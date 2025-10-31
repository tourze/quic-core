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
 * 路径状态枚举
 *
 * 定义QUIC连接路径的验证状态
 * 参考：https://tools.ietf.org/html/rfc9000#section-8.2
 *
 * @implements Arrayable<string, string>
 */
enum PathState: string implements Labelable, Itemable, Selectable, Arrayable
{
    use ItemTrait;
    use SelectTrait;

    case PROBING = 'probing';
    case VALIDATING = 'validating';
    case VALIDATED = 'validated';
    case ACTIVE = 'active';

    public function getLabel(): string
    {
        return match ($this) {
            self::PROBING => '正在探测路径',
            self::VALIDATING => '正在验证路径',
            self::VALIDATED => '路径已验证',
            self::ACTIVE => '路径已激活',
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
     * 判断路径是否已验证
     */
    public function isValidated(): bool
    {
        return match ($this) {
            self::VALIDATED, self::ACTIVE => true,
            self::PROBING, self::VALIDATING => false,
        };
    }

    /**
     * 判断是否正在验证过程中
     */
    public function isValidating(): bool
    {
        return match ($this) {
            self::PROBING, self::VALIDATING => true,
            self::VALIDATED, self::ACTIVE => false,
        };
    }

    /**
     * 判断路径是否激活
     */
    public function isActive(): bool
    {
        return self::ACTIVE === $this;
    }

    /**
     * 获取详细描述
     */
    public function getDescription(): string
    {
        return match ($this) {
            self::PROBING => '正在探测路径',
            self::VALIDATING => '正在验证路径',
            self::VALIDATED => '路径已验证',
            self::ACTIVE => '路径已激活',
        };
    }
}
