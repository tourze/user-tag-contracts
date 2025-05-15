<?php

namespace Tourze\UserTagContracts\Tests\Mock;

use Symfony\Component\Security\Core\User\UserInterface;

class MockUser implements UserInterface
{
    public function __construct(
        private readonly string $identifier,
        private readonly array $roles = []
    ) {
    }
    
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->identifier;
    }
} 