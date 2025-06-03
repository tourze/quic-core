<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Exception;

use Exception;
use Tourze\QUIC\Core\Enum\QuicError;

/**
 * QUIC基础异常类
 * 
 * 所有QUIC相关异常的基础类，包含QUIC错误码信息
 */
class QuicException extends Exception
{
    /**
     * @param string $message 异常消息
     * @param QuicError $errorCode QUIC错误码
     * @param Exception|null $previous 前一个异常
     */
    public function __construct(
        string $message,
        private readonly QuicError $errorCode,
        ?Exception $previous = null
    ) {
        parent::__construct($message, $errorCode->value, $previous);
    }

    /**
     * 获取QUIC错误码
     */
    public function getQuicError(): QuicError
    {
        return $this->errorCode;
    }

    /**
     * 判断是否为连接层错误
     */
    public function isConnectionError(): bool
    {
        return $this->errorCode->isConnectionError();
    }

    /**
     * 判断是否为应用层错误
     */
    public function isApplicationError(): bool
    {
        return $this->errorCode->isApplicationError();
    }

    /**
     * 判断是否为致命错误
     */
    public function isFatal(): bool
    {
        return $this->errorCode->isFatal();
    }

    /**
     * 获取错误的详细描述
     */
    public function getErrorDescription(): string
    {
        return $this->errorCode->getDescription();
    }

    /**
     * 转换为字符串表示
     */
    public function __toString(): string
    {
        $errorName = $this->errorCode->name;
        $errorDesc = $this->errorCode->getDescription();
        return sprintf(
            'QUIC异常 [%s] %s: %s (错误码: 0x%X)',
            $errorName,
            $errorDesc,
            $this->getMessage(),
            $this->errorCode->value
        );
    }
} 