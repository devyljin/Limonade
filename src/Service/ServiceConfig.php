<?php

namespace Agrume\Limonade\Service;

/**
 * Configuration for service endpoints.
 */
class ServiceConfig
{

    public const AUTH_SERVICE_NAME = "auth";
    public const CORE_SERVICE_NAME = "core";
    public const LEGAL_SERVICE_NAME = "legal";
    public const API_GATEWAY_SERVICE_NAME = 'api-gateway';

    public const CONTROLLERS = [
        // Auth Service
        'login' => self::AUTH_SERVICE_NAME,
        'permissions' => self::AUTH_SERVICE_NAME,
        // Core Service
        "coretest" => self::CORE_SERVICE_NAME,
        "employees" => self::CORE_SERVICE_NAME,
        // Legal
        "legaly" => self::LEGAL_SERVICE_NAME,
        "interservice" => self::LEGAL_SERVICE_NAME,
        // Add other services as needed
    ];
    public static function getServiceNameByResource(string $name) {

        return self::CONTROLLERS[$name];
    }
    /**
     * Service base URLs.
     */
    public const SERVICE_URLS = [
        self::API_GATEWAY_SERVICE_NAME => 'http://api-gateway:8000',
        self::AUTH_SERVICE_NAME => 'http://auth-service:8001',
        self::CORE_SERVICE_NAME => 'http://core-service:8002',
        self::LEGAL_SERVICE_NAME => 'http://legal-service:8003',
        // Add other services as needed
    ];

    /**
     * Get a service base URL.
     *
     * @param string $service Service name
     * @return string Service base URL
     * @throws \InvalidArgumentException If service not found
     */
    public static function getServiceUrl(string $service): string
    {
        if (!isset(self::SERVICE_URLS[$service])) {
            throw new \InvalidArgumentException("Service '{$service}' not found in configuration");
        }
        
        return self::SERVICE_URLS[$service];
    }

} 