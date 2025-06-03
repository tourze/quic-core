<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Tests\Enum;

use PHPUnit\Framework\TestCase;
use Tourze\QUIC\Core\Enum\QuicError;

/**
 * QuicError 枚举单元测试
 * 
 * @covers \Tourze\QUIC\Core\Enum\QuicError
 */
class QuicErrorTest extends TestCase
{
    /**
     * 测试枚举值
     */
    public function testEnumValues(): void
    {
        $this->assertSame(0x0, QuicError::NO_ERROR->value);
        $this->assertSame(0x1, QuicError::INTERNAL_ERROR->value);
        $this->assertSame(0x2, QuicError::CONNECTION_REFUSED->value);
        $this->assertSame(0x3, QuicError::FLOW_CONTROL_ERROR->value);
        $this->assertSame(0x100, QuicError::CRYPTO_ERROR->value);
    }

    /**
     * 测试连接层错误判断
     */
    public function testIsConnectionError(): void
    {
        $this->assertTrue(QuicError::NO_ERROR->isConnectionError());
        $this->assertTrue(QuicError::INTERNAL_ERROR->isConnectionError());
        $this->assertTrue(QuicError::CONNECTION_REFUSED->isConnectionError());
        $this->assertFalse(QuicError::CRYPTO_ERROR->isConnectionError());
    }

    /**
     * 测试应用层错误判断
     */
    public function testIsApplicationError(): void
    {
        $this->assertTrue(QuicError::APPLICATION_ERROR->isApplicationError());
        $this->assertFalse(QuicError::NO_ERROR->isApplicationError());
        $this->assertFalse(QuicError::CRYPTO_ERROR->isApplicationError());
    }

    /**
     * 测试加密错误判断
     */
    public function testIsCryptoError(): void
    {
        $this->assertTrue(QuicError::CRYPTO_ERROR->isCryptoError());
        $this->assertFalse(QuicError::NO_ERROR->isCryptoError());
        $this->assertFalse(QuicError::APPLICATION_ERROR->isCryptoError());
    }

    /**
     * 测试传输层错误判断
     */
    public function testIsTransportError(): void
    {
        $this->assertTrue(QuicError::NO_ERROR->isTransportError());
        $this->assertTrue(QuicError::INTERNAL_ERROR->isTransportError());
        $this->assertFalse(QuicError::APPLICATION_ERROR->isTransportError());
        $this->assertFalse(QuicError::CRYPTO_ERROR->isTransportError());
    }

    /**
     * 测试致命错误判断
     */
    public function testIsFatal(): void
    {
        $this->assertTrue(QuicError::INTERNAL_ERROR->isFatal());
        $this->assertTrue(QuicError::CONNECTION_REFUSED->isFatal());
        $this->assertTrue(QuicError::PROTOCOL_VIOLATION->isFatal());
        $this->assertFalse(QuicError::NO_ERROR->isFatal());
        $this->assertFalse(QuicError::FLOW_CONTROL_ERROR->isFatal());
    }

    /**
     * 测试错误描述
     */
    public function testGetDescription(): void
    {
        $this->assertSame('无错误', QuicError::NO_ERROR->getDescription());
        $this->assertSame('内部错误', QuicError::INTERNAL_ERROR->getDescription());
        $this->assertSame('连接被拒绝', QuicError::CONNECTION_REFUSED->getDescription());
        $this->assertSame('流量控制错误', QuicError::FLOW_CONTROL_ERROR->getDescription());
        $this->assertSame('应用层错误', QuicError::APPLICATION_ERROR->getDescription());
        $this->assertSame('加密错误', QuicError::CRYPTO_ERROR->getDescription());
    }
} 