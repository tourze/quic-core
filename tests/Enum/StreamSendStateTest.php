<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Tests\Enum;

use PHPUnit\Framework\TestCase;
use Tourze\QUIC\Core\Enum\StreamSendState;

/**
 * StreamSendState 枚举单元测试
 * 
 * @covers \Tourze\QUIC\Core\Enum\StreamSendState
 */
class StreamSendStateTest extends TestCase
{
    /**
     * 测试枚举值
     */
    public function testEnumValues(): void
    {
        $this->assertSame('ready', StreamSendState::READY->value);
        $this->assertSame('send', StreamSendState::SEND->value);
        $this->assertSame('data_sent', StreamSendState::DATA_SENT->value);
        $this->assertSame('reset_sent', StreamSendState::RESET_SENT->value);
        $this->assertSame('reset_recvd', StreamSendState::RESET_RECVD->value);
    }

    /**
     * 测试是否可以发送数据
     */
    public function testCanSendData(): void
    {
        $this->assertTrue(StreamSendState::READY->canSendData());
        $this->assertTrue(StreamSendState::SEND->canSendData());
        $this->assertTrue(StreamSendState::DATA_SENT->canSendData());
        $this->assertFalse(StreamSendState::RESET_SENT->canSendData());
        $this->assertFalse(StreamSendState::RESET_RECVD->canSendData());
    }

    /**
     * 测试是否已重置
     */
    public function testIsReset(): void
    {
        $this->assertFalse(StreamSendState::READY->isReset());
        $this->assertFalse(StreamSendState::SEND->isReset());
        $this->assertFalse(StreamSendState::DATA_SENT->isReset());
        $this->assertTrue(StreamSendState::RESET_SENT->isReset());
        $this->assertTrue(StreamSendState::RESET_RECVD->isReset());
    }

    /**
     * 测试是否为终止状态
     */
    public function testIsTerminal(): void
    {
        $this->assertFalse(StreamSendState::READY->isTerminal());
        $this->assertFalse(StreamSendState::SEND->isTerminal());
        $this->assertTrue(StreamSendState::DATA_SENT->isTerminal());
        $this->assertFalse(StreamSendState::RESET_SENT->isTerminal());
        $this->assertTrue(StreamSendState::RESET_RECVD->isTerminal());
    }

    /**
     * 测试状态描述
     */
    public function testGetDescription(): void
    {
        $this->assertSame('准备发送', StreamSendState::READY->getDescription());
        $this->assertSame('正在发送', StreamSendState::SEND->getDescription());
        $this->assertSame('数据已发送', StreamSendState::DATA_SENT->getDescription());
        $this->assertSame('已发送重置', StreamSendState::RESET_SENT->getDescription());
        $this->assertSame('已收到重置确认', StreamSendState::RESET_RECVD->getDescription());
    }
} 