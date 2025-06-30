<?php

namespace Agrume\Limonade\Annotation;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class ApiResource
{
    public function __construct(
        protected ?string $routePrefix = null,
        protected ?string $shortName = null,
        protected ?array $operations = null,
        protected ?array $formats = ['json'],
        protected ?array $validationGroups = null,
        protected ?string $security = null,
        protected ?array $paginationConfig = null,
        protected ?array $filters = null,
        protected ?array $extraProperties = []
    ) {
        // Génération automatique du shortName si non fourni
        if (!$this->shortName && $this->routePrefix) {
            $this->shortName = ucfirst(trim($this->routePrefix, '/'));
        }
    }

    // Getters
    public function getRoutePrefix(): ?string { return $this->routePrefix; }
    public function getShortName(): ?string { return $this->shortName; }
    public function getOperations(): ?array { return $this->operations; }
    public function getFormats(): ?array { return $this->formats; }
    public function getValidationGroups(): ?array { return $this->validationGroups; }
    public function getSecurity(): ?string { return $this->security; }
    public function getPaginationConfig(): ?array { return $this->paginationConfig; }
    public function getFilters(): ?array { return $this->filters; }
    public function getExtraProperties(): array { return $this->extraProperties; }
}