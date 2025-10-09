<?php

namespace Drenso\Shared\Messenger\Middleware;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\EntityManagerClosed;
use Override;
use Symfony\Bridge\Doctrine\Messenger\DoctrineTransactionMiddleware as BaseMiddleware;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\StopWorkerException;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;

class DoctrineTransactionMiddleware extends BaseMiddleware implements MiddlewareInterface
{
  #[Override]
  protected function handleForManager(
    EntityManagerInterface $entityManager,
    Envelope $envelope,
    StackInterface $stack,
  ): Envelope {
    if (!$envelope->all(ReceivedStamp::class)) {
      // Skip the normal doctrine transaction middleware when the message is not received
      return $stack->next()->handle($envelope, $stack);
    }

    try {
      return parent::handleForManager($entityManager, $envelope, $stack);
    } catch (EntityManagerClosed $e) {
      throw new StopWorkerException(previous: $e);
    }
  }
}
