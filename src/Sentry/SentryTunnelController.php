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
      private readonly string $host,
      private readonly int $projectId)
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

    $parsedDsn = parse_url($dsn);
    if (false === $parsedDsn || ($parsedDsn['host'] ?? null) !== $this->host) {
      throw $this->createNotFoundException('Invalid Sentry host');
    }

    if (intval(trim(($parsedDsn['path'] ?? ''), '/')) !== $this->projectId) {
      throw $this->createNotFoundException('Invalid project id');
    }

    $request = $this->httpClient->request(
        Request::METHOD_POST,
        sprintf('https://%s/api/%d/envelope/', $this->host, $this->projectId),
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
