<?php

namespace Tourze\UserTagContracts\Tests\Mock;

use Symfony\Component\Security\Core\User\UserInterface;
use Tourze\UserTagContracts\TagInterface;
use Tourze\UserTagContracts\TagLoaderInterface;

class MockTagLoader implements TagLoaderInterface
{
    /**
     * @var array<string, TagInterface[]>
     */
    private array $userTags = [];
    
    /**
     * 添加用户标签至测试集合
     */
    public function addUserTags(string $userIdentifier, array $tags): void
    {
        $this->userTags[$userIdentifier] = $tags;
    }
    
    public function loadTagsByUser(UserInterface $user): iterable
    {
        return $this->userTags[$user->getUserIdentifier()] ?? [];
    }
} 