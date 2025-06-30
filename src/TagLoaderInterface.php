<?php

namespace Tourze\UserTagContracts;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * 提供一些最通用的标签服务
 */
#[AutoconfigureTag(name: 'user-tag.service')]
interface TagLoaderInterface
{
    /**
     * 获取指定用户的标签列表
     *
     * @return iterable<TagInterface>
     */
    public function loadTagsByUser(UserInterface $user): iterable;
}
