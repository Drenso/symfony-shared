<?php

namespace Drenso\Shared\Http;

use Symfony\Component\HttpFoundation\Response;

/**
 * Small response extended to create an empty HTTP_NO_CONTENT response.
 *
 * There is no content to send for this request, but the headers are useful.
 * The user agent may update its cached headers for this resource with the new ones.
 */
class NoContentResponse extends Response
{
  /** @param array<string, list<string|null>> $headers */
  public function __construct(array $headers = [])
  {
    parent::__construct(null, Response::HTTP_NO_CONTENT, $headers);
  }
}
