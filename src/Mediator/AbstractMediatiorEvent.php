<?php

namespace Agrume\Limonade\Mediator;
use Agrume\Limonade\Mediator\MediatorEventInterface;

/**
 * Class AbstractMediatiorEvent
 *
 * Base implementation of the MediatorEventInterface.
 * Provides core event properties and logic for mediator-based event handling,
 * including sender tracking, event naming, and reflection-based class metadata.
 *
 * Intended to be extended by concrete event classes used within a mediator system.
 */
abstract class AbstractMediatiorEvent implements MediatorEventInterface
{
    /**
     * @var object The object that triggered the event
     */
    protected object $sender;
    /**
     * @var string The name of the event
     */
    protected string $eventName = "abstract.event";
    /**
     * @var string The short class name (without namespace) of the sender
     */
    protected string $classShortName;
    /**
     * Constructor.
     *
     * Initializes the event with a sender object and optionally an event name.
     * Also extracts and stores the short class name of the sender via reflection.
     *
     * @param object $sender The object that created the event
     * @param string|null $eventName Optional name of the event
     */
    public function __construct(object $sender, string $eventName = null){
        $this->sender = $sender;
        if(!is_null($eventName) ) {
            $this->eventName = $eventName;
        }
        $this->setClassName($sender);
    }


    /**
     * Gets the short class name of the sender object.
     *
     * @return string Short class name (no namespace)
     */
    public function getClassName(): string
    {
        return $this->classShortName;

    }
    /**
     * Sets the class name by using reflection on the sender.
     *
     * @param object $sender The sender object
     * @return $this
     */
    public function setClassName(object $sender): self
    {
        $this->classShortName = (new \ReflectionClass($sender))->getShortName();
        return $this;
    }

    /**
     * Sets the name of the event.
     *
     * @param string $name Event name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->eventName = $name;
        return $this;
    }
    /**
     * Gets the name of the event.
     *
     * @return string Event name
     */
    public function getName(): string {
        return $this->eventName;
    }
    /**
     * Sets the sender object.
     *
     * Protected to allow mutation from child classes only.
     *
     * @param object $sender The sender object
     * @return $this
     */
    protected function setSender(object $sender): self
    {
        $this->sender = $sender;
        return $this;
    }
    /**
     * Gets the sender object of the event.
     *
     * @return object The object that triggered the event
     */
    public function getSender(): object {
        return $this->sender;
    }
}