<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Exception;

use InvalidArgumentException;

/**
 * 连接ID异常类
 *
 * 用于处理连接ID生成、验证和操作过程中的参数错误
 */
class ConnectionIdException extends InvalidArgumentException
{
    /**
     * 创建长度超出范围异常
     */
    public static function invalidLength(int $length, int $min, int $max): self
    {
        return new self("连接ID长度必须在 {$min}-{$max} 字节范围内，实际长度: {$length}");
    }

    /**
     * 创建最小长度超出范围异常
     */
    public static function minLengthOutOfRange(int $minLength): self
    {
        return new self("最小长度超出范围: {$minLength}");
    }

    /**
     * 创建最大长度超出范围异常
     */
    public static function maxLengthOutOfRange(int $maxLength): self
    {
        return new self("最大长度超出范围: {$maxLength}");
    }

    /**
     * 创建最小长度大于最大长度异常
     */
    public static function minGreaterThanMax(int $minLength, int $maxLength): self
    {
        return new self("最小长度不能大于最大长度: {$minLength} > {$maxLength}");
    }

    /**
     * 创建无效十六进制字符串异常
     */
    public static function invalidHexString(string $hex): self
    {
        return new self("无效的十六进制字符串: {$hex}");
    }

    /**
     * 创建十六进制字符串长度必须为偶数异常
     */
    public static function hexLengthMustBeEven(string $hex): self
    {
        return new self("十六进制字符串长度必须为偶数: {$hex}");
    }

    /**
     * 创建无法解析十六进制字符串异常
     */
    public static function cannotParseHexString(string $hex): self
    {
        return new self("无法解析十六进制字符串: {$hex}");
    }

    /**
     * 创建生成的连接ID长度超出范围异常
     */
    public static function generatedConnectionIdOutOfRange(): self
    {
        return new self("生成的连接ID长度超出范围");
    }

    /**
     * 创建生成数量不能为负数异常
     */
    public static function countCannotBeNegative(int $count): self
    {
        return new self("生成数量不能为负数: {$count}");
    }

    /**
     * 创建序列号超出范围异常
     */
    public static function sequenceNumberOutOfRange(int $sequenceNumber): self
    {
        return new self("序列号超出有效范围: {$sequenceNumber}");
    }

    /**
     * 创建密钥长度不正确异常
     */
    public static function invalidSecretLength(int $length): self
    {
        return new self("密钥长度必须为16字节，实际长度: {$length}");
    }
} 