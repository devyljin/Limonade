<?php

namespace Agrume\Limonade\Annotation\Operation;

use Agrume\Limonade\Annotation\Operation\HttpOperation;
use Attribute;

#[Attribute]
class Delete extends HttpOperation
{
    public function __construct(
        ?string $uriTemplate = null,
        ?string $name = null,
        ?array $requirements = null,
        ?string $controller = null,
        ?array $defaults = null,
        ?string $security = null
    ) {
        parent::__construct('DELETE', $uriTemplate, $name, $requirements, $controller, $defaults, $security);
    }
}