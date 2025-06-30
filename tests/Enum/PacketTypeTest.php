<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Tests\Enum;

use PHPUnit\Framework\TestCase;
use Tourze\QUIC\Core\Enum\PacketType;

/**
 * PacketType 枚举单元测试
 *
 * @covers \Tourze\QUIC\Core\Enum\PacketType
 */
class PacketTypeTest extends TestCase
{
    /**
     * 测试枚举值
     */
    public function testEnumValues(): void
    {
        $this->assertSame(0x00, PacketType::INITIAL->value);
        $this->assertSame(0x01, PacketType::ZERO_RTT->value);
        $this->assertSame(0x02, PacketType::HANDSHAKE->value);
        $this->assertSame(0x03, PacketType::RETRY->value);
        $this->assertSame(0xFF, PacketType::VERSION_NEGOTIATION->value);
    }

    /**
     * 测试是否为长包头
     */
    public function testIsLongHeader(): void
    {
        $this->assertTrue(PacketType::INITIAL->isLongHeader());
        $this->assertTrue(PacketType::ZERO_RTT->isLongHeader());
        $this->assertTrue(PacketType::HANDSHAKE->isLongHeader());
        $this->assertTrue(PacketType::RETRY->isLongHeader());
        $this->assertTrue(PacketType::VERSION_NEGOTIATION->isLongHeader());
    }

    /**
     * 测试是否为加密包
     */
    public function testIsEncrypted(): void
    {
        $this->assertTrue(PacketType::INITIAL->isEncrypted());
        $this->assertTrue(PacketType::ZERO_RTT->isEncrypted());
        $this->assertTrue(PacketType::HANDSHAKE->isEncrypted());
        $this->assertFalse(PacketType::RETRY->isEncrypted());
        $this->assertFalse(PacketType::VERSION_NEGOTIATION->isEncrypted());
    }

    /**
     * 测试是否为握手包
     */
    public function testIsHandshakePacket(): void
    {
        $this->assertTrue(PacketType::INITIAL->isHandshakePacket());
        $this->assertFalse(PacketType::ZERO_RTT->isHandshakePacket());
        $this->assertTrue(PacketType::HANDSHAKE->isHandshakePacket());
        $this->assertFalse(PacketType::RETRY->isHandshakePacket());
        $this->assertFalse(PacketType::VERSION_NEGOTIATION->isHandshakePacket());
    }

    /**
     * 测试是否包含连接ID
     */
    public function testHasConnectionId(): void
    {
        $this->assertTrue(PacketType::INITIAL->hasConnectionId());
        $this->assertTrue(PacketType::ZERO_RTT->hasConnectionId());
        $this->assertTrue(PacketType::HANDSHAKE->hasConnectionId());
        $this->assertTrue(PacketType::RETRY->hasConnectionId());
        $this->assertTrue(PacketType::VERSION_NEGOTIATION->hasConnectionId());
    }

    /**
     * 测试是否可以包含ACK帧
     */
    public function testCanContainAck(): void
    {
        $this->assertTrue(PacketType::INITIAL->canContainAck());
        $this->assertFalse(PacketType::ZERO_RTT->canContainAck());
        $this->assertTrue(PacketType::HANDSHAKE->canContainAck());
        $this->assertFalse(PacketType::RETRY->canContainAck());
        $this->assertFalse(PacketType::VERSION_NEGOTIATION->canContainAck());
    }

    /**
     * 测试是否可以包含CRYPTO帧
     */
    public function testCanContainCrypto(): void
    {
        $this->assertTrue(PacketType::INITIAL->canContainCrypto());
        $this->assertFalse(PacketType::ZERO_RTT->canContainCrypto());
        $this->assertTrue(PacketType::HANDSHAKE->canContainCrypto());
        $this->assertFalse(PacketType::RETRY->canContainCrypto());
        $this->assertFalse(PacketType::VERSION_NEGOTIATION->canContainCrypto());
    }

    /**
     * 测试获取包类型名称
     */
    public function testGetName(): void
    {
        $this->assertSame('Initial', PacketType::INITIAL->getName());
        $this->assertSame('Zero RTT', PacketType::ZERO_RTT->getName());
        $this->assertSame('Handshake', PacketType::HANDSHAKE->getName());
        $this->assertSame('Retry', PacketType::RETRY->getName());
        $this->assertSame('Version Negotiation', PacketType::VERSION_NEGOTIATION->getName());
    }

    /**
     * 测试获取包类型描述
     */
    public function testGetDescription(): void
    {
        $this->assertSame('初始包', PacketType::INITIAL->getDescription());
        $this->assertSame('零RTT包', PacketType::ZERO_RTT->getDescription());
        $this->assertSame('握手包', PacketType::HANDSHAKE->getDescription());
        $this->assertSame('重试包', PacketType::RETRY->getDescription());
        $this->assertSame('版本协商包', PacketType::VERSION_NEGOTIATION->getDescription());
    }

    /**
     * 测试获取包头类型编码值
     */
    public function testGetHeaderType(): void
    {
        $this->assertSame(0x00, PacketType::INITIAL->getHeaderType());
        $this->assertSame(0x01, PacketType::ZERO_RTT->getHeaderType());
        $this->assertSame(0x02, PacketType::HANDSHAKE->getHeaderType());
        $this->assertSame(0x03, PacketType::RETRY->getHeaderType());
        $this->assertSame(0x00, PacketType::VERSION_NEGOTIATION->getHeaderType()); // 特殊处理
    }
} 