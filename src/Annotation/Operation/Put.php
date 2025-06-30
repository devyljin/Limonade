<?php
namespace Agrume\Limonade\Annotation\Operation;

use Agrume\Limonade\Annotation\Operation\HttpOperation;
use Attribute;

#[Attribute]
class Put extends HttpOperation
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
        parent::__construct('PUT', $uriTemplate, $name, $requirements, $controller, $defaults, $security, $validationGroups);
    }
}
