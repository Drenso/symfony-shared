<?php

namespace Drenso\Shared\Database\RepositoryTraits;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;

/**
 * Trait HasDefaultRepositoryTrait.
 *
 * @method QueryBuilder createQueryBuilder(string $alias)
 */
trait HasDefaultRepositoryTrait
{
  /**
   * Get the default value. Can be null.
   *
   * @return mixed|null
   */
  public function getDefault()
  {
    try {
      return $this
          ->createQueryBuilder('d')
          ->where('d.isDefault = :default')
          ->setParameter('default', true)
          ->setMaxResults(1)
          ->getQuery()
          ->getSingleResult();
    } catch (NoResultException|NonUniqueResultException) {
      return null;
    }
  }

  /**
   * Clear the default value in the database.
   */
  public function clearDefault()
  {
    $this
        ->createQueryBuilder('d')
        ->update()
        ->set('d.isDefault', false)
        ->getQuery()
        ->execute();
  }
}
