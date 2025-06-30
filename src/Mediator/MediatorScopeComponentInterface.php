<?php

namespace Agrume\Limonade\Mediator;
use Agrume\Limonade\Mediator\MediatorInterface;

/**
 * Interface MediatorScopeComponentInterface
 *
 * This interface should be implemented by any class that wants to
 * interact with a Mediator in a scoped communication pattern.
 *
 * It allows the implementing component to set and retrieve the mediator instance,
 * promoting loose coupling between components.
 */
interface MediatorScopeComponentInterface
{
    /**
     * Assigns a mediator to the component.
     *
     * @param MediatorInterface $mediator The mediator instance that coordinates communication
     * @return void
     */
    public function setMediator(MediatorInterface $mediator);
    /**
     * Retrieves the assigned mediator instance.
     *
     * @return MediatorInterface The currently assigned mediator
     */
    public function getMediator(): MediatorInterface;
}