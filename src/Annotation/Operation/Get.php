<?php

namespace Agrume\Limonade\Annotation\Operation;

use Agrume\Limonade\Annotation\Operation\HttpOperation;
use Attribute;

#[Attribute]
class Get extends HttpOperation
{
    public function __construct(
        ?string $uriTemplate = null,
        ?string $name = null,
        ?array $requirements = null,
        ?string $controller = null,
        ?array $defaults = null,
        ?string $security = null
    ) {
        parent::__construct('GET', $uriTemplate, $name, $requirements, $controller, $defaults, $security);
    }
}