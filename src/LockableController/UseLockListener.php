<?php

namespace Drenso\Shared\LockableController;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\LockInterface;

class UseLockListener implements EventSubscriberInterface
{
  /** @var LockInterface[] */
  private array $locks = [];

  public function __construct(private readonly LockFactory $lockFactory)
  {
  }

  public static function getSubscribedEvents(): array
  {
    return [
      KernelEvents::CONTROLLER_ARGUMENTS => 'onKernelControllerArguments',
      KernelEvents::TERMINATE            => 'onKernelTerminate',
    ];
  }

  public function onKernelControllerArguments(ControllerArgumentsEvent $event): void
  {
    foreach ($event->getAttributes(UseLock::class) as $attribute) {
      /** @var UseLock $attribute */
      $lock = $this->lockFactory->createLock($attribute->lockName);
      $lock->acquire(true);
      $this->locks[] = $lock;
    }
  }

  public function onKernelTerminate(): void
  {
    foreach ($this->locks as $lock) {
      $lock->release();
    }
  }
}
