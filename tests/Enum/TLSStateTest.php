<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Tests\Enum;

use PHPUnit\Framework\TestCase;
use Tourze\QUIC\Core\Enum\TLSState;

/**
 * TLSState 枚举单元测试
 * 
 * @covers \Tourze\QUIC\Core\Enum\TLSState
 */
class TLSStateTest extends TestCase
{
    /**
     * 测试枚举值
     */
    public function testEnumValues(): void
    {
        $this->assertSame('start', TLSState::START->value);
        $this->assertSame('wait_client_hello', TLSState::WAIT_CLIENT_HELLO->value);
        $this->assertSame('wait_server_hello', TLSState::WAIT_SERVER_HELLO->value);
        $this->assertSame('wait_encrypted_extensions', TLSState::WAIT_ENCRYPTED_EXTENSIONS->value);
        $this->assertSame('wait_certificate', TLSState::WAIT_CERTIFICATE->value);
        $this->assertSame('wait_certificate_verify', TLSState::WAIT_CERTIFICATE_VERIFY->value);
        $this->assertSame('wait_finished', TLSState::WAIT_FINISHED->value);
        $this->assertSame('connected', TLSState::CONNECTED->value);
        $this->assertSame('closed', TLSState::CLOSED->value);
    }

    /**
     * 测试是否正在握手
     */
    public function testIsHandshaking(): void
    {
        $this->assertFalse(TLSState::START->isHandshaking());
        $this->assertTrue(TLSState::WAIT_CLIENT_HELLO->isHandshaking());
        $this->assertTrue(TLSState::WAIT_SERVER_HELLO->isHandshaking());
        $this->assertTrue(TLSState::WAIT_ENCRYPTED_EXTENSIONS->isHandshaking());
        $this->assertTrue(TLSState::WAIT_CERTIFICATE->isHandshaking());
        $this->assertTrue(TLSState::WAIT_CERTIFICATE_VERIFY->isHandshaking());
        $this->assertTrue(TLSState::WAIT_FINISHED->isHandshaking());
        $this->assertFalse(TLSState::CONNECTED->isHandshaking());
        $this->assertFalse(TLSState::CLOSED->isHandshaking());
    }

    /**
     * 测试是否已连接
     */
    public function testIsConnected(): void
    {
        $this->assertFalse(TLSState::START->isConnected());
        $this->assertFalse(TLSState::WAIT_CLIENT_HELLO->isConnected());
        $this->assertFalse(TLSState::WAIT_SERVER_HELLO->isConnected());
        $this->assertFalse(TLSState::WAIT_ENCRYPTED_EXTENSIONS->isConnected());
        $this->assertFalse(TLSState::WAIT_CERTIFICATE->isConnected());
        $this->assertFalse(TLSState::WAIT_CERTIFICATE_VERIFY->isConnected());
        $this->assertFalse(TLSState::WAIT_FINISHED->isConnected());
        $this->assertTrue(TLSState::CONNECTED->isConnected());
        $this->assertFalse(TLSState::CLOSED->isConnected());
    }

    /**
     * 测试是否已关闭
     */
    public function testIsClosed(): void
    {
        $this->assertFalse(TLSState::START->isClosed());
        $this->assertFalse(TLSState::WAIT_CLIENT_HELLO->isClosed());
        $this->assertFalse(TLSState::WAIT_SERVER_HELLO->isClosed());
        $this->assertFalse(TLSState::WAIT_ENCRYPTED_EXTENSIONS->isClosed());
        $this->assertFalse(TLSState::WAIT_CERTIFICATE->isClosed());
        $this->assertFalse(TLSState::WAIT_CERTIFICATE_VERIFY->isClosed());
        $this->assertFalse(TLSState::WAIT_FINISHED->isClosed());
        $this->assertFalse(TLSState::CONNECTED->isClosed());
        $this->assertTrue(TLSState::CLOSED->isClosed());
    }

    /**
     * 测试状态描述
     */
    public function testGetDescription(): void
    {
        $this->assertSame('开始', TLSState::START->getDescription());
        $this->assertSame('等待客户端Hello', TLSState::WAIT_CLIENT_HELLO->getDescription());
        $this->assertSame('等待服务器Hello', TLSState::WAIT_SERVER_HELLO->getDescription());
        $this->assertSame('等待加密扩展', TLSState::WAIT_ENCRYPTED_EXTENSIONS->getDescription());
        $this->assertSame('等待证书', TLSState::WAIT_CERTIFICATE->getDescription());
        $this->assertSame('等待证书验证', TLSState::WAIT_CERTIFICATE_VERIFY->getDescription());
        $this->assertSame('等待完成', TLSState::WAIT_FINISHED->getDescription());
        $this->assertSame('已连接', TLSState::CONNECTED->getDescription());
        $this->assertSame('已关闭', TLSState::CLOSED->getDescription());
    }
} 