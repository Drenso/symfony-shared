<?php

namespace Drenso\Shared\Database\Query;

class JsonExtractFunction extends JsonValueFunction
{
  public function __construct(string $name)
  {
    parent::__construct($name, 'JSON_EXTRACT');
  }
}
