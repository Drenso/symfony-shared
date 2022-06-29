<?php

namespace Drenso\Shared\Sentry;

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
      throw $this->createNotFoundException('Invalid content');
    }

    $header = json_decode($pieces[0], true, 512, JSON_THROW_ON_ERROR);

    if (!$dsn = ($header['dsn'] ?? null)) {
      throw $this->createNotFoundException('DSN not supplied');
    }

    if (!in_array($dsn, $this->allowedDsn)) {
      throw $this->createNotFoundException('DSN not allowed');
    }

    $parsedDsn = parse_url((string)$dsn);
    if (false === $parsedDsn) {
      throw $this->createNotFoundException('Invalid Sentry host');
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
