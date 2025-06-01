<?php

namespace Tourze\UserTagContracts\Tests;

use PHPUnit\Framework\TestCase;
use Tourze\UserTagContracts\Tests\Mock\MockTag;
use Tourze\UserTagContracts\Tests\Mock\MockTagLoader;
use Tourze\UserTagContracts\Tests\Mock\MockUser;

class TagLoaderInterfaceTest extends TestCase
{
    public function testLoadTagsByUser_returnsCorrectTags(): void
    {
        $userId = 'user1';
        $user = new MockUser($userId);
        
        $tag = new MockTag('test-tag');
        $loader = new MockTagLoader();
        $loader->addUserTags($userId, [$tag]);
        
        $result = $loader->loadTagsByUser($user);
        
        $this->assertIsIterable($result);
        $resultArray = is_array($result) ? $result : iterator_to_array($result);
        $this->assertCount(1, $resultArray);
        $this->assertSame($tag, $resultArray[0]);
        $this->assertSame('test-tag', $resultArray[0]->getName());
    }
    
    public function testLoadTagsByUser_withNoTags(): void
    {
        $userId = 'user-no-tags';
        $user = new MockUser($userId);
        
        $loader = new MockTagLoader();
        
        $result = $loader->loadTagsByUser($user);
        
        $this->assertIsIterable($result);
        $resultArray = is_array($result) ? $result : iterator_to_array($result);
        $this->assertCount(0, $resultArray);
    }
    
    public function testLoadTagsByUser_withMultipleTags(): void
    {
        $userId = 'user-multiple-tags';
        $user = new MockUser($userId);
        
        $tag1 = new MockTag('tag1');
        $tag2 = new MockTag('tag2');
        $tag3 = new MockTag('tag3');
        
        $loader = new MockTagLoader();
        $loader->addUserTags($userId, [$tag1, $tag2, $tag3]);
        
        $result = $loader->loadTagsByUser($user);
        
        $this->assertIsIterable($result);
        $resultArray = is_array($result) ? $result : iterator_to_array($result);
        $this->assertCount(3, $resultArray);
        $this->assertSame($tag1, $resultArray[0]);
        $this->assertSame($tag2, $resultArray[1]);
        $this->assertSame($tag3, $resultArray[2]);
        $this->assertSame('tag1', $resultArray[0]->getName());
        $this->assertSame('tag2', $resultArray[1]->getName());
        $this->assertSame('tag3', $resultArray[2]->getName());
    }
    
    public function testLoadTagsByUser_withNonExistentUser(): void
    {
        $nonExistentUserId = 'non-existent-user';
        $user = new MockUser($nonExistentUserId);
        
        $loader = new MockTagLoader();
        // 故意不添加该用户的标签
        
        $result = $loader->loadTagsByUser($user);
        
        $this->assertIsIterable($result);
        $resultArray = is_array($result) ? $result : iterator_to_array($result);
        $this->assertCount(0, $resultArray);
        $this->assertEmpty($resultArray);
    }
    
    public function testLoadTagsByUser_withLargeNumberOfTags(): void
    {
        $userId = 'user-many-tags';
        $user = new MockUser($userId);
        $loader = new MockTagLoader();
        
        // 创建大量标签
        $tags = [];
        for ($i = 0; $i < 1000; $i++) {
            $tags[] = new MockTag("tag-{$i}");
        }
        
        $loader->addUserTags($userId, $tags);
        $result = $loader->loadTagsByUser($user);
        
        $resultArray = is_array($result) ? $result : iterator_to_array($result);
        $this->assertCount(1000, $resultArray);
        
        // 验证前几个和后几个标签
        $this->assertSame('tag-0', $resultArray[0]->getName());
        $this->assertSame('tag-999', $resultArray[999]->getName());
    }
    
    public function testLoadTagsByUser_withDuplicateTagNames(): void
    {
        $userId = 'user-duplicate-names';
        $user = new MockUser($userId);
        
        // 创建多个同名标签实例
        $tag1 = new MockTag('duplicate-name');
        $tag2 = new MockTag('duplicate-name');
        $tag3 = new MockTag('unique-name');
        
        $loader = new MockTagLoader();
        $loader->addUserTags($userId, [$tag1, $tag2, $tag3]);
        
        $result = $loader->loadTagsByUser($user);
        $resultArray = is_array($result) ? $result : iterator_to_array($result);
        
        $this->assertCount(3, $resultArray);
        $this->assertSame($tag1, $resultArray[0]);
        $this->assertSame($tag2, $resultArray[1]);
        $this->assertSame($tag3, $resultArray[2]);
        
        // 确认虽然名称相同，但是不同的对象实例
        $this->assertNotSame($tag1, $tag2);
        $this->assertSame('duplicate-name', $resultArray[0]->getName());
        $this->assertSame('duplicate-name', $resultArray[1]->getName());
    }
    
    public function testLoadTagsByUser_withSpecialCharacterTags(): void
    {
        $userId = 'user-special-chars';
        $user = new MockUser($userId);
        
        $specialTags = [
            new MockTag('标签中文'),
            new MockTag('🏷️emoji-tag'),
            new MockTag('tag@domain.com'),
            new MockTag('tag with spaces'),
            new MockTag('tag-with-"quotes"'),
            new MockTag("tag\nwith\nnewlines")
        ];
        
        $loader = new MockTagLoader();
        $loader->addUserTags($userId, $specialTags);
        
        $result = $loader->loadTagsByUser($user);
        $resultArray = is_array($result) ? $result : iterator_to_array($result);
        
        $this->assertCount(6, $resultArray);
        
        foreach ($specialTags as $index => $expectedTag) {
            $this->assertSame($expectedTag, $resultArray[$index]);
        }
    }
    
    public function testLoadTagsByUser_withEmptyTagNames(): void
    {
        $userId = 'user-empty-tag-names';
        $user = new MockUser($userId);
        
        $tags = [
            new MockTag(''),
            new MockTag('normal-tag'),
            new MockTag('   '),  // 只有空格
            new MockTag("\t\n\r")  // 只有制表符和换行符
        ];
        
        $loader = new MockTagLoader();
        $loader->addUserTags($userId, $tags);
        
        $result = $loader->loadTagsByUser($user);
        $resultArray = is_array($result) ? $result : iterator_to_array($result);
        
        $this->assertCount(4, $resultArray);
        $this->assertSame('', $resultArray[0]->getName());
        $this->assertSame('normal-tag', $resultArray[1]->getName());
        $this->assertSame('   ', $resultArray[2]->getName());
        $this->assertSame("\t\n\r", $resultArray[3]->getName());
    }
    
    public function testLoadTagsByUser_performanceWithManyUsers(): void
    {
        $loader = new MockTagLoader();
        
        // 为多个用户添加标签
        for ($i = 0; $i < 100; $i++) {
            $userId = "user-{$i}";
            $tags = [
                new MockTag("tag-{$i}-1"),
                new MockTag("tag-{$i}-2")
            ];
            $loader->addUserTags($userId, $tags);
        }
        
        // 测试任意用户的标签加载
        $testUser = new MockUser('user-50');
        $result = $loader->loadTagsByUser($testUser);
        $resultArray = is_array($result) ? $result : iterator_to_array($result);
        
        $this->assertCount(2, $resultArray);
        $this->assertSame('tag-50-1', $resultArray[0]->getName());
        $this->assertSame('tag-50-2', $resultArray[1]->getName());
    }
    
    public function testLoadTagsByUser_memoryConsistency(): void
    {
        $userId = 'memory-test-user';
        $user = new MockUser($userId);
        $loader = new MockTagLoader();
        
        $tag = new MockTag('memory-tag');
        $loader->addUserTags($userId, [$tag]);
        
        // 多次获取结果，验证内存中的一致性
        $results = [];
        for ($i = 0; $i < 10; $i++) {
            $result = $loader->loadTagsByUser($user);
            $results[] = is_array($result) ? $result : iterator_to_array($result);
        }
        
        // 所有结果应该包含相同的标签实例
        foreach ($results as $resultArray) {
            $this->assertCount(1, $resultArray);
            $this->assertSame($tag, $resultArray[0]);
        }
    }
    
    public function testLoadTagsByUser_withUserHavingRoles(): void
    {
        $userId = 'user-with-roles';
        $roles = ['ROLE_USER', 'ROLE_ADMIN'];
        $user = new MockUser($userId, $roles);
        
        $tag = new MockTag('admin-tag');
        $loader = new MockTagLoader();
        $loader->addUserTags($userId, [$tag]);
        
        $result = $loader->loadTagsByUser($user);
        $resultArray = is_array($result) ? $result : iterator_to_array($result);
        
        // 标签加载应该基于用户标识符，不受用户角色影响
        $this->assertCount(1, $resultArray);
        $this->assertSame($tag, $resultArray[0]);
        
        // 用户角色应该保持不变
        $this->assertSame($roles, $user->getRoles());
    }
    
    public function testLoadTagsByUser_iteratorBehavior(): void
    {
        $userId = 'iterator-test-user';
        $user = new MockUser($userId);
        
        $tags = [
            new MockTag('tag-1'),
            new MockTag('tag-2'),
            new MockTag('tag-3')
        ];
        
        $loader = new MockTagLoader();
        $loader->addUserTags($userId, $tags);
        
        $result = $loader->loadTagsByUser($user);
        
        // 验证可以直接迭代
        $iteratedTags = [];
        foreach ($result as $tag) {
            $iteratedTags[] = $tag;
        }
        
        $this->assertCount(3, $iteratedTags);
        $this->assertSame($tags[0], $iteratedTags[0]);
        $this->assertSame($tags[1], $iteratedTags[1]);
        $this->assertSame($tags[2], $iteratedTags[2]);
    }
} 