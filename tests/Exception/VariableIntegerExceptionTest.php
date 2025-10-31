<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Tests\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitBase\AbstractExceptionTestCase;
use Tourze\QUIC\Core\Exception\VariableIntegerException;

/**
 * @internal
 */
#[CoversClass(VariableIntegerException::class)]
final class VariableIntegerExceptionTest extends AbstractExceptionTestCase
{
    public function testCannotBeNegative(): void
    {
        $exception = VariableIntegerException::cannotBeNegative(-5);

        $this->assertSame('变长整数不能为负数: -5', $exception->getMessage());
    }

    public function testExceedsMaxValue(): void
    {
        $exception = VariableIntegerException::exceedsMaxValue(999999999);

        $this->assertSame('变长整数超出最大值: 999999999', $exception->getMessage());
    }

    public function testOffsetCannotBeNegative(): void
    {
        $exception = VariableIntegerException::offsetCannotBeNegative(-10);

        $this->assertSame('偏移量不能为负数: -10', $exception->getMessage());
    }

    public function testOffsetOutOfRange(): void
    {
        $exception = VariableIntegerException::offsetOutOfRange(15, 10);

        $this->assertSame('偏移量超出数据范围: 15 >= 10', $exception->getMessage());
    }

    public function testInsufficientData(): void
    {
        $exception = VariableIntegerException::insufficientData('需要4字节，但只有2字节');

        $this->assertSame('需要4字节，但只有2字节', $exception->getMessage());
    }

    public function testInvalidEncodingType(): void
    {
        $exception = VariableIntegerException::invalidEncodingType(7);

        $this->assertSame('无效的变长整数编码类型: 7', $exception->getMessage());
    }
}
