<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Tests\Unit\Exception;

use PHPUnit\Framework\TestCase;
use Tourze\QUIC\Core\Exception\ConnectionIdException;

class ConnectionIdExceptionTest extends TestCase
{
    public function testInvalidLength(): void
    {
        $exception = ConnectionIdException::invalidLength(25, 4, 18);
        
        $this->assertInstanceOf(ConnectionIdException::class, $exception);
        $this->assertSame('连接ID长度必须在 4-18 字节范围内，实际长度: 25', $exception->getMessage());
    }

    public function testMinLengthOutOfRange(): void
    {
        $exception = ConnectionIdException::minLengthOutOfRange(30);
        
        $this->assertInstanceOf(ConnectionIdException::class, $exception);
        $this->assertSame('最小长度超出范围: 30', $exception->getMessage());
    }

    public function testMaxLengthOutOfRange(): void
    {
        $exception = ConnectionIdException::maxLengthOutOfRange(100);
        
        $this->assertInstanceOf(ConnectionIdException::class, $exception);
        $this->assertSame('最大长度超出范围: 100', $exception->getMessage());
    }

    public function testMinGreaterThanMax(): void
    {
        $exception = ConnectionIdException::minGreaterThanMax(10, 5);
        
        $this->assertInstanceOf(ConnectionIdException::class, $exception);
        $this->assertSame('最小长度不能大于最大长度: 10 > 5', $exception->getMessage());
    }

    public function testInvalidHexString(): void
    {
        $exception = ConnectionIdException::invalidHexString('XYZ');
        
        $this->assertInstanceOf(ConnectionIdException::class, $exception);
        $this->assertSame('无效的十六进制字符串: XYZ', $exception->getMessage());
    }

    public function testHexLengthMustBeEven(): void
    {
        $exception = ConnectionIdException::hexLengthMustBeEven('ABC');
        
        $this->assertInstanceOf(ConnectionIdException::class, $exception);
        $this->assertSame('十六进制字符串长度必须为偶数: ABC', $exception->getMessage());
    }

    public function testCannotParseHexString(): void
    {
        $exception = ConnectionIdException::cannotParseHexString('GHI');
        
        $this->assertInstanceOf(ConnectionIdException::class, $exception);
        $this->assertSame('无法解析十六进制字符串: GHI', $exception->getMessage());
    }

    public function testGeneratedConnectionIdOutOfRange(): void
    {
        $exception = ConnectionIdException::generatedConnectionIdOutOfRange();
        
        $this->assertInstanceOf(ConnectionIdException::class, $exception);
        $this->assertSame('生成的连接ID长度超出范围', $exception->getMessage());
    }

    public function testCountCannotBeNegative(): void
    {
        $exception = ConnectionIdException::countCannotBeNegative(-5);
        
        $this->assertInstanceOf(ConnectionIdException::class, $exception);
        $this->assertSame('生成数量不能为负数: -5', $exception->getMessage());
    }

    public function testSequenceNumberOutOfRange(): void
    {
        $exception = ConnectionIdException::sequenceNumberOutOfRange(300);
        
        $this->assertInstanceOf(ConnectionIdException::class, $exception);
        $this->assertSame('序列号超出有效范围: 300', $exception->getMessage());
    }

    public function testInvalidSecretLength(): void
    {
        $exception = ConnectionIdException::invalidSecretLength(20);
        
        $this->assertInstanceOf(ConnectionIdException::class, $exception);
        $this->assertSame('密钥长度必须为16字节，实际长度: 20', $exception->getMessage());
    }
}