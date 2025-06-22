<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

/**
 * QUIC错误码枚举
 * 
 * 根据RFC 9000定义的所有QUIC错误类型
 * 参考：https://tools.ietf.org/html/rfc9000#section-20
 */
enum QuicError: int implements Itemable, Labelable, Selectable
{
    use ItemTrait;
    use SelectTrait;
    case NO_ERROR = 0x0;
    case INTERNAL_ERROR = 0x1;
    case CONNECTION_REFUSED = 0x2;
    case FLOW_CONTROL_ERROR = 0x3;
    case STREAM_LIMIT_ERROR = 0x4;
    case STREAM_STATE_ERROR = 0x5;
    case FINAL_SIZE_ERROR = 0x6;
    case FRAME_ENCODING_ERROR = 0x7;
    case TRANSPORT_PARAMETER_ERROR = 0x8;
    case CONNECTION_ID_LIMIT_ERROR = 0x9;
    case PROTOCOL_VIOLATION = 0xA;
    case INVALID_TOKEN = 0xB;
    case APPLICATION_ERROR = 0xC;
    case CRYPTO_BUFFER_EXCEEDED = 0xD;
    case KEY_UPDATE_ERROR = 0xE;
    case AEAD_LIMIT_REACHED = 0xF;
    case NO_VIABLE_PATH = 0x10;
    case CRYPTO_ERROR = 0x100; // 加密相关错误的基础值

    /**
     * 判断是否为连接层错误
     */
    public function isConnectionError(): bool
    {
        return $this->value < 0x100 && $this !== self::APPLICATION_ERROR;
    }

    /**
     * 判断是否为应用层错误
     */
    public function isApplicationError(): bool
    {
        return $this === self::APPLICATION_ERROR;
    }

    /**
     * 判断是否为TLS/加密错误
     */
    public function isCryptoError(): bool
    {
        return $this->value >= 0x100 && $this->value <= 0x1FF;
    }

    /**
     * 判断是否为传输层错误
     */
    public function isTransportError(): bool
    {
        return !$this->isApplicationError() && !$this->isCryptoError();
    }

    /**
     * 判断是否为致命错误（需要立即关闭连接）
     */
    public function isFatal(): bool
    {
        return in_array($this, [
            self::INTERNAL_ERROR,
            self::CONNECTION_REFUSED,
            self::PROTOCOL_VIOLATION,
            self::CRYPTO_BUFFER_EXCEEDED,
            self::KEY_UPDATE_ERROR,
            self::AEAD_LIMIT_REACHED,
        ]);
    }

    /**
     * 获取错误的描述信息
     */
    public function getDescription(): string
    {
        return match($this) {
            self::NO_ERROR => '无错误',
            self::INTERNAL_ERROR => '内部错误',
            self::CONNECTION_REFUSED => '连接被拒绝',
            self::FLOW_CONTROL_ERROR => '流量控制错误',
            self::STREAM_LIMIT_ERROR => '流限制错误',
            self::STREAM_STATE_ERROR => '流状态错误',
            self::FINAL_SIZE_ERROR => '最终大小错误',
            self::FRAME_ENCODING_ERROR => '帧编码错误',
            self::TRANSPORT_PARAMETER_ERROR => '传输参数错误',
            self::CONNECTION_ID_LIMIT_ERROR => '连接ID限制错误',
            self::PROTOCOL_VIOLATION => '协议违规',
            self::INVALID_TOKEN => '无效令牌',
            self::APPLICATION_ERROR => '应用层错误',
            self::CRYPTO_BUFFER_EXCEEDED => '加密缓冲区溢出',
            self::KEY_UPDATE_ERROR => '密钥更新错误',
            self::AEAD_LIMIT_REACHED => 'AEAD限制达到',
            self::NO_VIABLE_PATH => '无可用路径',
            self::CRYPTO_ERROR => '加密错误',
        };
    }

    /**
     * 获取标签 (EnumExtra 接口要求)
     */
    public function getLabel(): string
    {
        return $this->getDescription();
    }
} 