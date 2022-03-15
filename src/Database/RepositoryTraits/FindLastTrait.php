<?php

namespace Drenso\Shared\Database\RepositoryTraits;

use Doctrine\ORM\QueryBuilder;

/**
 * Trait FindLastTrait.
 *
 * @method QueryBuilder createQueryBuilder(string $alias)
 */
trait FindLastTrait
{
  /**
   * @return mixed
   *
   * @noinspection PhpUnhandledExceptionInspection Does not happen due to query build
   * @noinspection PhpDocMissingThrowsInspection
   */
  public function findLast()
  {
    return $this->findLastQb()
        ->getQuery()->getOneOrNullResult();
  }

  /** @return mixed */
  public function findLastX(int $amount)
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
