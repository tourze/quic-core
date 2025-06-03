<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Tests\Enum;

use PHPUnit\Framework\TestCase;
use Tourze\QUIC\Core\Enum\FrameType;

/**
 * FrameType 枚举单元测试
 * 
 * @covers \Tourze\QUIC\Core\Enum\FrameType
 */
class FrameTypeTest extends TestCase
{
    /**
     * 测试流帧判断
     */
    public function testIsStreamFrame(): void
    {
        $this->assertFalse(FrameType::PADDING->isStreamFrame());
        $this->assertFalse(FrameType::PING->isStreamFrame());
        $this->assertFalse(FrameType::ACK->isStreamFrame());
        
        // 流帧（0x08-0x0F）
        $this->assertTrue(FrameType::STREAM->isStreamFrame());
        $this->assertTrue(FrameType::STREAM_FIN->isStreamFrame());
        $this->assertTrue(FrameType::STREAM_LEN->isStreamFrame());
        $this->assertTrue(FrameType::STREAM_LEN_FIN->isStreamFrame());
        $this->assertTrue(FrameType::STREAM_OFF->isStreamFrame());
        $this->assertTrue(FrameType::STREAM_OFF_FIN->isStreamFrame());
        $this->assertTrue(FrameType::STREAM_OFF_LEN->isStreamFrame());
        $this->assertTrue(FrameType::STREAM_OFF_LEN_FIN->isStreamFrame());
        
        $this->assertFalse(FrameType::MAX_DATA->isStreamFrame());
    }

    /**
     * 测试流量控制判断
     */
    public function testIsFlowControlled(): void
    {
        $this->assertFalse(FrameType::PADDING->isFlowControlled());
        $this->assertFalse(FrameType::PING->isFlowControlled());
        $this->assertFalse(FrameType::ACK->isFlowControlled());
        
        // 流帧是流量控制的
        $this->assertTrue(FrameType::STREAM->isFlowControlled());
        $this->assertTrue(FrameType::STREAM_FIN->isFlowControlled());
        $this->assertTrue(FrameType::STREAM_LEN->isFlowControlled());
        $this->assertTrue(FrameType::STREAM_OFF_LEN_FIN->isFlowControlled());
        
        // CRYPTO帧也是流量控制的
        $this->assertTrue(FrameType::CRYPTO->isFlowControlled());
        
        $this->assertFalse(FrameType::MAX_DATA->isFlowControlled());
    }

    /**
     * 测试是否需要可靠传输
     */
    public function testNeedsReliableDelivery(): void
    {
        // 不需要可靠传输的帧
        $this->assertFalse(FrameType::PADDING->needsReliableDelivery());
        $this->assertFalse(FrameType::PING->needsReliableDelivery());
        $this->assertFalse(FrameType::ACK->needsReliableDelivery());
        $this->assertFalse(FrameType::ACK_ECN->needsReliableDelivery());
        $this->assertFalse(FrameType::PATH_CHALLENGE->needsReliableDelivery());
        $this->assertFalse(FrameType::PATH_RESPONSE->needsReliableDelivery());
        
        // 需要可靠传输的帧
        $this->assertTrue(FrameType::STREAM->needsReliableDelivery());
        $this->assertTrue(FrameType::CRYPTO->needsReliableDelivery());
        $this->assertTrue(FrameType::CONNECTION_CLOSE->needsReliableDelivery());
        $this->assertTrue(FrameType::MAX_DATA->needsReliableDelivery());
    }

    /**
     * 测试流控制帧判断
     */
    public function testIsFlowControl(): void
    {
        $this->assertFalse(FrameType::PADDING->isFlowControl());
        $this->assertFalse(FrameType::STREAM->isFlowControl());
        
        // 流控制帧
        $this->assertTrue(FrameType::MAX_DATA->isFlowControl());
        $this->assertTrue(FrameType::MAX_STREAM_DATA->isFlowControl());
        $this->assertTrue(FrameType::MAX_STREAMS->isFlowControl());
        $this->assertTrue(FrameType::MAX_STREAMS_UNI->isFlowControl());
        $this->assertTrue(FrameType::DATA_BLOCKED->isFlowControl());
        $this->assertTrue(FrameType::STREAM_DATA_BLOCKED->isFlowControl());
        $this->assertTrue(FrameType::STREAMS_BLOCKED->isFlowControl());
        $this->assertTrue(FrameType::STREAMS_BLOCKED_UNI->isFlowControl());
    }

    /**
     * 测试连接管理帧判断
     */
    public function testIsConnectionManagement(): void
    {
        $this->assertFalse(FrameType::PADDING->isConnectionManagement());
        $this->assertFalse(FrameType::STREAM->isConnectionManagement());
        $this->assertFalse(FrameType::MAX_DATA->isConnectionManagement());
        
        // 连接管理帧
        $this->assertTrue(FrameType::NEW_CONNECTION_ID->isConnectionManagement());
        $this->assertTrue(FrameType::RETIRE_CONNECTION_ID->isConnectionManagement());
        $this->assertTrue(FrameType::PATH_CHALLENGE->isConnectionManagement());
        $this->assertTrue(FrameType::PATH_RESPONSE->isConnectionManagement());
        $this->assertTrue(FrameType::CONNECTION_CLOSE->isConnectionManagement());
        $this->assertTrue(FrameType::CONNECTION_CLOSE_APP->isConnectionManagement());
        $this->assertTrue(FrameType::HANDSHAKE_DONE->isConnectionManagement());
    }

    /**
     * 测试确认帧判断
     */
    public function testIsAckFrame(): void
    {
        $this->assertFalse(FrameType::PADDING->isAckFrame());
        $this->assertFalse(FrameType::STREAM->isAckFrame());
        
        $this->assertTrue(FrameType::ACK->isAckFrame());
        $this->assertTrue(FrameType::ACK_ECN->isAckFrame());
    }

    /**
     * 测试流帧偏移量字段
     */
    public function testHasOffset(): void
    {
        // 非流帧
        $this->assertFalse(FrameType::PADDING->hasOffset());
        $this->assertFalse(FrameType::ACK->hasOffset());
        
        // 流帧中带偏移量的（位2设置）
        $this->assertFalse(FrameType::STREAM->hasOffset());       // 0x08: 位2=0
        $this->assertFalse(FrameType::STREAM_FIN->hasOffset());   // 0x09: 位2=0
        $this->assertFalse(FrameType::STREAM_LEN->hasOffset());   // 0x0A: 位2=0
        $this->assertFalse(FrameType::STREAM_LEN_FIN->hasOffset()); // 0x0B: 位2=0
        $this->assertTrue(FrameType::STREAM_OFF->hasOffset());      // 0x0C: 位2=1
        $this->assertTrue(FrameType::STREAM_OFF_FIN->hasOffset());  // 0x0D: 位2=1
        $this->assertTrue(FrameType::STREAM_OFF_LEN->hasOffset());  // 0x0E: 位2=1
        $this->assertTrue(FrameType::STREAM_OFF_LEN_FIN->hasOffset()); // 0x0F: 位2=1
    }

    /**
     * 测试流帧长度字段
     */
    public function testHasLength(): void
    {
        // 非流帧
        $this->assertFalse(FrameType::PADDING->hasLength());
        
        // 流帧中带长度的（位1设置）
        $this->assertFalse(FrameType::STREAM->hasLength());       // 0x08: 位1=0
        $this->assertFalse(FrameType::STREAM_FIN->hasLength());   // 0x09: 位1=0
        $this->assertTrue(FrameType::STREAM_LEN->hasLength());    // 0x0A: 位1=1
        $this->assertTrue(FrameType::STREAM_LEN_FIN->hasLength()); // 0x0B: 位1=1
        $this->assertFalse(FrameType::STREAM_OFF->hasLength());   // 0x0C: 位1=0
        $this->assertFalse(FrameType::STREAM_OFF_FIN->hasLength()); // 0x0D: 位1=0
        $this->assertTrue(FrameType::STREAM_OFF_LEN->hasLength()); // 0x0E: 位1=1
        $this->assertTrue(FrameType::STREAM_OFF_LEN_FIN->hasLength()); // 0x0F: 位1=1
    }

    /**
     * 测试流帧FIN标志
     */
    public function testHasFin(): void
    {
        // 非流帧
        $this->assertFalse(FrameType::PADDING->hasFin());
        
        // 流帧中带FIN的（位0设置）
        $this->assertFalse(FrameType::STREAM->hasFin());       // 0x08: 位0=0
        $this->assertTrue(FrameType::STREAM_FIN->hasFin());    // 0x09: 位0=1
        $this->assertFalse(FrameType::STREAM_LEN->hasFin());   // 0x0A: 位0=0
        $this->assertTrue(FrameType::STREAM_LEN_FIN->hasFin()); // 0x0B: 位0=1
        $this->assertFalse(FrameType::STREAM_OFF->hasFin());   // 0x0C: 位0=0
        $this->assertTrue(FrameType::STREAM_OFF_FIN->hasFin()); // 0x0D: 位0=1
        $this->assertFalse(FrameType::STREAM_OFF_LEN->hasFin()); // 0x0E: 位0=0
        $this->assertTrue(FrameType::STREAM_OFF_LEN_FIN->hasFin()); // 0x0F: 位0=1
    }

    /**
     * 测试Initial包中允许的帧
     */
    public function testAllowedInInitial(): void
    {
        $this->assertTrue(FrameType::PADDING->allowedInInitial());
        $this->assertTrue(FrameType::PING->allowedInInitial());
        $this->assertTrue(FrameType::ACK->allowedInInitial());
        $this->assertTrue(FrameType::ACK_ECN->allowedInInitial());
        $this->assertTrue(FrameType::CRYPTO->allowedInInitial());
        $this->assertTrue(FrameType::CONNECTION_CLOSE->allowedInInitial());
        
        $this->assertFalse(FrameType::STREAM->allowedInInitial());
        $this->assertFalse(FrameType::MAX_DATA->allowedInInitial());
        $this->assertFalse(FrameType::HANDSHAKE_DONE->allowedInInitial());
    }

    /**
     * 测试Handshake包中允许的帧
     */
    public function testAllowedInHandshake(): void
    {
        $this->assertTrue(FrameType::PADDING->allowedInHandshake());
        $this->assertTrue(FrameType::PING->allowedInHandshake());
        $this->assertTrue(FrameType::ACK->allowedInHandshake());
        $this->assertTrue(FrameType::ACK_ECN->allowedInHandshake());
        $this->assertTrue(FrameType::CRYPTO->allowedInHandshake());
        $this->assertTrue(FrameType::CONNECTION_CLOSE->allowedInHandshake());
        
        $this->assertFalse(FrameType::STREAM->allowedInHandshake());
        $this->assertFalse(FrameType::MAX_DATA->allowedInHandshake());
        $this->assertFalse(FrameType::HANDSHAKE_DONE->allowedInHandshake());
    }

    /**
     * 测试1-RTT包中允许的帧
     */
    public function testAllowedIn1RTT(): void
    {
        // 1-RTT包可以包含所有帧类型
        $this->assertTrue(FrameType::PADDING->allowedIn1RTT());
        $this->assertTrue(FrameType::STREAM->allowedIn1RTT());
        $this->assertTrue(FrameType::MAX_DATA->allowedIn1RTT());
        $this->assertTrue(FrameType::HANDSHAKE_DONE->allowedIn1RTT());
        $this->assertTrue(FrameType::CONNECTION_CLOSE->allowedIn1RTT());
    }

    /**
     * 测试帧类型描述
     */
    public function testGetDescription(): void
    {
        $this->assertSame('填充帧', FrameType::PADDING->getDescription());
        $this->assertSame('PING帧', FrameType::PING->getDescription());
        $this->assertSame('确认帧', FrameType::ACK->getDescription());
        $this->assertSame('流帧', FrameType::STREAM->getDescription());
        $this->assertSame('流帧', FrameType::STREAM_FIN->getDescription());
        $this->assertSame('加密帧', FrameType::CRYPTO->getDescription());
        $this->assertSame('最大数据帧', FrameType::MAX_DATA->getDescription());
        $this->assertSame('连接关闭帧', FrameType::CONNECTION_CLOSE->getDescription());
        $this->assertSame('握手完成帧', FrameType::HANDSHAKE_DONE->getDescription());
    }
} 