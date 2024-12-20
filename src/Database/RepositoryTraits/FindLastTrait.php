<?php

namespace Drenso\Shared\Database\RepositoryTraits;

use Doctrine\ORM\QueryBuilder;

/**
 * Trait FindLastTrait.
 *
 * @template T of object
 *
 * @method QueryBuilder createQueryBuilder(string $alias)
 *
 * @phpstan-ignore trait.unused
 */
trait FindLastTrait
{
  /**
   * @noinspection PhpUnhandledExceptionInspection Does not happen due to query build
   * @noinspection PhpDocMissingThrowsInspection
   *
   * @return T|null
   */
  public function findLast(): mixed
  {
    return $this->findLastQb()
      ->getQuery()->getOneOrNullResult();
  }

  /** @return T[] */
  public function findLastX(int $amount): array
  {
    return $this->findLastQb($amount)
      ->getQuery()->getResult();
  }

  public function findLastQb(int $amount = 1, string $alias = 'e'): QueryBuilder
  {
    return $this->createQueryBuilder($alias)
      ->orderBy(sprintf('%s.timestamp', $alias), 'DESC')
      ->setMaxResults($amount);
  }
}
