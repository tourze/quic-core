<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Exception;

use Tourze\QUIC\Core\Enum\QuicError;

/**
 * QUIC帧异常
 *
 * 用于表示帧处理过程中的错误，如帧编码错误、传输参数错误等
 */
class FrameException extends QuicException
{
    /**
     * 创建帧编码错误异常
     */
    public static function encodingError(string $details = ''): self
    {
        $message = '帧编码错误' . ($details !== '' ? ": {$details}" : '');
        return new self($message, QuicError::FRAME_ENCODING_ERROR);
    }

    /**
     * 创建传输参数错误异常
     */
    public static function transportParameterError(string $details = ''): self
    {
        $message = '传输参数错误' . ($details !== '' ? ": {$details}" : '');
        return new self($message, QuicError::TRANSPORT_PARAMETER_ERROR);
    }
} 