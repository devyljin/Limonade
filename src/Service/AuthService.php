<?php

namespace Agrume\Limonade\Service;

use Agrume\Limonade\DTO\API\Auth\BaseAuthRequest;
use Agrume\Limonade\DTO\API\Auth\LoginResponseDTO;
use Agrume\Limonade\DTO\API\Auth\RegisterResponseDTO;
use Agrume\Limonade\Service\AbstractHttpService;
use Agrume\Limonade\Service\ServiceConfig;

/**
 * Service for interacting with the Auth microservice.
 */
class AuthService extends AbstractHttpService
{
    /**
     * @inheritDoc
     */
    protected function getBaseUrl(): string
    {
        return ServiceConfig::getServiceUrl(ServiceConfig::AUTH_SERVICE_NAME);
    }

}