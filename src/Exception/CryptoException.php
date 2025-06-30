<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Exception;

use Tourze\QUIC\Core\Enum\QuicError;

/**
 * QUIC加密异常
 *
 * 用于表示加密和TLS相关的错误
 */
class CryptoException extends QuicException
{
    /**
     * 创建加密缓冲区溢出异常
     */
    public static function bufferExceeded(string $details = ''): self
    {
        $message = '加密缓冲区溢出' . ($details !== '' ? ": {$details}" : '');
        return new self($message, QuicError::CRYPTO_BUFFER_EXCEEDED);
    }

    /**
     * 创建密钥更新错误异常
     */
    public static function keyUpdateError(string $details = ''): self
    {
        $message = '密钥更新错误' . ($details !== '' ? ": {$details}" : '');
        return new self($message, QuicError::KEY_UPDATE_ERROR);
    }

    /**
     * 创建AEAD限制达到异常
     */
    public static function aeadLimitReached(string $details = ''): self
    {
        $message = 'AEAD限制达到' . ($details !== '' ? ": {$details}" : '');
        return new self($message, QuicError::AEAD_LIMIT_REACHED);
    }

    /**
     * 创建通用加密错误异常
     */
    public static function cryptoError(string $details = ''): self
    {
        $message = '加密错误' . ($details !== '' ? ": {$details}" : '');
        return new self($message, QuicError::CRYPTO_ERROR);
    }
} 