<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Tests\Enum;

use PHPUnit\Framework\TestCase;
use Tourze\QUIC\Core\Enum\StreamType;

/**
 * StreamType 枚举单元测试
 * 
 * @covers \Tourze\QUIC\Core\Enum\StreamType
 */
class StreamTypeTest extends TestCase
{
    /**
     * 测试枚举值
     */
    public function testEnumValues(): void
    {
        $this->assertSame(0x00, StreamType::CLIENT_BIDI->value);
        $this->assertSame(0x01, StreamType::SERVER_BIDI->value);
        $this->assertSame(0x02, StreamType::CLIENT_UNI->value);
        $this->assertSame(0x03, StreamType::SERVER_UNI->value);
    }

    /**
     * 测试是否为双向流
     */
    public function testIsBidirectional(): void
    {
        $this->assertTrue(StreamType::CLIENT_BIDI->isBidirectional());
        $this->assertTrue(StreamType::SERVER_BIDI->isBidirectional());
        $this->assertFalse(StreamType::CLIENT_UNI->isBidirectional());
        $this->assertFalse(StreamType::SERVER_UNI->isBidirectional());
    }

    /**
     * 测试是否为单向流
     */
    public function testIsUnidirectional(): void
    {
        $this->assertFalse(StreamType::CLIENT_BIDI->isUnidirectional());
        $this->assertFalse(StreamType::SERVER_BIDI->isUnidirectional());
        $this->assertTrue(StreamType::CLIENT_UNI->isUnidirectional());
        $this->assertTrue(StreamType::SERVER_UNI->isUnidirectional());
    }

    /**
     * 测试是否为客户端发起
     */
    public function testIsClientInitiated(): void
    {
        $this->assertTrue(StreamType::CLIENT_BIDI->isClientInitiated());
        $this->assertFalse(StreamType::SERVER_BIDI->isClientInitiated());
        $this->assertTrue(StreamType::CLIENT_UNI->isClientInitiated());
        $this->assertFalse(StreamType::SERVER_UNI->isClientInitiated());
    }

    /**
     * 测试是否为服务端发起
     */
    public function testIsServerInitiated(): void
    {
        $this->assertFalse(StreamType::CLIENT_BIDI->isServerInitiated());
        $this->assertTrue(StreamType::SERVER_BIDI->isServerInitiated());
        $this->assertFalse(StreamType::CLIENT_UNI->isServerInitiated());
        $this->assertTrue(StreamType::SERVER_UNI->isServerInitiated());
    }

    /**
     * 测试从流ID判断流类型
     */
    public function testFromStreamId(): void
    {
        // 测试不同的流ID模式
        $this->assertSame(StreamType::CLIENT_BIDI, StreamType::fromStreamId(0));
        $this->assertSame(StreamType::SERVER_BIDI, StreamType::fromStreamId(1));
        $this->assertSame(StreamType::CLIENT_UNI, StreamType::fromStreamId(2));
        $this->assertSame(StreamType::SERVER_UNI, StreamType::fromStreamId(3));
        
        // 测试更大的流ID（取模操作）
        $this->assertSame(StreamType::CLIENT_BIDI, StreamType::fromStreamId(4));
        $this->assertSame(StreamType::SERVER_BIDI, StreamType::fromStreamId(5));
        $this->assertSame(StreamType::CLIENT_UNI, StreamType::fromStreamId(6));
        $this->assertSame(StreamType::SERVER_UNI, StreamType::fromStreamId(7));
        
        $this->assertSame(StreamType::CLIENT_BIDI, StreamType::fromStreamId(8));
        $this->assertSame(StreamType::CLIENT_BIDI, StreamType::fromStreamId(100));
        $this->assertSame(StreamType::SERVER_UNI, StreamType::fromStreamId(99));
    }

    /**
     * 测试类型描述
     */
    public function testGetDescription(): void
    {
        $this->assertSame('客户端双向流', StreamType::CLIENT_BIDI->getDescription());
        $this->assertSame('服务端双向流', StreamType::SERVER_BIDI->getDescription());
        $this->assertSame('客户端单向流', StreamType::CLIENT_UNI->getDescription());
        $this->assertSame('服务端单向流', StreamType::SERVER_UNI->getDescription());
    }
} 