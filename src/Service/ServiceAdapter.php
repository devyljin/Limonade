<?php

namespace Agrume\Limonade\Service;

use Agrume\Limonade\Adapter\AdapterInterface;
use Agrume\Limonade\Mediator\MediatorInterface;
use Agrume\Limonade\Mediator\MediatorScopeComponentInterface;
use Agrume\Limonade\Service\ApiGatewayService;
use Agrume\Limonade\Service\AuthService;
use Agrume\Limonade\Service\CoreService;
use Agrume\Limonade\Service\LegalService;
use Agrume\Limonade\Service\ServiceConfig;
use Agrume\Limonade\Service\ServiceLoggerMediator;
use Agrume\Limonade\Service\ServiceMediator;
use Psr\Log\LoggerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ServiceAdapter implements AdapterInterface, MediatorScopeComponentInterface
{
    protected string $context = 'api';
    protected string $version = 'v1';
    protected string $resource = 'none';
    protected array $action = [];
    protected  string $pathInfo = "/";
    protected $request;
    private bool $configured = false;
    private HttpClientInterface $httpClient;
    private LoggerInterface $logger;
    private ?ServiceMediator $mediator;
    private string $senderService;

    private AuthService $authService;
    private CoreService $coreService;
    private LegalService $legalService;
    private ApiGatewayService $apiGatewayService;

    private bool $servicesInitialized = false;
    public function __construct(HttpClientInterface $httpClient, LoggerInterface $logger, string $serviceName)
    {
        $this->senderService = $serviceName;
        $this->httpClient = $httpClient;
        $this->logger = $logger;

//        $this->initServices();
    }

    private function initServices(){
        $this->authService = ( new AuthService($this->httpClient, $this->logger));
        $this->coreService = ( new CoreService($this->httpClient, $this->logger));
        $this->legalService = ( new LegalService($this->httpClient, $this->logger));
        $this->apiGatewayService = ( new ApiGatewayService($this->httpClient, $this->logger));
        $this->servicesInitialized = true;
        $this->initMediator();
    }
    private function initMediator() {
        $this->mediator = new  ServiceMediator(
            $this,
            $this->authService,
            $this->coreService,
            $this->apiGatewayService,
            $this->legalService,
            $this->logger
        );
    }



    public function autoRequest(Request $request){
        $this->setRequest($request);
        $service = $this->adaptee();
        return $service->processRequest($request);
    }

    public function setRequest(Request $request): self {
        $this->request = $request;
        $this->pathInfo = $this->request->getPathInfo();
        $pathInfos = explode("/",$this->pathInfo);
        $this->context = $pathInfos[1] ?? $this->context;
        $this->version =$pathInfos[2] ??$this->version;
        $this->resource = $pathInfos[3] ?? $this->resource;
        $this->action =  array_slice($pathInfos, 4) ?? $this->action;
        $this->configured = true;
        return $this;
    }
    /**
     * @inheritDoc
     */
    public function adaptee(): object
    {
        $this->check();
        $serviceName = ServiceConfig::getServiceNameByResource($this->resource);
        if($serviceName === $this->senderService){
            throw new ServiceNotFoundException("You cannot request this service, you're already into the '" . $serviceName . "' service.");
        }
        if($this->servicesInitialized === true){
            return match($serviceName){
                ServiceConfig::AUTH_SERVICE_NAME =>$this->authService->setEndpoint($this->pathInfo),
                ServiceConfig::CORE_SERVICE_NAME =>$this->coreService->setEndpoint($this->pathInfo),
                ServiceConfig::LEGAL_SERVICE_NAME =>$this->legalService->setEndpoint($this->pathInfo),
                ServiceConfig::API_GATEWAY_SERVICE_NAME =>$this->apiGatewayService->setEndpoint($this->pathInfo),
                default => throw new ServiceNotFoundException($serviceName),
            };
        }


        $service =  match($serviceName){
            ServiceConfig::AUTH_SERVICE_NAME => function () {
                $service = new AuthService($this->httpClient, $this->logger);
                $service->setMediator((new ServiceLoggerMediator($service, $this->logger)))->setEndpoint($this->pathInfo);
                return $service;
            },
            ServiceConfig::CORE_SERVICE_NAME => function () {
                $service = new CoreService($this->httpClient, $this->logger);
                $service->setMediator((new ServiceLoggerMediator($service, $this->logger)))->setEndpoint($this->pathInfo);
                return $service;
            },
            ServiceConfig::LEGAL_SERVICE_NAME => function () {
                $service = new LegalService($this->httpClient, $this->logger);
                $service->setMediator((new ServiceLoggerMediator($service, $this->logger)))->setEndpoint($this->pathInfo);
                return $service;
            },
            ServiceConfig::API_GATEWAY_SERVICE_NAME => function () {
                $service = new ApiGatewayService($this->httpClient, $this->logger);
                $service->setMediator((new ServiceLoggerMediator($service, $this->logger)))->setEndpoint($this->pathInfo);
                return $service;
            },

            default => throw new ServiceNotFoundException($serviceName),
        };
        return $service();
    }


    private function check(): void {
        if(!$this->configured) {
            throw new Exception("Service Adapter Misconfigured, use the setRequest() method to adapt your request");
        }
    }

    public function setMediator(MediatorInterface $mediator)
    {
        $this->mediator = $mediator;
        return $this;
    }

    public function getMediator(): MediatorInterface
    {
        return $this->mediator;
    }
}