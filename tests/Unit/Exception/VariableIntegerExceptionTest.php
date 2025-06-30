<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Tests\Unit\Exception;

use PHPUnit\Framework\TestCase;
use Tourze\QUIC\Core\Exception\VariableIntegerException;

class VariableIntegerExceptionTest extends TestCase
{
    public function testCannotBeNegative(): void
    {
        $exception = VariableIntegerException::cannotBeNegative(-5);
        
        $this->assertInstanceOf(VariableIntegerException::class, $exception);
        $this->assertSame('变长整数不能为负数: -5', $exception->getMessage());
    }

    public function testExceedsMaxValue(): void
    {
        $exception = VariableIntegerException::exceedsMaxValue(999999999);
        
        $this->assertInstanceOf(VariableIntegerException::class, $exception);
        $this->assertSame('变长整数超出最大值: 999999999', $exception->getMessage());
    }

    public function testOffsetCannotBeNegative(): void
    {
        $exception = VariableIntegerException::offsetCannotBeNegative(-10);
        
        $this->assertInstanceOf(VariableIntegerException::class, $exception);
        $this->assertSame('偏移量不能为负数: -10', $exception->getMessage());
    }

    public function testOffsetOutOfRange(): void
    {
        $exception = VariableIntegerException::offsetOutOfRange(15, 10);
        
        $this->assertInstanceOf(VariableIntegerException::class, $exception);
        $this->assertSame('偏移量超出数据范围: 15 >= 10', $exception->getMessage());
    }

    public function testInsufficientData(): void
    {
        $exception = VariableIntegerException::insufficientData('需要4字节，但只有2字节');
        
        $this->assertInstanceOf(VariableIntegerException::class, $exception);
        $this->assertSame('需要4字节，但只有2字节', $exception->getMessage());
    }

    public function testInvalidEncodingType(): void
    {
        $exception = VariableIntegerException::invalidEncodingType(7);
        
        $this->assertInstanceOf(VariableIntegerException::class, $exception);
        $this->assertSame('无效的变长整数编码类型: 7', $exception->getMessage());
    }
}