<?php

namespace Drenso\Shared\Database\RepositoryTraits;

use Doctrine\ORM\QueryBuilder;

/**
 * Trait RowCountTrait.
 *
 * @method QueryBuilder createQueryBuilder(string $alias)
 *
 * @phpstan-ignore trait.unused
 */
trait RowCountTrait
{
  /** Get the full table row count. */
  public function getRowCount(): int
  {
    return $this->getRowCountForBuilder($this->createQueryBuilder('e'), 'e.id');
  }

  /**
   * Retrieve the row count for the given builder and column.
   *
   * @noinspection PhpUnhandledExceptionInspection
   */
  public function getRowCountForBuilder(QueryBuilder $queryBuilder, string $column): int
  {
    return $queryBuilder
      ->select(sprintf('COUNT(%s)', $column))
      ->getQuery()->getSingleScalarResult();
  }

  /** Get whether the query builder returns any result */
  public function hasAnyRows(QueryBuilder $queryBuilder, string $column): bool
  {
    return $this->getRowCountForBuilder($queryBuilder, $column) > 0;
  }
}
