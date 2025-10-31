<?php

declare(strict_types=1);

namespace Tourze\UserTagContracts\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\UserTagContracts\TagInterface;

/**
 * @internal
 */
#[CoversClass(TagInterface::class)]
final class TagInterfaceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // TagInterfaceTest 只测试接口定义，不需要设置
    }

    public function testInterfaceDefinesRequiredMethods(): void
    {
        $reflection = new \ReflectionClass(TagInterface::class);

        // 验证接口定义了所有必需的方法
        self::assertTrue($reflection->hasMethod('getName'));
    }

    public function testInterfaceMethodSignatures(): void
    {
        $reflection = new \ReflectionClass(TagInterface::class);

        // 验证 getName 方法签名
        $method = $reflection->getMethod('getName');
        $returnType = $method->getReturnType();
        $this->assertNotNull($returnType, '返回类型不应为null');
        self::assertSame('string', $returnType->__toString());
        self::assertCount(0, $method->getParameters());
    }

    public function testInterfaceIsInterface(): void
    {
        $reflection = new \ReflectionClass(TagInterface::class);
        self::assertTrue($reflection->isInterface());
    }

    public function testInterfaceHasCorrectNamespace(): void
    {
        $reflection = new \ReflectionClass(TagInterface::class);
        self::assertSame('Tourze\UserTagContracts', $reflection->getNamespaceName());
    }
}
