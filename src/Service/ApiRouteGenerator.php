<?php

namespace Agrume\Limonade\Service;
use Agrume\Limonade\Core\Annotation\Operation\Delete;
use Agrume\Limonade\Core\Annotation\Operation\Get;
use Agrume\Limonade\Core\Annotation\Operation\GetCollection;
use Agrume\Limonade\Core\Annotation\Operation\Patch;
use Agrume\Limonade\Annotation\Operation\Post;
use Agrume\Limonade\Annotation\Operation\Put;
use Agrume\Limonade\Service\ApiResourceAnnotationReader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class ApiRouteGenerator
{
    public function __construct(
        private ApiResourceAnnotationReader $annotationReader
    ) {}

    public function generateRoutes(): RouteCollection
    {
        $routes = new RouteCollection();
        $resources = $this->annotationReader->loadApiResources();

        foreach ($resources as $resourceData) {
            $apiResource = $resourceData['apiResource'];
            $className = $resourceData['class'];

            $this->generateRoutesForResource($routes, $apiResource, $className);
        }

        return $routes;
    }

    private function generateRoutesForResource(RouteCollection $routes, $apiResource, string $className): void
    {
        $routePrefix = $apiResource->getRoutePrefix();
        $shortName = $apiResource->getShortName();
        $operations = $apiResource->getOperations();

        // Si aucune opération n'est définie, créer les opérations CRUD par défaut
        if (!$operations) {
            $operations = $this->getDefaultOperations($routePrefix);
        }

        foreach ($operations as $operation) {
            $route = $this->createRouteFromOperation($operation, $routePrefix, $shortName, $className);
            if ($route) {
                $routeName = $this->generateRouteName($operation, $shortName);
                $routes->add($routeName, $route);
            }
        }
    }

    private function getDefaultOperations(string $routePrefix): array
    {
        return [
            new GetCollection(uriTemplate: $routePrefix),
            new Get(uriTemplate: $routePrefix . '/{id}'),
            new Post(uriTemplate: $routePrefix),
            new Put(uriTemplate: $routePrefix . '/{id}'),
            new Patch(uriTemplate: $routePrefix . '/{id}'),
            new Delete(uriTemplate: $routePrefix . '/{id}')
        ];
    }

    private function createRouteFromOperation($operation, string $routePrefix, string $shortName, string $className): ?Route
    {
        $uriTemplate = $operation->getUriTemplate() ?? $this->getDefaultUriTemplate($operation, $routePrefix);
        $controller = $operation->getController() ?? $this->getDefaultController($operation, $shortName);

        $defaults = array_merge(
            ['_controller' => $controller, '_api_resource_class' => $className],
            $operation->getDefaults() ?? []
        );

        $requirements = $operation->getRequirements() ?? [];
        if (str_contains($uriTemplate, '{id}')) {
            $requirements['id'] = '[0-9a-fA-F\-]{36}';

        }

        return new Route(
            path: $uriTemplate,
            defaults: $defaults,
            requirements: $requirements,
            methods: [$operation->getMethod()]
        );
    }

    private function getDefaultUriTemplate($operation, string $routePrefix): string
    {
        switch (get_class($operation)) {
            case GetCollection::class:
            case Post::class:
                return $routePrefix;
            case Get::class:
            case Put::class:
            case Patch::class:
            case Delete::class:
                return $routePrefix . '/{id}';
            default:
                return $routePrefix;
        }
    }

    private function getDefaultController($operation, string $shortName): string
    {
        $controllerPrefix = 'App\\Controller\\' . $shortName . 'Controller::';

        switch (get_class($operation)) {
            case GetCollection::class:
                return $controllerPrefix . 'index';
            case Get::class:
                return $controllerPrefix . 'show';
            case Post::class:
                return $controllerPrefix . 'create';
            case Put::class:
                return $controllerPrefix . 'update';
            case Patch::class:
                return $controllerPrefix . 'patch';
            case Delete::class:
                return $controllerPrefix . 'delete';
            default:
                return $controllerPrefix . 'index';
        }
    }

    private function generateRouteName($operation, string $shortName): string
    {
        $operationName = strtolower(substr(strrchr(get_class($operation), '\\'), 1));
        return 'api_' . strtolower($shortName) . '_' . $operationName;
    }
}