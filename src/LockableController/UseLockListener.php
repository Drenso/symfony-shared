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

  public function __construct(private LockFactory $lockFactory)
  {
  }

  public static function getSubscribedEvents(): array
  {
    return [
        KernelEvents::CONTROLLER_ARGUMENTS => 'onKernelControllerArguments',
        KernelEvents::TERMINATE            => 'onKernelTerminate',
    ];
  }

  public function onKernelControllerArguments(ControllerArgumentsEvent $event)
  {
    $request = $event->getRequest();

    /** @var $attributes UseLock[] */
    if (!$attributes = $request->attributes->get('_drenso_use_lock')) {
      return;
    }

    foreach ($attributes as $attribute) {
      $lock = $this->lockFactory->createLock($attribute->lockName);
      $lock->acquire(true);
      $this->locks[] = $lock;
    }
  }

  public function onKernelTerminate()
  {
    foreach ($this->locks as $lock) {
      $lock->release();
    }
  }
}
