<?php

namespace Tourze\UserTagContracts\Tests\Mock;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\UserInterface;

class MockUserTest extends TestCase
{
    public function testConstruct_withValidIdentifier(): void
    {
        $identifier = 'user123';
        $user = new MockUser($identifier);
        
        $this->assertInstanceOf(UserInterface::class, $user);
        $this->assertInstanceOf(MockUser::class, $user);
    }
    
    public function testConstruct_withIdentifierAndRoles(): void
    {
        $identifier = 'admin_user';
        $roles = ['ROLE_USER', 'ROLE_ADMIN'];
        $user = new MockUser($identifier, $roles);
        
        $this->assertInstanceOf(UserInterface::class, $user);
        $this->assertSame($identifier, $user->getUserIdentifier());
        $this->assertSame($roles, $user->getRoles());
    }
    
    public function testGetUserIdentifier_returnsConstructorValue(): void
    {
        $identifier = 'test_user_123';
        $user = new MockUser($identifier);
        
        $this->assertSame($identifier, $user->getUserIdentifier());
    }
    
    public function testGetUserIdentifier_withEmptyString(): void
    {
        $user = new MockUser('');
        
        $this->assertSame('', $user->getUserIdentifier());
    }
    
    public function testGetUserIdentifier_withSpecialCharacters(): void
    {
        $identifier = 'user@example.com';
        $user = new MockUser($identifier);
        
        $this->assertSame($identifier, $user->getUserIdentifier());
    }
    
    public function testGetUserIdentifier_withUnicodeCharacters(): void
    {
        $identifier = '用户123';
        $user = new MockUser($identifier);
        
        $this->assertSame($identifier, $user->getUserIdentifier());
    }
    
    public function testGetUserIdentifier_isImmutable(): void
    {
        $identifier = 'immutable_user';
        $user = new MockUser($identifier);
        
        $id1 = $user->getUserIdentifier();
        $id2 = $user->getUserIdentifier();
        
        $this->assertSame($id1, $id2);
        $this->assertSame($identifier, $id1);
    }
    
    public function testGetRoles_withDefaultRoles(): void
    {
        $user = new MockUser('user1');
        
        $roles = $user->getRoles();
        $this->assertEmpty($roles);
    }
    
    public function testGetRoles_withSingleRole(): void
    {
        $roles = ['ROLE_USER'];
        $user = new MockUser('user1', $roles);
        
        $this->assertSame($roles, $user->getRoles());
    }
    
    public function testGetRoles_withMultipleRoles(): void
    {
        $roles = ['ROLE_USER', 'ROLE_ADMIN', 'ROLE_MANAGER'];
        $user = new MockUser('admin_user', $roles);
        
        $this->assertSame($roles, $user->getRoles());
        $this->assertCount(3, $user->getRoles());
    }
    
    public function testGetRoles_withEmptyRoles(): void
    {
        $user = new MockUser('user1', []);
        
        $roles = $user->getRoles();
        $this->assertEmpty($roles);
    }
    
    public function testGetRoles_isImmutable(): void
    {
        $originalRoles = ['ROLE_USER', 'ROLE_ADMIN'];
        $user = new MockUser('user1', $originalRoles);
        
        $roles1 = $user->getRoles();
        $roles2 = $user->getRoles();
        
        $this->assertSame($roles1, $roles2);
        $this->assertSame($originalRoles, $roles1);
    }
    
    public function testEraseCredentials_doesNothing(): void
    {
        $identifier = 'test_user';
        $roles = ['ROLE_USER'];
        $user = new MockUser($identifier, $roles);
        
        // 调用前的状态
        $identifierBefore = $user->getUserIdentifier();
        $rolesBefore = $user->getRoles();
        
        // 调用 eraseCredentials
        $user->eraseCredentials();
        
        // 调用后的状态应该保持不变
        $this->assertSame($identifierBefore, $user->getUserIdentifier());
        $this->assertSame($rolesBefore, $user->getRoles());
    }
    
    public function testEraseCredentials_canBeCalledMultipleTimes(): void
    {
        $user = new MockUser('user1', ['ROLE_USER']);
        
        $user->eraseCredentials();
        $user->eraseCredentials();
        $user->eraseCredentials();
        
        // 多次调用应该不会产生任何副作用
        $this->assertSame('user1', $user->getUserIdentifier());
        $this->assertSame(['ROLE_USER'], $user->getRoles());
    }
    
    public function testGetRoles_withDuplicateRoles(): void
    {
        $rolesWithDuplicates = ['ROLE_USER', 'ROLE_ADMIN', 'ROLE_USER'];
        $user = new MockUser('user1', $rolesWithDuplicates);
        
        // Mock 实现应该保持原样，不去重
        $this->assertSame($rolesWithDuplicates, $user->getRoles());
        $this->assertCount(3, $user->getRoles());
    }
    
    public function testGetRoles_withCustomRoleNames(): void
    {
        $customRoles = ['CUSTOM_ROLE_1', 'SPECIAL_ACCESS', 'FEATURE_X'];
        $user = new MockUser('custom_user', $customRoles);
        
        $this->assertSame($customRoles, $user->getRoles());
    }
} 