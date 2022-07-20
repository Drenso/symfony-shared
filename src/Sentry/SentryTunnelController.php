<?php

namespace Drenso\Shared\Sentry;

use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SentryTunnelController extends AbstractController
{
  public function __construct(
      private readonly HttpClientInterface $httpClient,
      private readonly array $allowedDsn)
  {
  }

  public function tunnel(Request $request): Response
  {
    $content = $request->getContent();
    $pieces  = explode("\n", (string)$content);
    if (empty($pieces)) {
      // Invalid content
      return new Response(status: Response::HTTP_NOT_FOUND);
    }

    try {
      $header = json_decode($pieces[0], true, 512, JSON_THROW_ON_ERROR);
    } catch (JsonException) {
      // Invalid JSON received, nothing we can do about that
      return new Response(status: Response::HTTP_BAD_REQUEST);
    }

    if (!$dsn = ($header['dsn'] ?? null)) {
      // DSN not supplied
      return new Response(status: Response::HTTP_BAD_REQUEST);
    }

    if (!in_array($dsn, $this->allowedDsn)) {
      // DSN not allowed
      return new Response(status: Response::HTTP_NOT_FOUND);
    }

    $parsedDsn = parse_url((string)$dsn);
    if (false === $parsedDsn) {
      // Invalid Sentry host
      return new Response(status: Response::HTTP_NOT_FOUND);
    }

    $request = $this->httpClient->request(
        Request::METHOD_POST,
        sprintf('https://%s/api/%d/envelope/', $parsedDsn['host'] ?? null, intval(trim(($parsedDsn['path'] ?? ''), '/'))),
        [
            'body'    => $content,
            'headers' => [
                'Content-Type' => 'application/x-sentry-envelope',
            ],
        ]
    );

    return new Response($request->getContent());
  }
}
