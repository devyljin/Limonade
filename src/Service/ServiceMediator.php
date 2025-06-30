<?php

namespace Agrume\Limonade\Service;

use Agrume\Limonade\Mediator\AbstractMediator;
use Agrume\Limonade\Mediator\MediatorInterface;
use Agrume\Limonade\Service\ApiGatewayService;
use Agrume\Limonade\Service\AuthService;
use Agrume\Limonade\Service\CoreService;
use Agrume\Limonade\Service\LegalService;
use Agrume\Limonade\Service\ServiceAdapter;
use Psr\Log\LoggerInterface;

class ServiceMediator extends AbstractMediator implements MediatorInterface
{
    protected AuthService $authService;
    protected CoreService $coreService;
    protected LegalService $legalService;
    protected ApiGatewayService $apiGatewayService;

    protected ServiceAdapter $serviceAdapter;
    public function __construct(ServiceAdapter $serviceAdapter, AuthService $authService, CoreService $coreService, ApiGatewayService $apiGatewayService,LegalService $legalService, LoggerInterface $logger){
        $this->serviceAdapter = $serviceAdapter;
        $this->authService = $authService;
        $this->coreService = $coreService;
        $this->apiGatewayService = $apiGatewayService;
        $this->legalService = $legalService;
        $this->logger = $logger;

        $this->initMediatoring();

    }

    private function initMediatoring(){
        $this->serviceAdapter->setMediator($this);
        $this->authService->setMediator($this);
        $this->coreService->setMediator($this);
        $this->apiGatewayService->setMediator($this);
        $this->legalService->setMediator($this);
    }

}