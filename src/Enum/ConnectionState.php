<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Enum;

use Tourze\Arrayable\Arrayable;
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
 *
 * @implements Arrayable<string, string>
 */
enum ConnectionState: string implements Labelable, Itemable, Selectable, Arrayable
{
    use ItemTrait;
    use SelectTrait;

    case NEW = 'new';
    case HANDSHAKING = 'handshaking';
    case CONNECTED = 'connected';
    case CLOSING = 'closing';
    case DRAINING = 'draining';
    case CLOSED = 'closed';

    public function getLabel(): string
    {
        return match ($this) {
            self::NEW => '新建',
            self::HANDSHAKING => '握手中',
            self::CONNECTED => '已连接',
            self::CLOSING => '关闭中',
            self::DRAINING => '排空中',
            self::CLOSED => '已关闭',
        };
    }

    /**
     * 获取所有枚举的选项数组（用于下拉列表等）
     *
     * @return array<int, array{value: string, label: string}>
     */
    public static function toSelectItems(): array
    {
        $result = [];
        foreach (self::cases() as $case) {
            $result[] = [
                'value' => $case->value,
                'label' => $case->getLabel(),
            ];
        }

        return $result;
    }

    /**
     * 判断是否可以发送数据
     */
    public function canSendData(): bool
    {
        return match ($this) {
            self::HANDSHAKING, self::CONNECTED => true,
            self::NEW, self::CLOSING, self::DRAINING, self::CLOSED => false,
        };
    }

    /**
     * 判断是否可以接收数据
     */
    public function canReceiveData(): bool
    {
        return match ($this) {
            self::HANDSHAKING, self::CONNECTED, self::CLOSING, self::DRAINING => true,
            self::NEW, self::CLOSED => false,
        };
    }

    /**
     * 判断是否正在握手
     */
    public function isHandshaking(): bool
    {
        return self::HANDSHAKING === $this;
    }

    /**
     * 判断是否已建立连接
     */
    public function isConnected(): bool
    {
        return self::CONNECTED === $this;
    }

    /**
     * 判断是否已关闭或正在关闭
     */
    public function isClosed(): bool
    {
        return match ($this) {
            self::CLOSING, self::DRAINING, self::CLOSED => true,
            self::NEW, self::HANDSHAKING, self::CONNECTED => false,
        };
    }

    /**
     * 判断连接是否活跃（未进入排空或关闭状态）
     */
    public function isActive(): bool
    {
        return match ($this) {
            self::NEW, self::HANDSHAKING, self::CONNECTED, self::CLOSING => true,
            self::DRAINING, self::CLOSED => false,
        };
    }

    /**
     * 判断是否可以发送流数据
     */
    public function canSendStreamData(): bool
    {
        return self::CONNECTED === $this;
    }

    /**
     * 判断是否可以创建新流
     */
    public function canCreateStream(): bool
    {
        return self::CONNECTED === $this;
    }

    /**
     * 获取可转换的状态列表
     *
     * @return array<self>
     */
    public function getValidTransitions(): array
    {
        return match ($this) {
            self::NEW => [self::HANDSHAKING, self::CLOSED],
            self::HANDSHAKING => [self::CONNECTED, self::CLOSING, self::CLOSED],
            self::CONNECTED => [self::CLOSING, self::DRAINING, self::CLOSED],
            self::CLOSING => [self::DRAINING, self::CLOSED],
            self::DRAINING => [self::CLOSED],
            self::CLOSED => [],
        };
    }

    /**
     * 判断是否可以转换到目标状态
     */
    public function canTransitionTo(self $target): bool
    {
        return in_array($target, $this->getValidTransitions(), true);
    }

    /**
     * 获取状态描述（与getLabel相同）
     */
    public function getDescription(): string
    {
        return $this->getLabel();
    }
}
