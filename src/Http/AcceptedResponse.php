<?php

namespace Drenso\Shared\Http;

use Symfony\Component\HttpFoundation\Response;

/**
 * Small response extended to create an empty HTTP_ACCEPTED response.
 *
 * The request has been received but not yet acted upon.
 * It is noncommittal, since there is no way in HTTP to later send an asynchronous response indicating the outcome of the request.
 * It is intended for cases where another process or server handles the request, or for batch processing.
 */
class AcceptedResponse extends Response
{
  /** @param array<string, list<string|null>> $headers */
  public function __construct(array $headers = [])
  {
    parent::__construct(null, Response::HTTP_ACCEPTED, $headers);
  }
}
