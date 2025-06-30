<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Tests\Enum;

use PHPUnit\Framework\TestCase;
use Tourze\QUIC\Core\Enum\StreamRecvState;

/**
 * StreamRecvState 枚举单元测试
 *
 * @covers \Tourze\QUIC\Core\Enum\StreamRecvState
 */
class StreamRecvStateTest extends TestCase
{
    /**
     * 测试枚举值
     */
    public function testEnumValues(): void
    {
        $this->assertSame('recv', StreamRecvState::RECV->value);
        $this->assertSame('size_known', StreamRecvState::SIZE_KNOWN->value);
        $this->assertSame('data_recvd', StreamRecvState::DATA_RECVD->value);
        $this->assertSame('reset_recvd', StreamRecvState::RESET_RECVD->value);
        $this->assertSame('reset_read', StreamRecvState::RESET_READ->value);
    }

    /**
     * 测试是否可以接收数据
     */
    public function testCanReceiveData(): void
    {
        $this->assertTrue(StreamRecvState::RECV->canReceiveData());
        $this->assertTrue(StreamRecvState::SIZE_KNOWN->canReceiveData());
        $this->assertFalse(StreamRecvState::DATA_RECVD->canReceiveData());
        $this->assertFalse(StreamRecvState::RESET_RECVD->canReceiveData());
        $this->assertFalse(StreamRecvState::RESET_READ->canReceiveData());
    }

    /**
     * 测试是否已重置
     */
    public function testIsReset(): void
    {
        $this->assertFalse(StreamRecvState::RECV->isReset());
        $this->assertFalse(StreamRecvState::SIZE_KNOWN->isReset());
        $this->assertFalse(StreamRecvState::DATA_RECVD->isReset());
        $this->assertTrue(StreamRecvState::RESET_RECVD->isReset());
        $this->assertTrue(StreamRecvState::RESET_READ->isReset());
    }

    /**
     * 测试是否为终止状态
     */
    public function testIsTerminal(): void
    {
        $this->assertFalse(StreamRecvState::RECV->isTerminal());
        $this->assertFalse(StreamRecvState::SIZE_KNOWN->isTerminal());
        $this->assertTrue(StreamRecvState::DATA_RECVD->isTerminal());
        $this->assertFalse(StreamRecvState::RESET_RECVD->isTerminal());
        $this->assertTrue(StreamRecvState::RESET_READ->isTerminal());
    }

    /**
     * 测试状态描述
     */
    public function testGetDescription(): void
    {
        $this->assertSame('可以接收数据', StreamRecvState::RECV->getDescription());
        $this->assertSame('已知最终大小', StreamRecvState::SIZE_KNOWN->getDescription());
        $this->assertSame('已接收所有数据', StreamRecvState::DATA_RECVD->getDescription());
        $this->assertSame('收到重置流', StreamRecvState::RESET_RECVD->getDescription());
        $this->assertSame('应用已读取重置', StreamRecvState::RESET_READ->getDescription());
    }
} 