<?php

namespace Agrume\Limonade\Annotation\Operation;

use Agrume\Limonade\Annotation\Operation\HttpOperation;
use Attribute;

#[Attribute]
class Post extends HttpOperation
{
    public function __construct(
        ?string $uriTemplate = null,
        ?string $name = null,
        ?array $requirements = null,
        ?string $controller = null,
        ?array $defaults = null,
        ?string $security = null,
        ?array $validationGroups = null
    ) {
        parent::__construct('POST', $uriTemplate, $name, $requirements, $controller, $defaults, $security, $validationGroups);
    }
}