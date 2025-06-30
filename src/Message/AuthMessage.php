<?php

namespace Agrume\Limonade\Message;

class AuthMessage
{
    private string $action;
    private string $username;
    private string $password;
    private string $correlationId;

    public function __construct(string $action, string $username, string $password, string $correlationId)
    {
        $this->action = $action;
        $this->username = $username;
        $this->password = $password;
        $this->correlationId = $correlationId;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getCorrelationId(): string
    {
        return $this->correlationId;
    }
}