<?php

namespace Agrume\Limonade\DTO\Event\Response;

use Agrume\Limonade\DTO\Event\AbstractEventDTO;
use Symfony\Component\HttpFoundation\Response;

class ResponseEventDTO extends AbstractEventDTO
{
    protected string $name = "kernel.response";
    protected array $mountableStdClass = [Response::class];


    public function getPayload(): mixed
    {
        $payload = [
            $this->payload[0][0],
            $this->payload[0][1],
            $this->payload[0][2]->getPayload(),
        ];


        return $payload;
    }
}