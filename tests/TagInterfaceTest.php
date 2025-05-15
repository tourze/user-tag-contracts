<?php

namespace Tourze\UserTagContracts\Tests;

use PHPUnit\Framework\TestCase;
use Tourze\UserTagContracts\Tests\Mock\MockTag;

class TagInterfaceTest extends TestCase
{
    public function testGetName_returnsCorrectName(): void
    {
        $tagName = 'test-tag';
        $tag = new MockTag($tagName);
        
        $this->assertSame($tagName, $tag->getName());
    }
    
    public function testGetName_withEmptyName(): void
    {
        $tag = new MockTag('');
        
        $this->assertSame('', $tag->getName());
    }
    
    public function testGetName_withSpecialCharacters(): void
    {
        $specialTagName = '特殊标签!@#$%^&*()';
        $tag = new MockTag($specialTagName);
        
        $this->assertSame($specialTagName, $tag->getName());
    }
} 