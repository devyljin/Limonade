<?php
namespace Agrume\Limonade\EventSubscriber;

use Agrume\Limonade\Entity\AbstractEntity;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\UnitOfWork;
use Symfony\Bundle\SecurityBundle\Security;

class DoctrineEventListener implements EventSubscriber
{
    private $user;
    private $isAutomated;

    public function __construct(Security $security){
        $this->user = $security->getUser();
        $this->isAutomated = is_null($this->user);
    }
    public function getSubscribedEvents(): array
    {
        return [
            Events::onFlush,
            Events::preRemove,
            Events::preUpdate,
        ];
    }


    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $em = $args->getObjectManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityUpdates() as $entity) {

            if ($entity instanceof AbstractEntity && !$entity->isDeleted() && false === $this->isAutomated) {
                $entity->setUpdatedBy($this->user->getId());
                $uow->propertyChanged($entity, 'updatedBy', null, $entity->getUpdatedBy());
            }
        }
        // Not Implemented
    }
    public function onFlush(OnFlushEventArgs $args): void
    {
        $em = $args->getObjectManager();
        $uow = $em->getUnitOfWork();
        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            if ($entity instanceof AbstractEntity && !$entity->isDeleted() && false === $this->isAutomated) {
                // Soft delete
                $entity->setDeletedAt(new \DateTimeImmutable());
                $entity->setUpdatedBy($this->user->getId());
                $uow->propertyChanged($entity, 'updatedBy', null, $entity->getUpdatedBy());

                $meta = $em->getClassMetadata(get_class($entity));
                $uow->propertyChanged($entity, 'deletedAt', null, $entity->getDeletedAt());
                $uow->scheduleExtraUpdate($entity, [
                    'deletedAt' => [null, $entity->getDeletedAt()],
                    'updatedBy' => [null ,$this->user->getId()],
                ]);
                $uow->cancelOrphanRemoval($entity);
                $this->removeEntityFromDeletions($uow, $entity);
            } else if($entity->isDeleted()){
                $uow->cancelOrphanRemoval($entity);
                $this->removeEntityFromDeletions($uow, $entity);
            }
        }

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof AbstractEntity && !$entity->isDeleted() && false === $this->isAutomated) {
                $entity->setUpdatedBy($this->user->getId());
                $entity->setCreatedBy($this->user->getId());
                $uow->propertyChanged($entity, 'updatedBy', null, $entity->getUpdatedBy());
                $uow->propertyChanged($entity, 'createdBy', null, $entity->getCreatedBy());
            }
        }
    }
    private function removeEntityFromDeletions(UnitOfWork $uow, object $entity): void
    {
        $reflection = new \ReflectionClass($uow);
        $property = $reflection->getProperty('entityDeletions');
        $property->setAccessible(true);

        /** @var array $deletions */
        $deletions = $property->getValue($uow);

        // Supprimer l'entité de la liste
        $deletions = array_filter($deletions, fn($e) => $e !== $entity);

        // Réinjecter la liste mise à jour
        $property->setValue($uow, $deletions);
    }
}
