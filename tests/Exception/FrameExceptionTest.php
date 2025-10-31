<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Tests\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitBase\AbstractExceptionTestCase;
use Tourze\QUIC\Core\Enum\QuicError;
use Tourze\QUIC\Core\Exception\FrameException;

/**
 * @internal
 */
#[CoversClass(FrameException::class)]
final class FrameExceptionTest extends AbstractExceptionTestCase
{
    public function testEncodingError(): void
    {
        $exception = FrameException::encodingError();

        $this->assertSame('帧编码错误', $exception->getMessage());
        $this->assertSame(QuicError::FRAME_ENCODING_ERROR->value, $exception->getCode());
    }

    public function testEncodingErrorWithDetails(): void
    {
        $exception = FrameException::encodingError('无效的帧类型');

        $this->assertSame('帧编码错误: 无效的帧类型', $exception->getMessage());
        $this->assertSame(QuicError::FRAME_ENCODING_ERROR->value, $exception->getCode());
    }

    public function testTransportParameterError(): void
    {
        $exception = FrameException::transportParameterError();

        $this->assertSame('传输参数错误', $exception->getMessage());
        $this->assertSame(QuicError::TRANSPORT_PARAMETER_ERROR->value, $exception->getCode());
    }

    public function testTransportParameterErrorWithDetails(): void
    {
        $exception = FrameException::transportParameterError('参数值超出范围');

        $this->assertSame('传输参数错误: 参数值超出范围', $exception->getMessage());
        $this->assertSame(QuicError::TRANSPORT_PARAMETER_ERROR->value, $exception->getCode());
    }
}
