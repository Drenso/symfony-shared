<?php

namespace Drenso\Shared\Database\RepositoryTraits;

use Doctrine\ORM\QueryBuilder;

/**
 * Trait RowCountTrait
 *
 * @method QueryBuilder createQueryBuilder(string $alias)
 */
trait RowCountTrait
{
  /**
   * Get the full table row count
   *
   * @return int
   */
  public function getRowCount(): int
  {
    return $this->getRowCountForBuilder($this->createQueryBuilder('e'), 'e.id');
  }

  /**
   * Retrieve the row count for the given builder and column
   *
   * @param QueryBuilder $queryBuilder
   * @param string       $column
   *
   * @return int
   *
   * @noinspection PhpUnhandledExceptionInspection
   * @noinspection PhpDocMissingThrowsInspection
   */
  public function getRowCountForBuilder(QueryBuilder $queryBuilder, string $column): int
  {
    return $queryBuilder
        ->select(sprintf('COUNT(%s)', $column))
        ->getQuery()->getSingleScalarResult();
  }
}
