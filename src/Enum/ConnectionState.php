<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

/**
 * QUIC连接状态枚举
 * 
 * 定义QUIC连接的各种状态及其转换逻辑
 * 参考：https://tools.ietf.org/html/rfc9000#section-4
 */
enum ConnectionState: string implements Itemable, Labelable, Selectable
{
    use ItemTrait;
    use SelectTrait;
    case NEW = 'new';
    case HANDSHAKING = 'handshaking';
    case CONNECTED = 'connected';
    case CLOSING = 'closing';
    case DRAINING = 'draining';
    case CLOSED = 'closed';

    /**
     * 判断是否可以发送数据
     */
    public function canSendData(): bool
    {
        return in_array($this, [
            self::HANDSHAKING,
            self::CONNECTED
        ]);
    }

    /**
     * 判断是否可以接收数据
     */
    public function canReceiveData(): bool
    {
        return in_array($this, [
            self::HANDSHAKING,
            self::CONNECTED,
            self::CLOSING,
            self::DRAINING
        ]);
    }

    /**
     * 判断是否正在握手
     */
    public function isHandshaking(): bool
    {
        return $this === self::HANDSHAKING;
    }

    /**
     * 判断是否已建立连接
     */
    public function isConnected(): bool
    {
        return $this === self::CONNECTED;
    }

    /**
     * 判断是否已关闭或正在关闭
     */
    public function isClosed(): bool
    {
        return in_array($this, [
            self::CLOSING,
            self::DRAINING,
            self::CLOSED
        ]);
    }

    /**
     * 判断是否可以发送流数据
     */
    public function canSendStreamData(): bool
    {
        return $this === self::CONNECTED;
    }

    /**
     * 判断是否可以创建新流
     */
    public function canCreateStream(): bool
    {
        return $this === self::CONNECTED;
    }

    /**
     * 判断连接是否活跃
     */
    public function isActive(): bool
    {
        return !in_array($this, [
            self::CLOSED,
            self::DRAINING
        ]);
    }

    /**
     * 获取可以转换到的状态列表
     */
    public function getValidTransitions(): array
    {
        return match($this) {
            self::NEW => [self::HANDSHAKING, self::CLOSED],
            self::HANDSHAKING => [self::CONNECTED, self::CLOSING, self::CLOSED],
            self::CONNECTED => [self::CLOSING, self::DRAINING, self::CLOSED],
            self::CLOSING => [self::DRAINING, self::CLOSED],
            self::DRAINING => [self::CLOSED],
            self::CLOSED => [],
        };
    }

    /**
     * 判断是否可以转换到指定状态
     */
    public function canTransitionTo(ConnectionState $targetState): bool
    {
        return in_array($targetState, $this->getValidTransitions());
    }

    /**
     * 获取状态的中文描述
     */
    public function getDescription(): string
    {
        return match($this) {
            self::NEW => '新建',
            self::HANDSHAKING => '握手中',
            self::CONNECTED => '已连接',
            self::CLOSING => '关闭中',
            self::DRAINING => '排空中',
            self::CLOSED => '已关闭',
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
