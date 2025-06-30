<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Exception;

use InvalidArgumentException;

/**
 * 变长整数异常类
 *
 * 用于处理QUIC变长整数编解码过程中的参数错误
 */
class VariableIntegerException extends InvalidArgumentException
{
    /**
     * 创建变长整数不能为负数异常
     */
    public static function cannotBeNegative(int $value): self
    {
        return new self("变长整数不能为负数: {$value}");
    }

    /**
     * 创建变长整数超出最大值异常
     */
    public static function exceedsMaxValue(int $value): self
    {
        return new self("变长整数超出最大值: {$value}");
    }

    /**
     * 创建偏移量不能为负数异常
     */
    public static function offsetCannotBeNegative(int $offset): self
    {
        return new self("偏移量不能为负数: {$offset}");
    }

    /**
     * 创建偏移量超出数据范围异常
     */
    public static function offsetOutOfRange(int $offset, int $length): self
    {
        return new self("偏移量超出数据范围: {$offset} >= {$length}");
    }

    /**
     * 创建数据不足异常
     */
    public static function insufficientData(string $message): self
    {
        return new self($message);
    }

    /**
     * 创建无效编码类型异常
     */
    public static function invalidEncodingType(int $encodingType): self
    {
        return new self("无效的变长整数编码类型: {$encodingType}");
    }
} 