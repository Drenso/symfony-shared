<?php

namespace Drenso\Shared\Database;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Gedmo\SoftDeleteable\SoftDeleteableListener;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SoftDeletableSubscriber implements EventSubscriber
{
  /**
   * Field name for deleted by.
   */
  final public const FIELD_NAME = 'deletedBy';

  /**
   * SoftDeletableSubscriber constructor.
   */
  public function __construct(private readonly TokenStorageInterface $tokenStorage)
  {
  }

  /**
   * Returns an array of events this subscriber wants to listen to.
   */
  public function getSubscribedEvents(): array
  {
    return [
        SoftDeleteableListener::PRE_SOFT_DELETE,
    ];
  }

  /**
   * Sets the deletedBy field.
   */
  public function preSoftDelete(LifecycleEventArgs $args)
  {
    // Get needed objects
    $object = $args->getObject();
    $om     = $args->getEntityManager();
    $uow    = $args->getEntityManager()->getUnitOfWork();

    // Get old field value
    $meta     = $om->getClassMetadata($object::class);
    $reflProp = $meta->getReflectionProperty(self::FIELD_NAME);
    $oldValue = $reflProp->getValue($object);

    // Update the value
    $token = $this->tokenStorage->getToken();
    $user  = $token
      // @phan-suppress-next-line PhanUndeclaredMethod,PhanDeprecatedFunction
        ? (method_exists($token, 'getUserIdentifier') ? $token->getUserIdentifier() : $token->getUsername())
        : 'anon.';
    $reflProp->setValue($object, $user);

    // Make sure the unit of works knows about this
    $uow->propertyChanged($object, self::FIELD_NAME, $oldValue, $user);
    $uow->scheduleExtraUpdate($object, [
        self::FIELD_NAME => [$oldValue, $user],
    ]);
  }
}
