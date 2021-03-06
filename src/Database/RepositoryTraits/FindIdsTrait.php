<?php

namespace Drenso\Shared\Database\RepositoryTraits;

use Doctrine\ORM\QueryBuilder;

/**
 * Trait FindIdsTrait
 *
 * @method QueryBuilder createQueryBuilder(string $alias)
 */
trait FindIdsTrait
{
  /**
   * Get the query builder to retrieve entities by the given ids
   *
   * @param array       $ids
   * @param string|null $alias
   *
   * @return QueryBuilder
   */
  public function findByIdsQb(array $ids, ?string $alias = NULL): QueryBuilder
  {
    $alias = $alias ?: 'e';
    $qb    = $this->createQueryBuilder($alias);

    return $qb
        ->where($qb->expr()->in(sprintf('%s.id', $alias), ':ids'))
        ->setParameter('ids', $ids);
  }

  /**
   * Retrieve entities by the given ids
   *
   * @param array       $ids
   *
   * @return array
   */
  public function findByIds(array $ids): array
  {
    return $this->findByIdsQb($ids)->getQuery()->getResult();
  }
}
