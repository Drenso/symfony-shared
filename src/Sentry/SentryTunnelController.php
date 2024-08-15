<?php

namespace Drenso\Shared\Sentry;

use DateInterval;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SentryTunnelController extends AbstractController
{
  private const CACHE_KEY = 'drenso.sentry_tunnel.available';

  /** @param string[] $allowedDsn */
  public function __construct(
    private readonly HttpClientInterface $httpClient,
    private readonly ?CacheInterface $appCache,
    private readonly array $allowedDsn,
    private readonly int $connectTimeout,
    private readonly int $maxDuration)
  {
  }

  public function tunnel(Request $request): Response
  {
    if (!$this->isAvailable()) {
      // Cache indicates service is not available
      return new Response(status: Response::HTTP_SERVICE_UNAVAILABLE);
    }

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

    try {
      $request = $this->httpClient->request(
        Request::METHOD_POST,
        sprintf('https://%s/api/%d/envelope/', $parsedDsn['host'] ?? null, intval(trim($parsedDsn['path'] ?? '', '/'))),
        [
          'body'    => $content,
          'headers' => [
            'Content-Type' => 'application/x-sentry-envelope',
          ],
          'timeout'       => $this->connectTimeout,
          'max_duration'  => $this->maxDuration,
          'max_redirects' => 0, // Do not follow redirects
        ]
      );

      return new Response($request->getContent());
    } catch (TransportExceptionInterface|RedirectionExceptionInterface) {
      $this->markUnavailable();

      return new Response(status: Response::HTTP_SERVICE_UNAVAILABLE);
    } catch (ClientExceptionInterface|ServerExceptionInterface) {
      return new Response(status: $request->getStatusCode());
    }
  }

  private function isAvailable(): bool
  {
    // Fill with true if there is nothing in the cache
    return $this->appCache?->get(self::CACHE_KEY, static fn (): bool => true) ?? true;
  }

  private function markUnavailable(): void
  {
    $this->appCache?->delete(self::CACHE_KEY);
    $this->appCache?->get(self::CACHE_KEY, static function (ItemInterface $item): bool {
      $item->expiresAfter(new DateInterval('PT5M'));

      return false;
    });
  }
}
