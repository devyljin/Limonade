<?php

namespace Agrume\Limonade\Mediator\MediatorEvent;

use Agrume\Limonade\Mediator\AbstractLoggedMediatorEvent;
use Psr\Log\LogLevel;

class RequestMediatorEvent extends AbstractLoggedMediatorEvent
{
    protected int|\Monolog\Level|string $logLevel = LogLevel::INFO;
    public function getMessage(): string
    {
        return $this->classShortName . " will be requested.";
    }
}