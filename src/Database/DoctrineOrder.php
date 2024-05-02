<?php

namespace Drenso\Shared\Database;

/** Convenient replacement for Criteria::ASC and Criteria::DESC, to be used in Doctrine ORM context. */
final class DoctrineOrder
{
  final public const ASC  = 'ASC';
  final public const DESC = 'DESC';
}
