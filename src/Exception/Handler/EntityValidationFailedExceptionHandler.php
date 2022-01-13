<?php

namespace Drenso\Shared\Exception\Handler;

use Drenso\Shared\Exception\EntityValidationFailedException;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class EntityValidationFailedExceptionHandler implements EventSubscriberInterface
{
  public function __construct(
      private SerializerInterface $serializer,
      private string              $controllerPrefix,
      private string              $dataField)
  {
  }

  public static function getSubscribedEvents()
  {
    return [
        KernelEvents::EXCEPTION => [
            ['handleException', 1024],
        ],
    ];
  }

  public function handleException(ExceptionEvent $event)
  {
    $exception = $event->getThrowable();

    if (!$exception instanceof EntityValidationFailedException) {
      return;
    }

    $controller = $event->getRequest()->attributes->get('_controller');

    if (!str_starts_with($controller, $this->controllerPrefix)) {
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
}
