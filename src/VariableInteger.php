<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core;

use InvalidArgumentException;

/**
 * QUIC变长整数编解码器
 * 
 * 实现RFC 9000 Section 16定义的变长整数编码
 * 支持1、2、4、8字节的变长整数编码和解码
 * 参考：https://tools.ietf.org/html/rfc9000#section-16
 */
final class VariableInteger
{
    /**
     * 编码变长整数
     * 
     * @param int $value 要编码的整数值 (0 <= value <= 2^62-1)
     * @return string 编码后的字节串
     * @throws InvalidArgumentException 当值超出范围时抛出异常
     */
    public static function encode(int $value): string
    {
        if ($value < 0) {
            throw new InvalidArgumentException("变长整数不能为负数: {$value}");
        }

        if ($value > Constants::VARINT_MAX_8_BYTE) {
            throw new InvalidArgumentException("变长整数超出最大值: {$value}");
        }

        // 1字节编码 (0-63)
        if ($value <= Constants::VARINT_MAX_1_BYTE) {
            return chr($value);
        }

        // 2字节编码 (64-16383)
        if ($value <= Constants::VARINT_MAX_2_BYTE) {
            return pack('n', $value | 0x4000);
        }

        // 4字节编码 (16384-1073741823)
        if ($value <= Constants::VARINT_MAX_4_BYTE) {
            return pack('N', $value | 0x80000000);
        }

        // 8字节编码 (1073741824-4611686018427387903)
        $high = ($value >> 32) | 0xC0000000;
        $low = $value & 0xFFFFFFFF;
        return pack('NN', $high, $low);
    }

    /**
     * 解码变长整数
     * 
     * @param string $data 要解码的字节串
     * @param int $offset 解码开始的偏移量
     * @return array{0: int, 1: int} [解码得到的值, 消耗的字节数]
     * @throws InvalidArgumentException 当数据不足或格式错误时抛出异常
     */
    public static function decode(string $data, int $offset = 0): array
    {
        if ($offset < 0) {
            throw new InvalidArgumentException("偏移量不能为负数: {$offset}");
        }

        $length = strlen($data);
        if ($offset >= $length) {
            throw new InvalidArgumentException("偏移量超出数据范围: {$offset} >= {$length}");
        }

        $firstByte = ord($data[$offset]);
        $encodingType = ($firstByte & 0xC0) >> 6;

        switch ($encodingType) {
            case 0: // 1字节编码
                return [$firstByte & 0x3F, 1];

            case 1: // 2字节编码
                if ($offset + 2 > $length) {
                    throw new InvalidArgumentException('数据不足，无法解码2字节变长整数');
                }
                $value = unpack('n', substr($data, $offset, 2))[1];
                return [$value & 0x3FFF, 2];

            case 2: // 4字节编码
                if ($offset + 4 > $length) {
                    throw new InvalidArgumentException('数据不足，无法解码4字节变长整数');
                }
                $value = unpack('N', substr($data, $offset, 4))[1];
                return [$value & 0x3FFFFFFF, 4];

            case 3: // 8字节编码
                if ($offset + 8 > $length) {
                    throw new InvalidArgumentException('数据不足，无法解码8字节变长整数');
                }
                $values = unpack('N2', substr($data, $offset, 8));
                $high = $values[1] & 0x3FFFFFFF;
                $low = $values[2];
                return [($high << 32) | $low, 8];

            default:
                throw new InvalidArgumentException("无效的变长整数编码类型: {$encodingType}");
        }
    }

    /**
     * 获取编码指定值所需的字节数
     * 
     * @param int $value 要编码的值
     * @return int 所需的字节数 (1, 2, 4, 或 8)
     * @throws InvalidArgumentException 当值超出范围时抛出异常
     */
    public static function getLength(int $value): int
    {
        if ($value < 0) {
            throw new InvalidArgumentException("变长整数不能为负数: {$value}");
        }

        if ($value > Constants::VARINT_MAX_8_BYTE) {
            throw new InvalidArgumentException("变长整数超出最大值: {$value}");
        }

        if ($value <= Constants::VARINT_MAX_1_BYTE) {
            return 1;
        }

        if ($value <= Constants::VARINT_MAX_2_BYTE) {
            return 2;
        }

        if ($value <= Constants::VARINT_MAX_4_BYTE) {
            return 4;
        }

        return 8;
    }

    /**
     * 检查数据中是否有完整的变长整数
     * 
     * @param string $data 要检查的数据
     * @param int $offset 检查开始的偏移量
     * @return bool 是否有完整的变长整数
     */
    public static function hasCompleteVarint(string $data, int $offset = 0): bool
    {
        $length = strlen($data);
        if ($offset >= $length) {
            return false;
        }

        $firstByte = ord($data[$offset]);
        $encodingType = ($firstByte & 0xC0) >> 6;

        $requiredLength = match ($encodingType) {
            0 => 1,
            1 => 2,
            2 => 4,
            3 => 8,
        };

        return $offset + $requiredLength <= $length;
    }

    /**
     * 获取变长整数的编码类型
     * 
     * @param string $data 数据
     * @param int $offset 偏移量
     * @return int 编码类型 (0=1字节, 1=2字节, 2=4字节, 3=8字节)
     * @throws InvalidArgumentException 当偏移量超出范围时抛出异常
     */
    public static function getEncodingType(string $data, int $offset = 0): int
    {
        $length = strlen($data);
        if ($offset >= $length) {
            throw new InvalidArgumentException("偏移量超出数据范围: {$offset} >= {$length}");
        }

        $firstByte = ord($data[$offset]);
        return ($firstByte & 0xC0) >> 6;
    }

    /**
     * 批量编码多个变长整数
     * 
     * @param array<int> $values 要编码的值数组
     * @return string 编码后的字节串
     */
    public static function encodeMultiple(array $values): string
    {
        $result = '';
        foreach ($values as $value) {
            $result .= self::encode($value);
        }
        return $result;
    }

    /**
     * 批量解码多个变长整数
     * 
     * @param string $data 要解码的数据
     * @param int $count 要解码的变长整数个数
     * @param int $offset 开始偏移量
     * @return array{0: array<int>, 1: int} [解码得到的值数组, 总消耗字节数]
     */
    public static function decodeMultiple(string $data, int $count, int $offset = 0): array
    {
        $values = [];
        $totalConsumed = 0;

        for ($i = 0; $i < $count; $i++) {
            [$value, $consumed] = self::decode($data, $offset + $totalConsumed);
            $values[] = $value;
            $totalConsumed += $consumed;
        }

        return [$values, $totalConsumed];
    }
} 