<?php

namespace Agrume\Limonade\Service;

use Agrume\Limonade\Mediator\AbstractMediator;
use Agrume\Limonade\Mediator\MediatorInterface;
use Psr\Log\LoggerInterface;

class ServiceLoggerMediator extends AbstractMediator implements MediatorInterface
{
    protected $service;

    public function __construct($service, LoggerInterface $logger){
        $this->service= $service;
        $this->logger = $logger;

        $this->initMediatoring();

    }

    private function initMediatoring(){
        $this->service->setMediator($this);
    }

}