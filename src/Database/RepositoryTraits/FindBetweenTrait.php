<?php

namespace Drenso\Shared\Database\RepositoryTraits;

use DateTimeInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Trait FindBetweenTrait.
 *
 * @template T of object
 *
 * @method QueryBuilder createQueryBuilder(string $alias)
 *
 * @phpstan-ignore trait.unused
 */
trait FindBetweenTrait
{
  /**
   * Find all entities between two given datetime instances.
   * If any is empty, it will not be used in the constraint.
   *
   * @param string|null $alias          If not given, 'e' will be used
   * @param bool        $startInclusive If false, the compare will be exclusive
   * @param bool        $endInclusive   If false, the compare will be exclusive
   *
   * @return T[]
   */
  public function findBetween(
    ?DateTimeInterface $start,
    ?DateTimeInterface $end,
    ?string $alias = null,
    bool $startInclusive = true,
    bool $endInclusive = true): array
  {
    return $this
      ->findBetweenQb($start, $end, $alias, $startInclusive, $endInclusive)
      ->getQuery()->getResult();
  }

  /**
   * Creates an query builder that will find all entities between two given datetime instances.
   * If any is empty, it will not be used in the constraint.
   *
   * @param string|null $alias          If not given, 'e' will be used
   * @param bool        $startInclusive If false, the compare will be exclusive
   * @param bool        $endInclusive   If false, the compare will be exclusive
   */
  public function findBetweenQb(
    ?DateTimeInterface $start,
    ?DateTimeInterface $end,
    ?string $alias = null,
    bool $startInclusive = true,
    bool $endInclusive = true): QueryBuilder
  {
    $alias = $alias ?: 'e';
    $qb    = $this->createQueryBuilder($alias);

    if ($start) {
      $qb->andWhere(sprintf('%s.timestamp %s :start', $alias, $startInclusive ? '>=' : '>'))
        ->setParameter('start', $start);
    }

    if ($end) {
      $qb->andWhere(sprintf('%s.timestamp %s :end', $alias, $endInclusive ? '<=' : '<'))
        ->setParameter('end', $end);
    }

    return $qb;
  }
}
