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
} 