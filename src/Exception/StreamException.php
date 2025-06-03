<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Exception;

use Tourze\QUIC\Core\Enum\QuicError;

/**
 * QUIC流异常
 * 
 * 用于表示流层面的错误，如流状态错误、流限制等
 */
class StreamException extends QuicException
{
    /**
     * 创建流状态错误异常
     */
    public static function stateError(string $details = ''): self
    {
        $message = '流状态错误' . ($details ? ": {$details}" : '');
        return new self($message, QuicError::STREAM_STATE_ERROR);
    }

    /**
     * 创建流限制错误异常
     */
    public static function limitError(string $details = ''): self
    {
        $message = '流限制错误' . ($details ? ": {$details}" : '');
        return new self($message, QuicError::STREAM_LIMIT_ERROR);
    }

    /**
     * 创建最终大小错误异常
     */
    public static function finalSizeError(string $details = ''): self
    {
        $message = '最终大小错误' . ($details ? ": {$details}" : '');
        return new self($message, QuicError::FINAL_SIZE_ERROR);
    }

    /**
     * 创建流量控制错误异常
     */
    public static function flowControlError(string $details = ''): self
    {
        $message = '流量控制错误' . ($details ? ": {$details}" : '');
        return new self($message, QuicError::FLOW_CONTROL_ERROR);
    }
} 