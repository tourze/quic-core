<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Enum;

/**
 * QUIC流类型枚举
 * 
 * 定义QUIC流的类型和特性
 * 参考：https://tools.ietf.org/html/rfc9000#section-2.1
 */
enum StreamType: int
{
    case CLIENT_BIDI = 0x00;    // 客户端发起的双向流
    case SERVER_BIDI = 0x01;    // 服务端发起的双向流
    case CLIENT_UNI = 0x02;     // 客户端发起的单向流
    case SERVER_UNI = 0x03;     // 服务端发起的单向流

    /**
     * 判断是否为双向流
     */
    public function isBidirectional(): bool
    {
        return in_array($this, [
            self::CLIENT_BIDI,
            self::SERVER_BIDI
        ]);
    }

    /**
     * 判断是否为单向流
     */
    public function isUnidirectional(): bool
    {
        return in_array($this, [
            self::CLIENT_UNI,
            self::SERVER_UNI
        ]);
    }

    /**
     * 判断是否为客户端发起
     */
    public function isClientInitiated(): bool
    {
        return in_array($this, [
            self::CLIENT_BIDI,
            self::CLIENT_UNI
        ]);
    }

    /**
     * 判断是否为服务端发起
     */
    public function isServerInitiated(): bool
    {
        return in_array($this, [
            self::SERVER_BIDI,
            self::SERVER_UNI
        ]);
    }

    /**
     * 从流ID判断流类型
     */
    public static function fromStreamId(int $streamId): self
    {
        return match ($streamId & 0x03) {
            0x00 => self::CLIENT_BIDI,
            0x01 => self::SERVER_BIDI,
            0x02 => self::CLIENT_UNI,
            0x03 => self::SERVER_UNI,
        };
    }

    /**
     * 获取类型的中文描述
     */
    public function getDescription(): string
    {
        return match($this) {
            self::CLIENT_BIDI => '客户端双向流',
            self::SERVER_BIDI => '服务端双向流',
            self::CLIENT_UNI => '客户端单向流',
            self::SERVER_UNI => '服务端单向流',
        };
    }
}
