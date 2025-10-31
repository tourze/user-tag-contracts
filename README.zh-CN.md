# User Tag Contracts

[English](README.md) | [中文](README.zh-CN.md)

此包为 Symfony 应用程序中的用户标签系统提供契约（接口）。

## 安装

```bash
composer require tourze/user-tag-contracts
```

## 快速开始

此包提供两个主要接口：

### TagInterface

表示可以与用户关联的标签：

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

为用户提供标签加载功能：

```php
use Tourze\UserTagContracts\TagLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class DatabaseTagLoader implements TagLoaderInterface
{
    public function loadTagsByUser(UserInterface $user): iterable
    {
        // 从数据库加载标签
        return $this->repository->findTagsByUser($user);
    }
}
```

## 功能特性

- 简单且可扩展的标签接口
- 通过 Symfony 依赖注入自动配置服务
- 支持任何实现 `UserInterface` 的用户实现
- 通过可迭代对象支持懒加载

## 使用说明

实现 `TagLoaderInterface` 的服务会自动在 Symfony 容器中标记为 `user-tag.service`，便于发现和使用。

## 环境要求

- PHP 8.1+
- Symfony 6.4+

## 许可证

MIT
