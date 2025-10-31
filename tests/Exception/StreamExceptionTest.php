<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Tests\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitBase\AbstractExceptionTestCase;
use Tourze\QUIC\Core\Enum\QuicError;
use Tourze\QUIC\Core\Exception\StreamException;

/**
 * @internal
 */
#[CoversClass(StreamException::class)]
final class StreamExceptionTest extends AbstractExceptionTestCase
{
    public function testStateError(): void
    {
        $exception = StreamException::stateError();

        $this->assertSame('流状态错误', $exception->getMessage());
        $this->assertSame(QuicError::STREAM_STATE_ERROR->value, $exception->getCode());
    }

    public function testStateErrorWithDetails(): void
    {
        $exception = StreamException::stateError('流已关闭');

        $this->assertSame('流状态错误: 流已关闭', $exception->getMessage());
        $this->assertSame(QuicError::STREAM_STATE_ERROR->value, $exception->getCode());
    }

    public function testLimitError(): void
    {
        $exception = StreamException::limitError();

        $this->assertSame('流限制错误', $exception->getMessage());
        $this->assertSame(QuicError::STREAM_LIMIT_ERROR->value, $exception->getCode());
    }

    public function testLimitErrorWithDetails(): void
    {
        $exception = StreamException::limitError('超过最大流数量');

        $this->assertSame('流限制错误: 超过最大流数量', $exception->getMessage());
        $this->assertSame(QuicError::STREAM_LIMIT_ERROR->value, $exception->getCode());
    }

    public function testFinalSizeError(): void
    {
        $exception = StreamException::finalSizeError();

        $this->assertSame('最终大小错误', $exception->getMessage());
        $this->assertSame(QuicError::FINAL_SIZE_ERROR->value, $exception->getCode());
    }

    public function testFinalSizeErrorWithDetails(): void
    {
        $exception = StreamException::finalSizeError('数据大小不匹配');

        $this->assertSame('最终大小错误: 数据大小不匹配', $exception->getMessage());
        $this->assertSame(QuicError::FINAL_SIZE_ERROR->value, $exception->getCode());
    }

    public function testFlowControlError(): void
    {
        $exception = StreamException::flowControlError();

        $this->assertSame('流量控制错误', $exception->getMessage());
        $this->assertSame(QuicError::FLOW_CONTROL_ERROR->value, $exception->getCode());
    }

    public function testFlowControlErrorWithDetails(): void
    {
        $exception = StreamException::flowControlError('超过流量限制');

        $this->assertSame('流量控制错误: 超过流量限制', $exception->getMessage());
        $this->assertSame(QuicError::FLOW_CONTROL_ERROR->value, $exception->getCode());
    }
}
