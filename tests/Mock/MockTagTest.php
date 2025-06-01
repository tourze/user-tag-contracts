<?php

namespace Tourze\UserTagContracts\Tests\Mock;

use PHPUnit\Framework\TestCase;
use Tourze\UserTagContracts\TagInterface;

class MockTagTest extends TestCase
{
    public function testConstruct_withValidName(): void
    {
        $name = 'test-tag';
        $tag = new MockTag($name);
        
        $this->assertInstanceOf(TagInterface::class, $tag);
        $this->assertInstanceOf(MockTag::class, $tag);
    }
    
    public function testGetName_returnsConstructorValue(): void
    {
        $name = 'my-tag';
        $tag = new MockTag($name);
        
        $this->assertSame($name, $tag->getName());
    }
    
    public function testGetName_withEmptyString(): void
    {
        $tag = new MockTag('');
        
        $this->assertSame('', $tag->getName());
    }
    
    public function testGetName_withWhitespaceOnly(): void
    {
        $whitespaceTag = '   ';
        $tag = new MockTag($whitespaceTag);
        
        $this->assertSame($whitespaceTag, $tag->getName());
    }
    
    public function testGetName_withSpecialCharacters(): void
    {
        $specialName = '!@#$%^&*()_+-=[]{}|;\':",./<>?';
        $tag = new MockTag($specialName);
        
        $this->assertSame($specialName, $tag->getName());
    }
    
    public function testGetName_withUnicodeCharacters(): void
    {
        $unicodeName = '测试标签🏷️';
        $tag = new MockTag($unicodeName);
        
        $this->assertSame($unicodeName, $tag->getName());
    }
    
    public function testGetName_withVeryLongString(): void
    {
        $longName = str_repeat('a', 1000);
        $tag = new MockTag($longName);
        
        $this->assertSame($longName, $tag->getName());
        $this->assertSame(1000, strlen($tag->getName()));
    }
    
    public function testGetName_withNumericString(): void
    {
        $numericName = '12345';
        $tag = new MockTag($numericName);
        
        $this->assertSame($numericName, $tag->getName());
        $this->assertIsString($tag->getName());
    }
    
    public function testGetName_withNewlineCharacters(): void
    {
        $nameWithNewlines = "line1\nline2\r\nline3";
        $tag = new MockTag($nameWithNewlines);
        
        $this->assertSame($nameWithNewlines, $tag->getName());
    }
    
    public function testGetName_isImmutable(): void
    {
        $originalName = 'original-name';
        $tag = new MockTag($originalName);
        
        // 多次调用应该返回相同的值
        $name1 = $tag->getName();
        $name2 = $tag->getName();
        
        $this->assertSame($name1, $name2);
        $this->assertSame($originalName, $name1);
        $this->assertSame($originalName, $name2);
    }
    
    public function testGetName_withTabCharacters(): void
    {
        $nameWithTabs = "tag\twith\ttabs";
        $tag = new MockTag($nameWithTabs);
        
        $this->assertSame($nameWithTabs, $tag->getName());
    }
} 