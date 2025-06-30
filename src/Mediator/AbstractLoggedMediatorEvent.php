<?php

namespace Agrume\Limonade\Mediator;


use Agrume\Limonade\Mediator\AbstractMediatiorEvent;
use Agrume\Limonade\Mediator\LoggedMediatorEventInterface;
use Monolog\Level;

/**
 * Class AbstractLoggedMediatorEvent
 *
 * Base class for mediator events that are intended to be logged.
 * Provides support for setting a log message and log level,
 * and defines a default generic event name of "log".
 *
 * Implements LoggedMediatorEventInterface and is intended
 * to be extended by concrete logging event classes.
 */
abstract class AbstractLoggedMediatorEvent extends AbstractMediatiorEvent implements LoggedMediatorEventInterface
{
    /**
     * @var string The default generic name of the event (used by Mediator for routing)
     */
    protected string $eventName = "log";

    /**
     * @var string The message to be logged
     */
    private string $message;

    /**
     * @var string|int|Level The log level (e.g., "info", "warning", "error") please, use the Monolog constants (e.g LogLevel::INFO, LogLevel::WARNING...)
     */
    protected string|int|Level $logLevel = 0;

    /**
     * @var mixed|null Optional payload data associated with the event
     */
    protected $payload = null;

    /**
     * Gets the message to be written to the log.
     *
     * @return string Log message
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Sets the message to be written to the log.
     *
     * @param string $message The log message content
     * @return $this
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }
    /**
     * Gets the severity level of the log event.
     *
     * @return string|int|Level Log level (PSR-3 compatible string or int)
     */
    public function getLogLevel()
    {
        return $this->logLevel;
    }

    /**
     * Sets the log level (severity) for the event.
     *
     * @param string|int     $logLevel Log level (e.g., "info", "warning")
     * @return $this
     */
    public function setLogLevel($logLevel)
    {
        $this->logLevel = $logLevel;
        return $this;
    }
    /**
     * Gets the payload associated with the event.
     *
     * The payload can hold any additional data related to the event.
     *
     * @return mixed|null The payload data or null if none set
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * Sets the payload data associated with the event.
     *
     * The payload can be any type of data providing additional context or information
     * about the event.
     *
     * @param mixed $payload Payload data
     * @return $this
     */
    public function setPayload($payload): self
    {
        $this->payload = $payload;
        return $this;
    }
}