<?php

namespace Drenso\Shared\LockableController;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\LockInterface;

class UseLockListener implements EventSubscriberInterface
{
  /** @var LockFactory */
  private $lockFactory;
  /** @var LockInterface[] */
  private $locks = [];

  public function __construct(LockFactory $lockFactory)
  {
    $this->lockFactory = $lockFactory;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents()
  {
    return [
        KernelEvents::CONTROLLER_ARGUMENTS => 'onKernelControllerArguments',
        KernelEvents::TERMINATE            => 'onKernelTerminate',
    ];
  }

  public function onKernelControllerArguments(ControllerArgumentsEvent $event)
  {
    $request = $event->getRequest();

    /** @var $annotations UseLock[] */
    if (!$annotations = $request->attributes->get('_drenso_use_lock')) {
      return;
    }

    foreach ($annotations as $annotation) {
      $lock = $this->lockFactory->createLock($annotation->getLockName());
      $lock->acquire(true);
      $this->locks[] = $lock;
    }
  }

  public function onKernelTerminate(TerminateEvent $event)
  {
    foreach ($this->locks as $lock) {
      $lock->release();
    }
  }
}
