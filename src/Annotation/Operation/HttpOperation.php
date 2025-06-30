<?php

namespace Agrume\Limonade\Annotation\Operation;

abstract class HttpOperation
{
    public function __construct(
        protected ?string $method = null,
        protected ?string $uriTemplate = null,
        protected ?string $name = null,
        protected ?array $requirements = null,
        protected ?string $controller = null,
        protected ?array $defaults = null,
        protected ?string $security = null,
        protected ?array $validationGroups = null
    ) {}

    public function getMethod(): ?string { return $this->method; }
    public function getUriTemplate(): ?string { return $this->uriTemplate; }
    public function getName(): ?string { return $this->name; }
    public function getRequirements(): ?array { return $this->requirements; }
    public function getController(): ?string { return $this->controller; }
    public function getDefaults(): ?array { return $this->defaults; }
    public function getSecurity(): ?string { return $this->security; }
    public function getValidationGroups(): ?array { return $this->validationGroups; }
}