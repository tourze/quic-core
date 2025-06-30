<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Tests\Exception;

use PHPUnit\Framework\TestCase;
use Tourze\QUIC\Core\Enum\QuicError;
use Tourze\QUIC\Core\Exception\ConnectionException;

/**
 * ConnectionException 连接异常类单元测试
 *
 * @covers \Tourze\QUIC\Core\Exception\ConnectionException
 */
class ConnectionExceptionTest extends TestCase
{
    /**
     * 测试连接被拒绝异常
     */
    public function testRefused(): void
    {
        // 无详细信息
        $exception = ConnectionException::refused();
        $this->assertSame('连接被拒绝', $exception->getMessage());
        $this->assertSame(QuicError::CONNECTION_REFUSED, $exception->getQuicError());
        $this->assertTrue($exception->isConnectionError());
        $this->assertTrue($exception->isFatal());

        // 带详细信息
        $exception = ConnectionException::refused('服务器拒绝');
        $this->assertSame('连接被拒绝: 服务器拒绝', $exception->getMessage());
        $this->assertSame(QuicError::CONNECTION_REFUSED, $exception->getQuicError());
    }

    /**
     * 测试协议违规异常
     */
    public function testProtocolViolation(): void
    {
        // 无详细信息
        $exception = ConnectionException::protocolViolation();
        $this->assertSame('协议违规', $exception->getMessage());
        $this->assertSame(QuicError::PROTOCOL_VIOLATION, $exception->getQuicError());
        $this->assertTrue($exception->isConnectionError());
        $this->assertTrue($exception->isFatal());

        // 带详细信息
        $exception = ConnectionException::protocolViolation('无效的帧格式');
        $this->assertSame('协议违规: 无效的帧格式', $exception->getMessage());
        $this->assertSame(QuicError::PROTOCOL_VIOLATION, $exception->getQuicError());
    }

    /**
     * 测试内部错误异常
     */
    public function testInternalError(): void
    {
        // 无详细信息
        $exception = ConnectionException::internalError();
        $this->assertSame('内部错误', $exception->getMessage());
        $this->assertSame(QuicError::INTERNAL_ERROR, $exception->getQuicError());
        $this->assertTrue($exception->isConnectionError());
        $this->assertTrue($exception->isFatal());

        // 带详细信息
        $exception = ConnectionException::internalError('内存分配失败');
        $this->assertSame('内部错误: 内存分配失败', $exception->getMessage());
        $this->assertSame(QuicError::INTERNAL_ERROR, $exception->getQuicError());
    }

    /**
     * 测试无效令牌异常
     */
    public function testInvalidToken(): void
    {
        // 无详细信息
        $exception = ConnectionException::invalidToken();
        $this->assertSame('无效令牌', $exception->getMessage());
        $this->assertSame(QuicError::INVALID_TOKEN, $exception->getQuicError());
        $this->assertTrue($exception->isConnectionError());

        // 带详细信息
        $exception = ConnectionException::invalidToken('令牌已过期');
        $this->assertSame('无效令牌: 令牌已过期', $exception->getMessage());
        $this->assertSame(QuicError::INVALID_TOKEN, $exception->getQuicError());
    }

    /**
     * 测试连接ID限制异常
     */
    public function testConnectionIdLimit(): void
    {
        // 无详细信息
        $exception = ConnectionException::connectionIdLimit();
        $this->assertSame('连接ID限制', $exception->getMessage());
        $this->assertSame(QuicError::CONNECTION_ID_LIMIT_ERROR, $exception->getQuicError());
        $this->assertTrue($exception->isConnectionError());

        // 带详细信息
        $exception = ConnectionException::connectionIdLimit('超过最大连接ID数量');
        $this->assertSame('连接ID限制: 超过最大连接ID数量', $exception->getMessage());
        $this->assertSame(QuicError::CONNECTION_ID_LIMIT_ERROR, $exception->getQuicError());
    }

    /**
     * 测试无可用路径异常
     */
    public function testNoViablePath(): void
    {
        // 无详细信息
        $exception = ConnectionException::noViablePath();
        $this->assertSame('无可用路径', $exception->getMessage());
        $this->assertSame(QuicError::NO_VIABLE_PATH, $exception->getQuicError());
        $this->assertTrue($exception->isConnectionError());

        // 带详细信息
        $exception = ConnectionException::noViablePath('所有路径验证失败');
        $this->assertSame('无可用路径: 所有路径验证失败', $exception->getMessage());
        $this->assertSame(QuicError::NO_VIABLE_PATH, $exception->getQuicError());
    }

    /**
     * 测试异常继承关系
     */
    public function testInheritance(): void
    {
        $exception = ConnectionException::refused();
        
        $this->assertInstanceOf('Tourze\QUIC\Core\Exception\QuicException', $exception);
        $this->assertInstanceOf('Exception', $exception);
    }

    /**
     * 测试所有工厂方法的错误分类
     */
    public function testErrorClassification(): void
    {
        $exceptions = [
            ConnectionException::refused(),
            ConnectionException::protocolViolation(),
            ConnectionException::internalError(),
            ConnectionException::invalidToken(),
            ConnectionException::connectionIdLimit(),
            ConnectionException::noViablePath(),
        ];

        foreach ($exceptions as $exception) {
            $this->assertTrue($exception->isConnectionError());
            $this->assertFalse($exception->isApplicationError());
        }
    }
} 