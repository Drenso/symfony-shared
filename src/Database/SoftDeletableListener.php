<?php

namespace Drenso\Shared\Database;

use Doctrine\ORM\EntityManagerInterface;
use Gedmo\SoftDeleteable\Event\PreSoftDeleteEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SoftDeletableListener
{
  /** Field name for deleted by. */
  final public const string FIELD_NAME = 'deletedBy';

  public function __construct(private readonly TokenStorageInterface $tokenStorage)
  {
  }

  /**
   * Sets the deletedBy field.
   *
   * @param PreSoftDeleteEventArgs<EntityManagerInterface> $args
   */
  public function preSoftDelete(PreSoftDeleteEventArgs $args): void
  {
    // Get needed objects
    $object = $args->getObject();
    $om     = $args->getObjectManager();
    $uow    = $om->getUnitOfWork();

    // Get old field value
    $meta     = $om->getClassMetadata($object::class);
    $oldValue = $meta->getFieldValue($object, self::FIELD_NAME);

    // Update the value
    $token = $this->tokenStorage->getToken();
    $user  = $token?->getUserIdentifier() ?? 'anon.';
    $meta->setFieldValue($object, self::FIELD_NAME, $user);

    // Make sure the unit of works knows about this
    $uow->propertyChanged($object, self::FIELD_NAME, $oldValue, $user);
    $uow->scheduleExtraUpdate($object, [
      self::FIELD_NAME => [$oldValue, $user],
    ]);
  }
}
