<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Tests\Unit\Exception;

use PHPUnit\Framework\TestCase;
use Tourze\QUIC\Core\Enum\QuicError;
use Tourze\QUIC\Core\Exception\FrameException;

class FrameExceptionTest extends TestCase
{
    public function testEncodingError(): void
    {
        $exception = FrameException::encodingError();
        
        $this->assertInstanceOf(FrameException::class, $exception);
        $this->assertSame('帧编码错误', $exception->getMessage());
        $this->assertSame(QuicError::FRAME_ENCODING_ERROR->value, $exception->getCode());
    }

    public function testEncodingErrorWithDetails(): void
    {
        $exception = FrameException::encodingError('无效的帧类型');
        
        $this->assertInstanceOf(FrameException::class, $exception);
        $this->assertSame('帧编码错误: 无效的帧类型', $exception->getMessage());
        $this->assertSame(QuicError::FRAME_ENCODING_ERROR->value, $exception->getCode());
    }

    public function testTransportParameterError(): void
    {
        $exception = FrameException::transportParameterError();
        
        $this->assertInstanceOf(FrameException::class, $exception);
        $this->assertSame('传输参数错误', $exception->getMessage());
        $this->assertSame(QuicError::TRANSPORT_PARAMETER_ERROR->value, $exception->getCode());
    }

    public function testTransportParameterErrorWithDetails(): void
    {
        $exception = FrameException::transportParameterError('参数值超出范围');
        
        $this->assertInstanceOf(FrameException::class, $exception);
        $this->assertSame('传输参数错误: 参数值超出范围', $exception->getMessage());
        $this->assertSame(QuicError::TRANSPORT_PARAMETER_ERROR->value, $exception->getCode());
    }
}