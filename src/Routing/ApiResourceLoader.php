<?php
// src/Routing/ApiResourceLoader.php

namespace Agrume\Limonade\Routing;


use Agrume\Limonade\Service\ApiRouteGenerator;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\RouteCollection;

class ApiResourceLoader extends Loader
{
    private bool $loaded = false;

    public function __construct(
        private ApiRouteGenerator $routeGenerator
    ) {
        parent::__construct();
    }

    public function load($resource, string $type = null): RouteCollection
    {
        if (true === $this->loaded) {
            throw new \RuntimeException('Do not add the "api_resource" loader twice');
        }

        $routes = $this->routeGenerator->generateRoutes();
        $this->loaded = true;

        return $routes;
    }

    public function supports($resource, string $type = null): bool
    {
        return 'api_resource' === $type;
    }
}
