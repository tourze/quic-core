<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Exception;

use Tourze\QUIC\Core\Enum\QuicError;

/**
 * QUIC连接异常
 * 
 * 用于表示连接层面的错误，如连接被拒绝、协议违规等
 */
class ConnectionException extends QuicException
{
    /**
     * 创建连接被拒绝异常
     */
    public static function refused(string $reason = ''): self
    {
        $message = '连接被拒绝' . ($reason !== '' ? ": {$reason}" : '');
        return new self($message, QuicError::CONNECTION_REFUSED);
    }

    /**
     * 创建协议违规异常
     */
    public static function protocolViolation(string $details = ''): self
    {
        $message = '协议违规' . ($details !== '' ? ": {$details}" : '');
        return new self($message, QuicError::PROTOCOL_VIOLATION);
    }

    /**
     * 创建内部错误异常
     */
    public static function internalError(string $details = ''): self
    {
        $message = '内部错误' . ($details !== '' ? ": {$details}" : '');
        return new self($message, QuicError::INTERNAL_ERROR);
    }

    /**
     * 创建无效令牌异常
     */
    public static function invalidToken(string $details = ''): self
    {
        $message = '无效令牌' . ($details !== '' ? ": {$details}" : '');
        return new self($message, QuicError::INVALID_TOKEN);
    }

    /**
     * 创建连接ID限制异常
     */
    public static function connectionIdLimit(string $details = ''): self
    {
        $message = '连接ID限制' . ($details !== '' ? ": {$details}" : '');
        return new self($message, QuicError::CONNECTION_ID_LIMIT_ERROR);
    }

    /**
     * 创建无可用路径异常
     */
    public static function noViablePath(string $details = ''): self
    {
        $message = '无可用路径' . ($details !== '' ? ": {$details}" : '');
        return new self($message, QuicError::NO_VIABLE_PATH);
    }
} 