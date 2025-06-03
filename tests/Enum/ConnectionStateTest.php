<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Tests\Enum;

use PHPUnit\Framework\TestCase;
use Tourze\QUIC\Core\Enum\ConnectionState;

/**
 * ConnectionState 枚举单元测试
 * 
 * @covers \Tourze\QUIC\Core\Enum\ConnectionState
 */
class ConnectionStateTest extends TestCase
{
    /**
     * 测试枚举值
     */
    public function testEnumValues(): void
    {
        $this->assertSame('new', ConnectionState::NEW->value);
        $this->assertSame('handshaking', ConnectionState::HANDSHAKING->value);
        $this->assertSame('connected', ConnectionState::CONNECTED->value);
        $this->assertSame('closing', ConnectionState::CLOSING->value);
        $this->assertSame('draining', ConnectionState::DRAINING->value);
        $this->assertSame('closed', ConnectionState::CLOSED->value);
    }

    /**
     * 测试是否可以发送数据
     */
    public function testCanSendData(): void
    {
        $this->assertFalse(ConnectionState::NEW->canSendData());
        $this->assertTrue(ConnectionState::HANDSHAKING->canSendData());
        $this->assertTrue(ConnectionState::CONNECTED->canSendData());
        $this->assertFalse(ConnectionState::CLOSING->canSendData());
        $this->assertFalse(ConnectionState::DRAINING->canSendData());
        $this->assertFalse(ConnectionState::CLOSED->canSendData());
    }

    /**
     * 测试是否可以接收数据
     */
    public function testCanReceiveData(): void
    {
        $this->assertFalse(ConnectionState::NEW->canReceiveData());
        $this->assertTrue(ConnectionState::HANDSHAKING->canReceiveData());
        $this->assertTrue(ConnectionState::CONNECTED->canReceiveData());
        $this->assertTrue(ConnectionState::CLOSING->canReceiveData());
        $this->assertTrue(ConnectionState::DRAINING->canReceiveData());
        $this->assertFalse(ConnectionState::CLOSED->canReceiveData());
    }

    /**
     * 测试是否正在握手
     */
    public function testIsHandshaking(): void
    {
        $this->assertFalse(ConnectionState::NEW->isHandshaking());
        $this->assertTrue(ConnectionState::HANDSHAKING->isHandshaking());
        $this->assertFalse(ConnectionState::CONNECTED->isHandshaking());
        $this->assertFalse(ConnectionState::CLOSING->isHandshaking());
        $this->assertFalse(ConnectionState::DRAINING->isHandshaking());
        $this->assertFalse(ConnectionState::CLOSED->isHandshaking());
    }

    /**
     * 测试是否已建立连接
     */
    public function testIsConnected(): void
    {
        $this->assertFalse(ConnectionState::NEW->isConnected());
        $this->assertFalse(ConnectionState::HANDSHAKING->isConnected());
        $this->assertTrue(ConnectionState::CONNECTED->isConnected());
        $this->assertFalse(ConnectionState::CLOSING->isConnected());
        $this->assertFalse(ConnectionState::DRAINING->isConnected());
        $this->assertFalse(ConnectionState::CLOSED->isConnected());
    }

    /**
     * 测试是否已关闭或正在关闭
     */
    public function testIsClosed(): void
    {
        $this->assertFalse(ConnectionState::NEW->isClosed());
        $this->assertFalse(ConnectionState::HANDSHAKING->isClosed());
        $this->assertFalse(ConnectionState::CONNECTED->isClosed());
        $this->assertTrue(ConnectionState::CLOSING->isClosed());
        $this->assertTrue(ConnectionState::DRAINING->isClosed());
        $this->assertTrue(ConnectionState::CLOSED->isClosed());
    }

    /**
     * 测试是否可以发送流数据
     */
    public function testCanSendStreamData(): void
    {
        $this->assertFalse(ConnectionState::NEW->canSendStreamData());
        $this->assertFalse(ConnectionState::HANDSHAKING->canSendStreamData());
        $this->assertTrue(ConnectionState::CONNECTED->canSendStreamData());
        $this->assertFalse(ConnectionState::CLOSING->canSendStreamData());
        $this->assertFalse(ConnectionState::DRAINING->canSendStreamData());
        $this->assertFalse(ConnectionState::CLOSED->canSendStreamData());
    }

    /**
     * 测试是否可以创建新流
     */
    public function testCanCreateStream(): void
    {
        $this->assertFalse(ConnectionState::NEW->canCreateStream());
        $this->assertFalse(ConnectionState::HANDSHAKING->canCreateStream());
        $this->assertTrue(ConnectionState::CONNECTED->canCreateStream());
        $this->assertFalse(ConnectionState::CLOSING->canCreateStream());
        $this->assertFalse(ConnectionState::DRAINING->canCreateStream());
        $this->assertFalse(ConnectionState::CLOSED->canCreateStream());
    }

    /**
     * 测试连接是否活跃
     */
    public function testIsActive(): void
    {
        $this->assertTrue(ConnectionState::NEW->isActive());
        $this->assertTrue(ConnectionState::HANDSHAKING->isActive());
        $this->assertTrue(ConnectionState::CONNECTED->isActive());
        $this->assertTrue(ConnectionState::CLOSING->isActive());
        $this->assertFalse(ConnectionState::DRAINING->isActive());
        $this->assertFalse(ConnectionState::CLOSED->isActive());
    }

    /**
     * 测试可转换状态列表
     */
    public function testGetValidTransitions(): void
    {
        $this->assertSame(
            [ConnectionState::HANDSHAKING, ConnectionState::CLOSED],
            ConnectionState::NEW->getValidTransitions()
        );

        $this->assertSame(
            [ConnectionState::CONNECTED, ConnectionState::CLOSING, ConnectionState::CLOSED],
            ConnectionState::HANDSHAKING->getValidTransitions()
        );

        $this->assertSame(
            [ConnectionState::CLOSING, ConnectionState::DRAINING, ConnectionState::CLOSED],
            ConnectionState::CONNECTED->getValidTransitions()
        );

        $this->assertSame(
            [ConnectionState::DRAINING, ConnectionState::CLOSED],
            ConnectionState::CLOSING->getValidTransitions()
        );

        $this->assertSame(
            [ConnectionState::CLOSED],
            ConnectionState::DRAINING->getValidTransitions()
        );

        $this->assertSame(
            [],
            ConnectionState::CLOSED->getValidTransitions()
        );
    }

    /**
     * 测试状态转换判断
     */
    public function testCanTransitionTo(): void
    {
        // NEW状态的转换
        $this->assertTrue(ConnectionState::NEW->canTransitionTo(ConnectionState::HANDSHAKING));
        $this->assertTrue(ConnectionState::NEW->canTransitionTo(ConnectionState::CLOSED));
        $this->assertFalse(ConnectionState::NEW->canTransitionTo(ConnectionState::CONNECTED));

        // CONNECTED状态的转换
        $this->assertTrue(ConnectionState::CONNECTED->canTransitionTo(ConnectionState::CLOSING));
        $this->assertTrue(ConnectionState::CONNECTED->canTransitionTo(ConnectionState::DRAINING));
        $this->assertTrue(ConnectionState::CONNECTED->canTransitionTo(ConnectionState::CLOSED));
        $this->assertFalse(ConnectionState::CONNECTED->canTransitionTo(ConnectionState::NEW));

        // CLOSED状态的转换（无法转换到任何状态）
        $this->assertFalse(ConnectionState::CLOSED->canTransitionTo(ConnectionState::NEW));
        $this->assertFalse(ConnectionState::CLOSED->canTransitionTo(ConnectionState::CONNECTED));
    }

    /**
     * 测试状态描述
     */
    public function testGetDescription(): void
    {
        $this->assertSame('新建', ConnectionState::NEW->getDescription());
        $this->assertSame('握手中', ConnectionState::HANDSHAKING->getDescription());
        $this->assertSame('已连接', ConnectionState::CONNECTED->getDescription());
        $this->assertSame('关闭中', ConnectionState::CLOSING->getDescription());
        $this->assertSame('排空中', ConnectionState::DRAINING->getDescription());
        $this->assertSame('已关闭', ConnectionState::CLOSED->getDescription());
    }
} 