<?php

namespace Drenso\Shared\Exception\Handler;

use Drenso\Shared\Exception\EntityValidationFailedException;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class EntityValidationFailedExceptionHandler
{
  /** @param string[] $controllerPrefixes */
  public function __construct(
    private readonly SerializerInterface $serializer,
    private readonly array $controllerPrefixes,
    private readonly string $dataField)
  {
  }

  public function __invoke(ExceptionEvent $event): void
  {
    $exception = $event->getThrowable();

    if (!$exception instanceof EntityValidationFailedException) {
      return;
    }

    if (!$this->matchesPrefix((string)$event->getRequest()->attributes->get('_controller'))) {
      return;
    }

    // Create a JSON response with a serialized representation of the validation exception
    $json = $this->serializer->serialize([
      'reason'         => 'validation.failed',
      $this->dataField => $exception->getViolationList(),
    ], 'json');
    $event->setResponse(new JsonResponse($json, Response::HTTP_BAD_REQUEST, [], true));

    // Wrap exception in Bad Request exception, to not trigger other handlers such as Sentry
    $event->setThrowable(new BadRequestHttpException($exception->getMessage(), $exception));
  }

  private function matchesPrefix(string $controller): bool
  {
    foreach ($this->controllerPrefixes as $controllerPrefix) {
      if (str_starts_with($controller, $controllerPrefix)) {
        return true;
      }
    }

    return false;
  }
}
