<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Enum;

/**
 * QUIC包类型枚举
 * 
 * 定义QUIC包的各种类型及其特性
 * 参考：https://tools.ietf.org/html/rfc9000#section-17
 */
enum PacketType: int
{
    case INITIAL = 0x00;
    case ZERO_RTT = 0x01;
    case HANDSHAKE = 0x02;
    case RETRY = 0x03;
    case VERSION_NEGOTIATION = 0xFF;

    /**
     * 判断是否为长包头
     */
    public function isLongHeader(): bool
    {
        return in_array($this, [
            self::INITIAL,
            self::ZERO_RTT,
            self::HANDSHAKE,
            self::RETRY,
            self::VERSION_NEGOTIATION
        ]);
    }

    /**
     * 判断是否为加密包
     */
    public function isEncrypted(): bool
    {
        return in_array($this, [
            self::INITIAL,
            self::ZERO_RTT,
            self::HANDSHAKE
        ]);
    }

    /**
     * 判断是否为握手包
     */
    public function isHandshakePacket(): bool
    {
        return in_array($this, [
            self::INITIAL,
            self::HANDSHAKE
        ]);
    }

    /**
     * 判断是否包含连接ID
     */
    public function hasConnectionId(): bool
    {
        return $this->isLongHeader();
    }

    /**
     * 判断是否可以包含ACK帧
     */
    public function canContainAck(): bool
    {
        return in_array($this, [
            self::INITIAL,
            self::HANDSHAKE
        ]);
    }

    /**
     * 判断是否可以包含CRYPTO帧
     */
    public function canContainCrypto(): bool
    {
        return in_array($this, [
            self::INITIAL,
            self::HANDSHAKE
        ]);
    }

    /**
     * 获取包类型名称
     */
    public function getName(): string
    {
        return match($this) {
            self::INITIAL => 'Initial',
            self::ZERO_RTT => 'Zero RTT',
            self::HANDSHAKE => 'Handshake',
            self::RETRY => 'Retry',
            self::VERSION_NEGOTIATION => 'Version Negotiation'
        };
    }

    /**
     * 获取包类型的中文描述
     */
    public function getDescription(): string
    {
        return match($this) {
            self::INITIAL => '初始包',
            self::ZERO_RTT => '零RTT包',
            self::HANDSHAKE => '握手包',
            self::RETRY => '重试包',
            self::VERSION_NEGOTIATION => '版本协商包'
        };
    }

    /**
     * 获取包类型在包头中的编码值
     */
    public function getHeaderType(): int
    {
        return match($this) {
            self::INITIAL => 0x00,
            self::ZERO_RTT => 0x01,
            self::HANDSHAKE => 0x02,
            self::RETRY => 0x03,
            self::VERSION_NEGOTIATION => 0x00, // 版本协商包的特殊处理
        };
    }
} 