<?php

namespace Agrume\Limonade\Mediator;
/**
 * Interface MediatorEventInterface
 *
 * Defines the structure of an event object used within a mediator-based communication system.
 * A mediator event encapsulates the sender, the event name, and its originating class context.
 */
interface MediatorEventInterface
{
    /**
     * MediatorEventInterface constructor.
     *
     * Initializes a new event with a sender object and an event name.
     *
     * @param object $sender The object that triggered the event
     * @param string $eventName The name of the event
     */
    public function __construct(object $sender, string $eventName);
    /**
     * Sets the event's name.
     *
     * @param string $name The name to assign to the event
     * @return self
     */
    public function setName(string $name): self;
    /**
     * Gets the event's name.
     *
     * @return string The name of the event
     */
    public function getName(): string;
    /**
     * Gets the sender object that initiated the event.
     *
     * @return object The originating object of the event
     */
    public function getSender(): object;
    /**
     * Gets the fully qualified class name of the sender.
     *
     * @return string The class name of the event sender
     */
    public function getClassName(): string;
    /**
     * Sets the class name based on the sender object.
     *
     * @param object $sender The object from which to extract the class name
     * @return self
     */
    public function setClassName(object $sender): self;
}