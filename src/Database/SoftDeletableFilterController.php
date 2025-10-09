<?php

namespace Drenso\Shared\Database;

use Doctrine\ORM\EntityManagerInterface;
use Gedmo\SoftDeleteable\Filter\SoftDeleteableFilter;
use RuntimeException;

class SoftDeletableFilterController
{
  final public const string SOFT_DELETE_FILTER = 'softdeleteable'; // This is case-sensitive

  public function __construct(private readonly EntityManagerInterface $entityManager)
  {
  }

  public function isSoftDeleteFilterEnabled(): bool
  {
    return $this->entityManager->getFilters()->isEnabled(self::SOFT_DELETE_FILTER);
  }

  public function disableSoftDeleteFilter(): void
  {
    $this->entityManager->getFilters()->disable(self::SOFT_DELETE_FILTER);
  }

  public function enableSoftDeleteFilter(): void
  {
    $this->entityManager->getFilters()->enable(self::SOFT_DELETE_FILTER);
  }

  /** @param class-string $class */
  public function disableSoftDeleteFilterForEntity(string $class): void
  {
    $this->getSoftDeleteFilter()->disableForEntity($class);
  }

  /** @param class-string $class */
  public function enableSoftDeleteFilterForEntity(string $class): void
  {
    $this->getSoftDeleteFilter()->enableForEntity($class);
  }

  private function getSoftDeleteFilter(): SoftDeleteableFilter
  {
    $filter = $this->entityManager->getFilters()->getFilter(self::SOFT_DELETE_FILTER);
    if (!($filter instanceof SoftDeleteableFilter)) {
      throw new RuntimeException(sprintf('SoftDeleteableFilter filter expected to be of type %s, but was %s',
        SoftDeleteableFilter::class, $filter::class));
    }

    return $filter;
  }
}
