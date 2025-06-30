<?php

namespace Agrume\Limonade\DTO\Event;

use Agrume\Limonade\DTO\AbstractDTOAdapter;
use Agrume\Limonade\DTO\Event\Response\JsonResponseEventDTO;
use Agrume\Limonade\DTO\Event\Response\ResponseEventDTO;
use Symfony\Component\Config\Definition\Exception\Exception;

class EventDTOAdapter extends AbstractDTOAdapter
{

    private $eventDTO;
    public function __construct(array $eventDTO){
        $this->eventDTO = $eventDTO;
    }

    /**
     * @inheritDoc
     */
    public function adaptee(): object
    {
        $key =array_keys($this->eventDTO)[0];
        return match($key) {
            'JsonResponse' => (new JsonResponseEventDTO())->unserialize($this->eventDTO["JsonResponse"]),
            'Response' => (new ResponseEventDTO())->unserialize($this->eventDTO["Response"]),
            default => throw new Exception("Pas d'adapteur pour $key, Ajoutez la dans l'EventDTOAdapter->adaptee()"),
        };
    }
}