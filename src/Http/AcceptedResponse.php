<?php

namespace Drenso\Shared\Http;

use Symfony\Component\HttpFoundation\Response;

/**
 * Small response extended to create an empty HTTP_ACCEPTED response.
 */
class AcceptedResponse extends Response
{
  public function __construct(array $headers = [])
  {
    parent::__construct(null, Response::HTTP_ACCEPTED, $headers);
  }
}
