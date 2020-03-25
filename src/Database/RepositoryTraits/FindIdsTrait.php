<?php

namespace Drenso\Shared\Repository\Traits;

use Doctrine\ORM\QueryBuilder;

/**
 * Trait FindIdsTrait
 *
 * @method QueryBuilder createQueryBuilder(string $alias)
 */
trait FindIdsTrait
{
  /**
   * Retrieve entities by the given ids
   *
   * @param array       $ids
   * @param string|null $alias
   *
   * @return array
   */
  public function findByIds(array $ids, ?string $alias = NULL): array
  {
    $alias = $alias ?: 'e';
    $qb    = $this->createQueryBuilder($alias);

    return $qb
        ->where($qb->expr()->in(sprintf('%s.id', $alias), ':ids'))
        ->setParameter('ids', $ids)
        ->getQuery()->getResult();
  }
}
