<?php

namespace Drenso\Shared\Command;

use DateTimeImmutable;
use Doctrine\DBAL\Connection as DBALConnection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Types;
use Drenso\Shared\Helper\DateTimeHelper;
use ReflectionClass;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Bridge\Doctrine\Transport\Connection;
use Symfony\Component\Messenger\Bridge\Doctrine\Transport\DoctrineTransport;
use Symfony\Component\Messenger\Transport\Sync\SyncTransport;
use Throwable;
use Zenstruck\Messenger\Monitor\Transports;

class CheckMessengerQueuesCommand extends Command
{
  // Icinga constants
  private const OK       = 0;
  private const WARNING  = 1;
  private const CRITICAL = 2;

  /** @param string[] $disabledQueues */
  public function __construct(
    private readonly ?Transports $transports,
    private readonly string $failedQueue,
    private readonly array $disabledQueues,
    private readonly int $nowMargin)
  {
    parent::__construct();
  }

  protected function execute(InputInterface $input, OutputInterface $output): int
  {
    try {
      return $this->doExecute($output);
    } catch (Throwable $e) {
      $output->writeln("CRITICAL - Command failed with {$e->getMessage()}");

      return self::CRITICAL;
    }
  }

  private function doExecute(OutputInterface $output): int
  {
    if (!$this->transports) {
      $output->writeln('Transports not found. Is zenstruck/messenger-monitor-bundle installed?');

      return self::CRITICAL;
    }

    // Check for failed messages
    $failureTransport = $this->transports->get($this->failedQueue);
    if (!$failureTransport->isCountable()) {
      $output->writeln(sprintf('Failure transport is not countable: %s', $failureTransport->get()::class));

      return self::CRITICAL;
    }

    $failedCount = $failureTransport->count();
    if ($failedCount >= 1) {
      $output->writeln($failedCount > 1
        ? "CRITICAL - There are $failedCount failed queue messages!"
        : 'CRITICAL - There is one failed queue message!');

      return self::CRITICAL;
    }

    $transportConnectionProperty = (new ReflectionClass(DoctrineTransport::class))
      ->getProperty('connection');
    $dbalConnectionProperty      = (new ReflectionClass(Connection::class))
      ->getProperty('driverConnection');

    $now             = new DateTimeImmutable('now', DateTimeHelper::getUtcTimeZone());
    $stuckDeadline   = $now->modify(sprintf('-%d minutes', $this->nowMargin * 2));
    $pendingDeadline = $now->modify(sprintf('-%d minutes', $this->nowMargin));

    // Check for available messages
    $stuck   = 0;
    $pending = 0;
    foreach ($this->transports->getIterator() as $name => $transport) {
      if ($name === $this->failedQueue) {
        // Skip failed transport
        continue;
      }

      if (in_array($name, $this->disabledQueues)) {
        // Skip disabled queues
        continue;
      }

      $sfTransport = $transport->get();
      if ($sfTransport instanceof SyncTransport) {
        // We are not interested in the sync transport
        continue;
      }

      if (!$sfTransport instanceof DoctrineTransport) {
        $output->writeln("Critical - Non doctrine transport found. $name - " . $sfTransport::class);

        return self::CRITICAL;
      }

      // We need the connection to get the table configuration here
      /** @var Connection $connection */
      $connection    = $transportConnectionProperty->getValue($sfTransport);
      $configuration = $connection->getConfiguration();

      /** @var DBALConnection $dbalConnection */
      $dbalConnection = $dbalConnectionProperty->getValue($connection);
      $table          = (string)$configuration['table_name'];
      $queue          = (string)$configuration['queue_name'];

      $stuck += $this->getQuery($dbalConnection, $table, $queue)
        ->setParameter('available_at', $stuckDeadline, Types::DATETIME_IMMUTABLE)
        ->andWhere('m.delivered_at IS NOT NULL')
        ->fetchOne();

      $pending += $this->getQuery($dbalConnection, $table, $queue)
        ->setParameter('available_at', $pendingDeadline, Types::DATETIME_IMMUTABLE)
        ->andWhere('m.delivered_at IS NULL')
        ->fetchOne();
    }

    if ($stuck > 0) {
      $output->writeln($stuck > 1
        ? "CRITICAL - There are $stuck stuck queue messages!"
        : 'WARNING - There is one stuck queue message!');

      return $stuck > 1 ? self::CRITICAL : self::WARNING;
    }

    if ($pending > 0) {
      $output->writeln($pending > 1
        ? "CRITICAL - There are $pending pending queue messages!"
        : 'WARNING - There is one pending queue message!');

      return $pending > 1 ? self::CRITICAL : self::WARNING;
    }

    $output->writeln('No failed, stuck or pending messages');

    return self::OK;
  }

  private function getQuery(DBALConnection $dbalConnection, string $table, string $queue): QueryBuilder
  {
    return $dbalConnection->createQueryBuilder()
      ->select('COUNT(id)')
      ->from($table, 'm')
      ->where('m.queue_name = :queue')->setParameter('queue', $queue)
      ->andWhere('m.available_at < :available_at')
      ->setMaxResults(1);
  }
}
