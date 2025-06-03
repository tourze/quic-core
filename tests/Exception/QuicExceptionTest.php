<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Tests\Exception;

use Exception;
use PHPUnit\Framework\TestCase;
use Tourze\QUIC\Core\Enum\QuicError;
use Tourze\QUIC\Core\Exception\QuicException;

/**
 * QuicException 基础异常类单元测试
 * 
 * @covers \Tourze\QUIC\Core\Exception\QuicException
 */
class QuicExceptionTest extends TestCase
{
    /**
     * 测试创建基础异常
     */
    public function testCreateBasicException(): void
    {
        $exception = new QuicException('测试消息', QuicError::INTERNAL_ERROR);
        
        $this->assertSame('测试消息', $exception->getMessage());
        $this->assertSame(QuicError::INTERNAL_ERROR->value, $exception->getCode());
        $this->assertSame(QuicError::INTERNAL_ERROR, $exception->getQuicError());
    }

    /**
     * 测试带前一个异常的创建
     */
    public function testCreateWithPreviousException(): void
    {
        $previous = new Exception('前一个异常');
        $exception = new QuicException('QUIC异常', QuicError::PROTOCOL_VIOLATION, $previous);
        
        $this->assertSame('QUIC异常', $exception->getMessage());
        $this->assertSame(QuicError::PROTOCOL_VIOLATION, $exception->getQuicError());
        $this->assertSame($previous, $exception->getPrevious());
    }

    /**
     * 测试连接层错误判断
     */
    public function testIsConnectionError(): void
    {
        $connectionError = new QuicException('连接错误', QuicError::CONNECTION_REFUSED);
        $appError = new QuicException('应用错误', QuicError::APPLICATION_ERROR);
        $cryptoError = new QuicException('加密错误', QuicError::CRYPTO_ERROR);
        
        $this->assertTrue($connectionError->isConnectionError());
        $this->assertFalse($appError->isConnectionError());
        $this->assertFalse($cryptoError->isConnectionError());
    }

    /**
     * 测试应用层错误判断
     */
    public function testIsApplicationError(): void
    {
        $connectionError = new QuicException('连接错误', QuicError::CONNECTION_REFUSED);
        $appError = new QuicException('应用错误', QuicError::APPLICATION_ERROR);
        $cryptoError = new QuicException('加密错误', QuicError::CRYPTO_ERROR);
        
        $this->assertFalse($connectionError->isApplicationError());
        $this->assertTrue($appError->isApplicationError());
        $this->assertFalse($cryptoError->isApplicationError());
    }

    /**
     * 测试致命错误判断
     */
    public function testIsFatal(): void
    {
        $fatalError = new QuicException('致命错误', QuicError::INTERNAL_ERROR);
        $nonFatalError = new QuicException('非致命错误', QuicError::FLOW_CONTROL_ERROR);
        
        $this->assertTrue($fatalError->isFatal());
        $this->assertFalse($nonFatalError->isFatal());
    }

    /**
     * 测试获取错误描述
     */
    public function testGetErrorDescription(): void
    {
        $exception = new QuicException('测试', QuicError::INTERNAL_ERROR);
        $this->assertSame('内部错误', $exception->getErrorDescription());
        
        $exception2 = new QuicException('测试', QuicError::CONNECTION_REFUSED);
        $this->assertSame('连接被拒绝', $exception2->getErrorDescription());
    }

    /**
     * 测试toString方法
     */
    public function testToString(): void
    {
        $exception = new QuicException('测试消息', QuicError::INTERNAL_ERROR);
        $string = $exception->__toString();
        
        $this->assertStringContainsString('QUIC异常', $string);
        $this->assertStringContainsString('INTERNAL_ERROR', $string);
        $this->assertStringContainsString('内部错误', $string);
        $this->assertStringContainsString('测试消息', $string);
        $this->assertStringContainsString('0x1', $string);
    }

    /**
     * 测试所有错误码的异常创建
     */
    public function testAllErrorCodes(): void
    {
        $errorCodes = [
            QuicError::NO_ERROR,
            QuicError::INTERNAL_ERROR,
            QuicError::CONNECTION_REFUSED,
            QuicError::FLOW_CONTROL_ERROR,
            QuicError::STREAM_LIMIT_ERROR,
            QuicError::STREAM_STATE_ERROR,
            QuicError::FINAL_SIZE_ERROR,
            QuicError::FRAME_ENCODING_ERROR,
            QuicError::TRANSPORT_PARAMETER_ERROR,
            QuicError::CONNECTION_ID_LIMIT_ERROR,
            QuicError::PROTOCOL_VIOLATION,
            QuicError::INVALID_TOKEN,
            QuicError::APPLICATION_ERROR,
            QuicError::CRYPTO_BUFFER_EXCEEDED,
            QuicError::KEY_UPDATE_ERROR,
            QuicError::AEAD_LIMIT_REACHED,
            QuicError::NO_VIABLE_PATH,
            QuicError::CRYPTO_ERROR,
        ];

        foreach ($errorCodes as $errorCode) {
            $exception = new QuicException("测试{$errorCode->name}", $errorCode);
            $this->assertSame($errorCode, $exception->getQuicError());
            $this->assertSame($errorCode->value, $exception->getCode());
            $this->assertIsString($exception->getErrorDescription());
        }
    }
} 