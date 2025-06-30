<?php

namespace Agrume\Limonade\Mediator;

use Agrume\Limonade\Mediator\MediatorEventInterface;

/**
 * Interface LoggedMediatorEventInterface
 *
 * Extends the base MediatorEventInterface by adding support for
 * logging-level management and log message content.
 * This is useful when mediator events are intended to be logged
 * for observability, debugging, or auditing.
 */
interface LoggedMediatorEventInterface extends MediatorEventInterface
{
    /**
     * Retrieves the log level associated with the event.
     *
     * Common log levels: 'debug', 'info', 'notice', 'warning', 'error', 'critical', etc.
     *
     * @return string|int|Level The log level (could be a PSR-3 compatible string or custom int constant)
     */
    public function getLogLevel();

    /**
     * Sets the log level for the event.
     *
     * @param string|int $logLevel The desired log level
     * @return void
     */
    public function setLogLevel($logLevel);

    /**
     * Gets the message to be written to the logs.
     *
     * @return string A human-readable log message
     */
    public function getMessage(): string;

    /**
     * Sets the log message content.
     *
     * @param string $message The message to log
     * @return self Fluent interface
     */
    public function setMessage(string $message): self;



}