<?php

namespace Agrume\Limonade\DTO;

use Agrume\Limonade\Adapter\AdapterInterface;

abstract class AbstractDTOAdapter implements AdapterInterface
{
    private string $shortName;
    public function setShortName(string $name): AdapterInterface
    {
        $this->shortName = $name;
        return $this;
    }

    public function getShortName(): string
    {
        return $this->shortName;
    }
}