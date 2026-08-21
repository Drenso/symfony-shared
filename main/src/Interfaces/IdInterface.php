<?php

namespace Drenso\Shared\Interfaces;

interface IdInterface
{
  /** Retrieve the object id which can be null in case the object hasn't been persisted yet. */
  public function getId(): ?int;

  /** Retrieve the object id which is confirmed to be non-null. */
  public function getNonNullId(): int;
}
