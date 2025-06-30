<?php

namespace Agrume\Limonade\DTO\Event;

use Agrume\Limonade\DTO\AbstractDTO;
use Agrume\Limonade\DTO\Event\EventDTOInterface;

abstract class AbstractEventDTO extends AbstractDTO implements EventDTOInterface
{
    protected string $name;


    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function setName(\Stringable|string $name): \Agrume\Limonade\DTO\Event\EventDTOInterface
    {
        $this->name = $name;
        return $this;
    }


}