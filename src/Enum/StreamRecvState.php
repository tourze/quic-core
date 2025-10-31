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
 * QUIC流接收状态枚举
 *
 * 定义流接收端的状态机
 * 参考：https://tools.ietf.org/html/rfc9000#section-3.2
 *
 * @implements Arrayable<string, string>
 */
enum StreamRecvState: string implements Labelable, Itemable, Selectable, Arrayable
{
    use ItemTrait;
    use SelectTrait;

    case RECV = 'recv';
    case SIZE_KNOWN = 'size_known';
    case DATA_RECVD = 'data_recvd';
    case RESET_RECVD = 'reset_recvd';
    case RESET_READ = 'reset_read';

    public function getLabel(): string
    {
        return match ($this) {
            self::RECV => '可以接收数据',
            self::SIZE_KNOWN => '已知最终大小',
            self::DATA_RECVD => '已接收所有数据',
            self::RESET_RECVD => '收到重置流',
            self::RESET_READ => '应用已读取重置',
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
     * 判断是否可以接收数据
     */
    public function canReceiveData(): bool
    {
        return match ($this) {
            self::RECV, self::SIZE_KNOWN => true,
            self::DATA_RECVD, self::RESET_RECVD, self::RESET_READ => false,
        };
    }

    /**
     * 判断是否已重置
     */
    public function isReset(): bool
    {
        return match ($this) {
            self::RESET_RECVD, self::RESET_READ => true,
            self::RECV, self::SIZE_KNOWN, self::DATA_RECVD => false,
        };
    }

    /**
     * 判断是否为终止状态
     */
    public function isTerminal(): bool
    {
        return match ($this) {
            self::DATA_RECVD, self::RESET_READ => true,
            self::RECV, self::SIZE_KNOWN, self::RESET_RECVD => false,
        };
    }

    /**
     * 获取状态描述
     */
    public function getDescription(): string
    {
        return match ($this) {
            self::RECV => '可以接收数据',
            self::SIZE_KNOWN => '已知最终大小',
            self::DATA_RECVD => '已接收所有数据',
            self::RESET_RECVD => '收到重置流',
            self::RESET_READ => '应用已读取重置',
        };
    }
}
