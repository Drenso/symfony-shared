<?php

namespace Drenso\Shared;

use Drenso\Shared\Interfaces\UlidInterface;
use Symfony\Component\Uid\Ulid;

class UlidHelper
{
  public static function refreshUlid(Ulid $source): Ulid
  {
    return new Ulid(Ulid::generate($source->getDateTime()));
  }

  /**
   * @template T of UlidInterface
   *
   * @param iterable<T> $objects
   *
   * @return string[]
   */
  public static function mapToUlid(iterable $objects): array
  {
    $result = [];
    foreach ($objects as $object) {
      $result[] = $object->getUlid()->toBase32();
    }

    return $result;
  }

  public static function getUlid(?UlidInterface $object): ?Ulid
  {
    return $object?->getUlid();
  }
}
