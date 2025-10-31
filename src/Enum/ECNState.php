<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

/**
 * ECN（Explicit Congestion Notification）状态枚举
 *
 * 定义ECN功能的验证状态
 * 参考：https://tools.ietf.org/html/rfc9000#section-13.4
 */
enum ECNState: string implements Labelable, Itemable, Selectable
{
    use ItemTrait;
    use SelectTrait;

    case TESTING = 'testing';
    case UNKNOWN = 'unknown';
    case CAPABLE = 'capable';
    case FAILED = 'failed';

    public function getLabel(): string
    {
        return match ($this) {
            self::TESTING => '测试中',
            self::UNKNOWN => '未知',
            self::CAPABLE => '支持',
            self::FAILED => '失败',
        };
    }

    /**
     * 检查ECN是否可用
     */
    public function isCapable(): bool
    {
        return self::CAPABLE === $this;
    }

    /**
     * 检查是否正在测试ECN
     */
    public function isTesting(): bool
    {
        return self::TESTING === $this;
    }

    /**
     * 检查ECN是否失败
     */
    public function isFailed(): bool
    {
        return self::FAILED === $this;
    }

    /**
     * 获取状态描述
     */
    public function getDescription(): string
    {
        return match ($this) {
            self::TESTING => '正在测试ECN',
            self::UNKNOWN => 'ECN状态未知',
            self::CAPABLE => 'ECN功能可用',
            self::FAILED => 'ECN功能不可用',
        };
    }
}
