<?php

namespace Tourze\UserTagContracts\Tests\Mock;

use Tourze\UserTagContracts\TagInterface;

class MockTag implements TagInterface
{
    public function __construct(
        private readonly string $name
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
