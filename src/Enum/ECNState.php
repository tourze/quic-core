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
enum ECNState: string implements Itemable, Labelable, Selectable
{
    use ItemTrait;
    use SelectTrait;
    case TESTING = 'testing';     // 正在测试ECN功能
    case UNKNOWN = 'unknown';     // ECN状态未知
    case CAPABLE = 'capable';     // ECN功能可用
    case FAILED = 'failed';       // ECN功能不可用

    /**
     * 判断是否支持ECN
     */
    public function isCapable(): bool
    {
        return $this === self::CAPABLE;
    }

    /**
     * 判断是否正在测试
     */
    public function isTesting(): bool
    {
        return $this === self::TESTING;
    }

    /**
     * 判断ECN是否失败
     */
    public function isFailed(): bool
    {
        return $this === self::FAILED;
    }

    /**
     * 获取状态的中文描述
     */
    public function getDescription(): string
    {
        return match($this) {
            self::TESTING => '正在测试ECN',
            self::UNKNOWN => 'ECN状态未知',
            self::CAPABLE => 'ECN功能可用',
            self::FAILED => 'ECN功能不可用',
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
