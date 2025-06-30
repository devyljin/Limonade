<?php

namespace Agrume\Limonade\Mediator\MediatorEvent;

use Agrume\Limonade\Mediator\AbstractLoggedMediatorEvent;
use Psr\Log\LogLevel;

class NullReturnedMediatorEvent extends AbstractLoggedMediatorEvent
{
    protected int|\Monolog\Level|string $logLevel = LogLevel::CRITICAL;
    public function getMessage(): string
    {
        return "Null returned";
    }
}