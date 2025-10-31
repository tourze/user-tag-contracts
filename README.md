# User Tag Contracts

[English](README.md) | [中文](README.zh-CN.md)

This package provides contracts (interfaces) for user tag systems in Symfony applications.

## Installation

```bash
composer require tourze/user-tag-contracts
```

## Quick Start

This package provides two main interfaces:

### TagInterface

Represents a tag that can be associated with users:

```php
use Tourze\UserTagContracts\TagInterface;

class UserTag implements TagInterface
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
```

### TagLoaderInterface

Provides tag loading functionality for users:

```php
use Tourze\UserTagContracts\TagLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class DatabaseTagLoader implements TagLoaderInterface
{
    public function loadTagsByUser(UserInterface $user): iterable
    {
        // Load tags from database
        return $this->repository->findTagsByUser($user);
    }
}
```

## Features

- Simple and extensible tag interface
- Automatic service configuration with Symfony's dependency injection
- Support for any user implementation that implements `UserInterface`
- Lazy loading support through iterables

## Usage

Services implementing `TagLoaderInterface` are automatically tagged with `user-tag.service` in the Symfony container, making them easy to discover and use.

## Requirements

- PHP 8.1+
- Symfony 6.4+

## License

MIT