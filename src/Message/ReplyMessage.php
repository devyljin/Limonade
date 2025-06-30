<?php

namespace Agrume\Limonade\Message;

class ReplyMessage
{
    private string $correlationId;
    private array $response;

    public function __construct(string $correlationId, array $response)
    {
        $this->correlationId = $correlationId;
        $this->response = $response;
    }

    public function getCorrelationId(): string
    {
        return $this->correlationId;
    }

    public function getResponse(): array
    {
        return $this->response;
    }
}