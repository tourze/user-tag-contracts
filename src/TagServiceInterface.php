<?php

namespace Tourze\UserTagContracts;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * 提供一些最通用的标签服务
 */
interface TagServiceInterface
{
    /**
     * 获取指定用户的标签列表
     */
    public function loadTagsByUser(UserInterface $user): iterable;
}
