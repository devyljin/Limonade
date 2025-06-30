<?php

namespace Agrume\Limonade\Security;

use Symfony\Component\Security\Core\User\UserInterface;

class HypotheticUser implements UserInterface
{
    public function __construct(
        private string $id,
        private string $login,
        private ?string $clientId = "",
        private ?int $trialDays = null,
        private array $roles = [],
    ) {}

    public function getId(): string
    {
        return $this->id;
    }
    public function getUserIdentifier(): string
    {
        return $this->login;
    }
    /**
     * @inheritDoc
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getPassword(): ?string
    {
        return null;
    }

    public function getSalt(): ?string
    {
        return null;
    }


    //Custom Property passport

    public function getTrialDays(): ?int
    {
        return $this->trialDays;
    }
    public function setTrialDays(int $trialDays): \Agrume\Limonade\Security\HypotheticUser
    {
        $this->trialDays = $trialDays;
        return $this;
    }

    /**
     * @return string
     */
    public function getClientId(): ?string
    {
        return $this->clientId;
    }
    public function setClientId(?string $clientId): \Agrume\Limonade\Security\HypotheticUser
    {
        $this->clientId = $clientId;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials(): void {}
}