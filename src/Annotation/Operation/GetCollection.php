<?php

namespace Agrume\Limonade\Annotation\Operation;

use Agrume\Limonade\Annotation\Operation\HttpOperation;
use Attribute;

#[Attribute]
class GetCollection extends HttpOperation
{
    public function __construct(
        ?string $uriTemplate = null,
        ?string $name = null,
        ?array $requirements = null,
        ?string $controller = null,
        ?array $defaults = null,
        ?string $security = null,
        protected ?array $filters = null,
        protected ?array $paginationConfig = null
    ) {
        parent::__construct('GET', $uriTemplate, $name, $requirements, $controller, $defaults, $security);
    }

    public function getFilters(): ?array { return $this->filters; }
    public function getPaginationConfig(): ?array { return $this->paginationConfig; }
}