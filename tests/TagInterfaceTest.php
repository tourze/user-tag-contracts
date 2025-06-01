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
    
    public function testGetName_withWhitespaceCharacters(): void
    {
        $whitespaceTagName = "  \t\n\r  ";
        $tag = new MockTag($whitespaceTagName);
        
        $this->assertSame($whitespaceTagName, $tag->getName());
    }
    
    public function testGetName_withVeryLongString(): void
    {
        $longTagName = str_repeat('长标签名称', 100);
        $tag = new MockTag($longTagName);
        
        $this->assertSame($longTagName, $tag->getName());
        $this->assertGreaterThan(100, strlen($tag->getName()));
    }
    
    public function testGetName_withNumericString(): void
    {
        $numericTagName = '123456789';
        $tag = new MockTag($numericTagName);
        
        $this->assertSame($numericTagName, $tag->getName());
        $this->assertIsString($tag->getName());
    }
    
    public function testGetName_withMixedContent(): void
    {
        $mixedTagName = 'Tag123_标签-test@domain.com';
        $tag = new MockTag($mixedTagName);
        
        $this->assertSame($mixedTagName, $tag->getName());
    }
    
    public function testGetName_withZeroWidthCharacters(): void
    {
        $zeroWidthTagName = "tag\u{200B}name";  // 零宽度空格
        $tag = new MockTag($zeroWidthTagName);
        
        $this->assertSame($zeroWidthTagName, $tag->getName());
    }
    
    public function testGetName_withEmojiCharacters(): void
    {
        $emojiTagName = '🏷️📝✅❌';
        $tag = new MockTag($emojiTagName);
        
        $this->assertSame($emojiTagName, $tag->getName());
    }
    
    public function testGetName_withUrlLikeString(): void
    {
        $urlTagName = 'https://example.com/tag?id=123&type=special';
        $tag = new MockTag($urlTagName);
        
        $this->assertSame($urlTagName, $tag->getName());
    }
    
    public function testGetName_withJsonLikeString(): void
    {
        $jsonTagName = '{"type":"tag","value":"test"}';
        $tag = new MockTag($jsonTagName);
        
        $this->assertSame($jsonTagName, $tag->getName());
    }
    
    public function testGetName_withSqlLikeString(): void
    {
        $sqlTagName = "SELECT * FROM tags WHERE name = 'test'";
        $tag = new MockTag($sqlTagName);
        
        $this->assertSame($sqlTagName, $tag->getName());
    }
    
    public function testGetName_consistencyAcrossMultipleCalls(): void
    {
        $tagName = 'consistent-tag';
        $tag = new MockTag($tagName);
        
        $name1 = $tag->getName();
        $name2 = $tag->getName();
        $name3 = $tag->getName();
        
        $this->assertSame($name1, $name2);
        $this->assertSame($name2, $name3);
        $this->assertSame($tagName, $name1);
    }
    
    public function testGetName_withControlCharacters(): void
    {
        $controlCharTagName = "tag\x01\x02\x03name";
        $tag = new MockTag($controlCharTagName);
        
        $this->assertSame($controlCharTagName, $tag->getName());
    }
    
    public function testGetName_withQuotesAndEscapes(): void
    {
        $quotedTagName = "tag'with\"quotes\\and\\escapes";
        $tag = new MockTag($quotedTagName);
        
        $this->assertSame($quotedTagName, $tag->getName());
    }
} 