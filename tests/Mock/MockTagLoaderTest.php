<?php

namespace Tourze\UserTagContracts\Tests\Mock;

use PHPUnit\Framework\TestCase;
use Tourze\UserTagContracts\TagLoaderInterface;

class MockTagLoaderTest extends TestCase
{
    public function testConstruct_implementsInterface(): void
    {
        $loader = new MockTagLoader();
        
        $this->assertInstanceOf(TagLoaderInterface::class, $loader);
        $this->assertInstanceOf(MockTagLoader::class, $loader);
    }
    
    public function testAddUserTags_withSingleTag(): void
    {
        $userIdentifier = 'user1';
        $tag = new MockTag('tag1');
        $loader = new MockTagLoader();
        
        $loader->addUserTags($userIdentifier, [$tag]);
        
        $user = new MockUser($userIdentifier);
        $result = $loader->loadTagsByUser($user);
        
        $this->assertIsIterable($result);
        $resultArray = is_array($result) ? $result : iterator_to_array($result);
        $this->assertCount(1, $resultArray);
        $this->assertSame($tag, $resultArray[0]);
    }
    
    public function testAddUserTags_withMultipleTags(): void
    {
        $userIdentifier = 'user2';
        $tag1 = new MockTag('tag1');
        $tag2 = new MockTag('tag2');
        $tag3 = new MockTag('tag3');
        $tags = [$tag1, $tag2, $tag3];
        
        $loader = new MockTagLoader();
        $loader->addUserTags($userIdentifier, $tags);
        
        $user = new MockUser($userIdentifier);
        $result = $loader->loadTagsByUser($user);
        
        $resultArray = is_array($result) ? $result : iterator_to_array($result);
        $this->assertCount(3, $resultArray);
        $this->assertSame($tag1, $resultArray[0]);
        $this->assertSame($tag2, $resultArray[1]);
        $this->assertSame($tag3, $resultArray[2]);
    }
    
    public function testAddUserTags_withEmptyArray(): void
    {
        $userIdentifier = 'user_empty';
        $loader = new MockTagLoader();
        
        $loader->addUserTags($userIdentifier, []);
        
        $user = new MockUser($userIdentifier);
        $result = $loader->loadTagsByUser($user);
        
        $resultArray = is_array($result) ? $result : iterator_to_array($result);
        $this->assertCount(0, $resultArray);
        $this->assertEmpty($resultArray);
    }
    
    public function testAddUserTags_overwritesPreviousTags(): void
    {
        $userIdentifier = 'user_overwrite';
        $loader = new MockTagLoader();
        
        // 先添加一组标签
        $originalTag = new MockTag('original');
        $loader->addUserTags($userIdentifier, [$originalTag]);
        
        // 再添加新的标签组
        $newTag1 = new MockTag('new1');
        $newTag2 = new MockTag('new2');
        $loader->addUserTags($userIdentifier, [$newTag1, $newTag2]);
        
        $user = new MockUser($userIdentifier);
        $result = $loader->loadTagsByUser($user);
        
        $resultArray = is_array($result) ? $result : iterator_to_array($result);
        $this->assertCount(2, $resultArray);
        $this->assertSame($newTag1, $resultArray[0]);
        $this->assertSame($newTag2, $resultArray[1]);
        
        // 确认原始标签不存在
        $this->assertNotContains($originalTag, $resultArray);
    }
    
    public function testAddUserTags_withMultipleUsers(): void
    {
        $loader = new MockTagLoader();
        
        // 为不同用户添加不同标签
        $user1Id = 'user1';
        $user2Id = 'user2';
        
        $user1Tag = new MockTag('user1-tag');
        $user2Tag1 = new MockTag('user2-tag1');
        $user2Tag2 = new MockTag('user2-tag2');
        
        $loader->addUserTags($user1Id, [$user1Tag]);
        $loader->addUserTags($user2Id, [$user2Tag1, $user2Tag2]);
        
        // 验证用户1的标签
        $user1 = new MockUser($user1Id);
        $user1Result = $loader->loadTagsByUser($user1);
        $user1ResultArray = is_array($user1Result) ? $user1Result : iterator_to_array($user1Result);
        
        $this->assertCount(1, $user1ResultArray);
        $this->assertSame($user1Tag, $user1ResultArray[0]);
        
        // 验证用户2的标签
        $user2 = new MockUser($user2Id);
        $user2Result = $loader->loadTagsByUser($user2);
        $user2ResultArray = is_array($user2Result) ? $user2Result : iterator_to_array($user2Result);
        
        $this->assertCount(2, $user2ResultArray);
        $this->assertSame($user2Tag1, $user2ResultArray[0]);
        $this->assertSame($user2Tag2, $user2ResultArray[1]);
    }
    
    public function testLoadTagsByUser_withNonExistentUser(): void
    {
        $loader = new MockTagLoader();
        $nonExistentUser = new MockUser('non-existent');
        
        $result = $loader->loadTagsByUser($nonExistentUser);
        
        $this->assertIsIterable($result);
        $resultArray = is_array($result) ? $result : iterator_to_array($result);
        $this->assertCount(0, $resultArray);
        $this->assertEmpty($resultArray);
    }
    
    public function testLoadTagsByUser_afterClearingTags(): void
    {
        $userIdentifier = 'user_clear';
        $loader = new MockTagLoader();
        
        // 先添加标签
        $tag = new MockTag('to-be-cleared');
        $loader->addUserTags($userIdentifier, [$tag]);
        
        // 清空标签
        $loader->addUserTags($userIdentifier, []);
        
        $user = new MockUser($userIdentifier);
        $result = $loader->loadTagsByUser($user);
        
        $resultArray = is_array($result) ? $result : iterator_to_array($result);
        $this->assertCount(0, $resultArray);
        $this->assertEmpty($resultArray);
    }
    
    public function testLoadTagsByUser_withSameTagsForDifferentUsers(): void
    {
        $loader = new MockTagLoader();
        $sharedTag = new MockTag('shared-tag');
        
        $user1Id = 'user1';
        $user2Id = 'user2';
        
        // 给两个用户添加相同的标签实例
        $loader->addUserTags($user1Id, [$sharedTag]);
        $loader->addUserTags($user2Id, [$sharedTag]);
        
        $user1 = new MockUser($user1Id);
        $user2 = new MockUser($user2Id);
        
        $user1Result = $loader->loadTagsByUser($user1);
        $user2Result = $loader->loadTagsByUser($user2);
        
        $user1Array = is_array($user1Result) ? $user1Result : iterator_to_array($user1Result);
        $user2Array = is_array($user2Result) ? $user2Result : iterator_to_array($user2Result);
        
        $this->assertCount(1, $user1Array);
        $this->assertCount(1, $user2Array);
        $this->assertSame($sharedTag, $user1Array[0]);
        $this->assertSame($sharedTag, $user2Array[0]);
        $this->assertSame($user1Array[0], $user2Array[0]);
    }
    
    public function testLoadTagsByUser_isConsistent(): void
    {
        $userIdentifier = 'consistent_user';
        $tag = new MockTag('consistent-tag');
        $loader = new MockTagLoader();
        
        $loader->addUserTags($userIdentifier, [$tag]);
        
        $user = new MockUser($userIdentifier);
        
        // 多次调用应该返回一致的结果
        $result1 = $loader->loadTagsByUser($user);
        $result2 = $loader->loadTagsByUser($user);
        $result3 = $loader->loadTagsByUser($user);
        
        $array1 = is_array($result1) ? $result1 : iterator_to_array($result1);
        $array2 = is_array($result2) ? $result2 : iterator_to_array($result2);
        $array3 = is_array($result3) ? $result3 : iterator_to_array($result3);
        
        $this->assertEquals($array1, $array2);
        $this->assertEquals($array2, $array3);
        $this->assertSame($tag, $array1[0]);
        $this->assertSame($tag, $array2[0]);
        $this->assertSame($tag, $array3[0]);
    }
    
    public function testLoadTagsByUser_withSpecialUserIdentifiers(): void
    {
        $loader = new MockTagLoader();
        
        // 测试特殊字符用户标识符
        $specialIds = [
            'user@example.com',
            'user-with-dashes',
            'user_with_underscores',
            'user.with.dots',
            '123456',
            'user 123',
            '用户中文名'
        ];
        
        foreach ($specialIds as $index => $userId) {
            $tag = new MockTag("tag-for-{$index}");
            $loader->addUserTags($userId, [$tag]);
            
            $user = new MockUser($userId);
            $result = $loader->loadTagsByUser($user);
            $resultArray = is_array($result) ? $result : iterator_to_array($result);
            
            $this->assertCount(1, $resultArray);
            $this->assertSame($tag, $resultArray[0]);
        }
    }
} 