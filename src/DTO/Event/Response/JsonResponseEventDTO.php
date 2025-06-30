<?php

namespace Agrume\Limonade\DTO\Event\Response;

use Agrume\Limonade\DTO\Event\AbstractEventDTO;
use Symfony\Component\HttpFoundation\JsonResponse;

class JsonResponseEventDTO extends AbstractEventDTO
{
    protected string $name = "kernel.response";
    protected array $mountableStdClass = [JsonResponse::class];
    public function getPayload(): mixed
    {
        $payload = [
            $this->payload[0][0],
            $this->payload[0][1],
            $this->payload[0][2]->getPayload(),
            $this->payload[0][3],
        ];


        return $payload;
    }
    public function autoMount(): object
    {
        return $this->mount(...$this->getPayload());
    }
}