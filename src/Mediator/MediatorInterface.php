<?php

namespace Agrume\Limonade\Mediator;

use Agrume\Limonade\Mediator\MediatorEventInterface;

interface MediatorInterface
{
    /**
     * Handle Event and notify other components with a scheduled way
     * @param object $sender
     * @param MediatorEventInterface $event
     * @return mixed
     */
    public function notify(object $sender, MediatorEventInterface $event);

}