<?php

namespace Agrume\Limonade\Service;

use Agrume\Limonade\Annotation\ApiResource;
use ReflectionClass;
use Symfony\Component\Finder\Finder;

class ApiResourceAnnotationReader
{

    private array $resources = [];

    public function __construct(
        private string $entityDir = 'src/Entity'
    ) {}

    public function loadApiResources(): array
    {
        if (!empty($this->resources)) {
            return $this->resources;
        }

        $finder = new Finder();
        $finder->files()->in($this->entityDir)->name('*.php');

        foreach ($finder as $file) {
            $className = $this->getClassNameFromFile($file->getRealPath());
            if ($className && class_exists($className)) {
                $reflection = new ReflectionClass($className);
                $attributes = $reflection->getAttributes(ApiResource::class);

                foreach ($attributes as $attribute) {
                    $apiResource = $attribute->newInstance();
                    $this->resources[$className] = [
                        'class' => $className,
                        'reflection' => $reflection,
                        'apiResource' => $apiResource
                    ];
                }
            }
        }

        return $this->resources;
    }

    private function getClassNameFromFile(string $filePath): ?string
    {
        $content = file_get_contents($filePath);

        // Extraction du namespace
        if (preg_match('/namespace\s+([^;]+);/', $content, $namespaceMatches)) {
            $namespace = $namespaceMatches[1];
        } else {
            return null;
        }

        // Extraction du nom de classe
        if (preg_match('/class\s+(\w+)/', $content, $classMatches)) {
            $className = $classMatches[1];
            return $namespace . '\\' . $className;
        }

        return null;
    }

    public function getResourceByClass(string $className): ?array
    {
        $resources = $this->loadApiResources();
        return $resources[$className] ?? null;
    }
}