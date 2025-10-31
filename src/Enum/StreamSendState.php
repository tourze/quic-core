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
 * QUIC流发送状态枚举
 *
 * 定义流发送端的状态机
 * 参考：https://tools.ietf.org/html/rfc9000#section-3.1
 *
 * @implements Arrayable<string, string>
 */
enum StreamSendState: string implements Labelable, Itemable, Selectable, Arrayable
{
    use ItemTrait;
    use SelectTrait;

    case READY = 'ready';
    case SEND = 'send';
    case DATA_SENT = 'data_sent';
    case RESET_SENT = 'reset_sent';
    case RESET_RECVD = 'reset_recvd';

    public function getLabel(): string
    {
        return match ($this) {
            self::READY => '准备发送',
            self::SEND => '正在发送',
            self::DATA_SENT => '数据已发送',
            self::RESET_SENT => '已发送重置',
            self::RESET_RECVD => '已收到重置确认',
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
            self::READY, self::SEND => true,
            self::DATA_SENT, self::RESET_SENT, self::RESET_RECVD => false,
        };
    }

    /**
     * 判断是否已重置
     */
    public function isReset(): bool
    {
        return match ($this) {
            self::RESET_SENT, self::RESET_RECVD => true,
            self::READY, self::SEND, self::DATA_SENT => false,
        };
    }

    /**
     * 判断是否为终止状态
     */
    public function isTerminal(): bool
    {
        return match ($this) {
            self::DATA_SENT, self::RESET_RECVD => true,
            self::READY, self::SEND, self::RESET_SENT => false,
        };
    }

    /**
     * 获取状态描述
     */
    public function getDescription(): string
    {
        return match ($this) {
            self::READY => '准备发送',
            self::SEND => '正在发送',
            self::DATA_SENT => '数据已发送',
            self::RESET_SENT => '已发送重置',
            self::RESET_RECVD => '已收到重置确认',
        };
    }
}
