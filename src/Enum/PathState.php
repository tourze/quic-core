<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

/**
 * 路径状态枚举
 *
 * 定义QUIC连接路径的验证状态
 * 参考：https://tools.ietf.org/html/rfc9000#section-8.2
 */
enum PathState: string implements Itemable, Labelable, Selectable
{
    use ItemTrait;
    use SelectTrait;
    case PROBING = 'probing';         // 正在探测路径
    case VALIDATING = 'validating';   // 正在验证路径
    case VALIDATED = 'validated';     // 路径已验证
    case ACTIVE = 'active';           // 路径激活中

    /**
     * 判断路径是否已验证
     */
    public function isValidated(): bool
    {
        return in_array($this, [
            self::VALIDATED,
            self::ACTIVE
        ]);
    }

    /**
     * 判断路径是否正在验证过程中
     */
    public function isValidating(): bool
    {
        return in_array($this, [
            self::PROBING,
            self::VALIDATING
        ]);
    }

    /**
     * 判断路径是否激活
     */
    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }

    /**
     * 获取状态的中文描述
     */
    public function getDescription(): string
    {
        return match($this) {
            self::PROBING => '正在探测路径',
            self::VALIDATING => '正在验证路径',
            self::VALIDATED => '路径已验证',
            self::ACTIVE => '路径已激活',
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