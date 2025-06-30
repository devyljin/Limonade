<?php

namespace Agrume\Limonade\DTO\Event;

use Agrume\Limonade\DTO\DTOInterface;

interface EventDTOInterface extends DTOInterface
{
    /**
     * Safe Getter to get the symfony event nickname
     *
     * @return string The name of the event e.g. "kernel.exception"
     */
    public function getName(): string;

    /**
     * Set the symfony event nickname to identify the event to mount
     *
     * @param string|\Stringable $name
     */
    public function setName(string|\Stringable $name): self;

}