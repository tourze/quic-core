<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core;

use Tourze\QUIC\Core\Exception\ConnectionIdException;

/**
 * QUIC连接ID生成器和工具类
 *
 * 负责生成、验证和管理QUIC连接ID
 * 连接ID长度范围：0-20字节
 * 参考：https://tools.ietf.org/html/rfc9000#section-5.1
 */
final class ConnectionId
{
    /**
     * 生成指定长度的连接ID
     *
     * @param int $length 连接ID长度，范围0-20字节
     * @return string 生成的连接ID
     * @throws ConnectionIdException 当长度超出范围时抛出异常
     */
    public static function generate(int $length = Constants::DEFAULT_CONNECTION_ID_LENGTH): string
    {
        if ($length < Constants::MIN_CONNECTION_ID_LENGTH || $length > Constants::MAX_CONNECTION_ID_LENGTH) {
            throw ConnectionIdException::invalidLength($length, Constants::MIN_CONNECTION_ID_LENGTH, Constants::MAX_CONNECTION_ID_LENGTH);
        }

        if ($length === 0) {
            return '';
        }

        return random_bytes($length);
    }

    /**
     * 验证连接ID是否合法
     *
     * @param string $connectionId 要验证的连接ID
     * @return bool 是否合法
     */
    public static function validate(string $connectionId): bool
    {
        $length = strlen($connectionId);
        return $length <= Constants::MAX_CONNECTION_ID_LENGTH;
    }

    /**
     * 生成随机长度的连接ID
     *
     * @param int $minLength 最小长度，默认为4
     * @param int $maxLength 最大长度，默认为20
     * @return string 生成的连接ID
     * @throws ConnectionIdException 当长度参数无效时抛出异常
     */
    public static function random(int $minLength = 4, int $maxLength = Constants::MAX_CONNECTION_ID_LENGTH): string
    {
        if ($minLength < Constants::MIN_CONNECTION_ID_LENGTH || $minLength > Constants::MAX_CONNECTION_ID_LENGTH) {
            throw ConnectionIdException::minLengthOutOfRange($minLength);
        }

        if ($maxLength < Constants::MIN_CONNECTION_ID_LENGTH || $maxLength > Constants::MAX_CONNECTION_ID_LENGTH) {
            throw ConnectionIdException::maxLengthOutOfRange($maxLength);
        }

        if ($minLength > $maxLength) {
            throw ConnectionIdException::minGreaterThanMax($minLength, $maxLength);
        }

        $length = random_int($minLength, $maxLength);
        return self::generate($length);
    }

    /**
     * 比较两个连接ID是否相等
     *
     * @param string $connectionId1 连接ID1
     * @param string $connectionId2 连接ID2
     * @return bool 是否相等
     */
    public static function equals(string $connectionId1, string $connectionId2): bool
    {
        return hash_equals($connectionId1, $connectionId2);
    }

    /**
     * 将连接ID转换为十六进制字符串表示
     *
     * @param string $connectionId 连接ID
     * @return string 十六进制字符串
     */
    public static function toHex(string $connectionId): string
    {
        if ($connectionId === '') {
            return '';
        }

        return bin2hex($connectionId);
    }

    /**
     * 从十六进制字符串创建连接ID
     *
     * @param string $hex 十六进制字符串
     * @return string 连接ID
     * @throws ConnectionIdException 当十六进制字符串无效时抛出异常
     */
    public static function fromHex(string $hex): string
    {
        if ($hex === '') {
            return '';
        }

        if (!ctype_xdigit($hex)) {
            throw ConnectionIdException::invalidHexString($hex);
        }

        if (strlen($hex) % 2 !== 0) {
            throw ConnectionIdException::hexLengthMustBeEven($hex);
        }

        $connectionId = hex2bin($hex);
        if ($connectionId === false) {
            throw ConnectionIdException::cannotParseHexString($hex);
        }

        if (!self::validate($connectionId)) {
            throw ConnectionIdException::generatedConnectionIdOutOfRange();
        }

        return $connectionId;
    }

    /**
     * 获取连接ID的长度
     *
     * @param string $connectionId 连接ID
     * @return int 长度
     */
    public static function getLength(string $connectionId): int
    {
        return strlen($connectionId);
    }

    /**
     * 判断连接ID是否为空
     *
     * @param string $connectionId 连接ID
     * @return bool 是否为空
     */
    public static function isEmpty(string $connectionId): bool
    {
        return $connectionId === '';
    }

    /**
     * 生成用于调试的连接ID字符串表示
     *
     * @param string $connectionId 连接ID
     * @return string 调试字符串
     */
    public static function toString(string $connectionId): string
    {
        if ($connectionId === '') {
            return '[empty]';
        }

        $hex = self::toHex($connectionId);
        $length = strlen($connectionId);
        return "[{$length}] {$hex}";
    }

    /**
     * 批量生成多个连接ID
     *
     * @param int $count 生成数量
     * @param int $length 每个连接ID的长度
     * @return array<string> 连接ID数组
     */
    public static function generateMultiple(int $count, int $length = Constants::DEFAULT_CONNECTION_ID_LENGTH): array
    {
        if ($count < 0) {
            throw ConnectionIdException::countCannotBeNegative($count);
        }

        $connectionIds = [];
        for ($i = 0; $i < $count; $i++) {
            $connectionIds[] = self::generate($length);
        }

        return $connectionIds;
    }

    /**
     * 验证连接ID序列号（用于NEW_CONNECTION_ID帧）
     *
     * @param int $sequenceNumber 序列号
     * @return bool 是否有效
     */
    public static function isValidSequenceNumber(int $sequenceNumber): bool
    {
        return $sequenceNumber >= 0 && $sequenceNumber <= Constants::VARINT_MAX_8_BYTE;
    }

    /**
     * 生成状态重置令牌（与连接ID关联）
     *
     * @param string $connectionId 连接ID
     * @param string $secret 密钥（16字节）
     * @return string 16字节的重置令牌
     * @throws ConnectionIdException 当密钥长度不正确时抛出异常
     */
    public static function generateResetToken(string $connectionId, string $secret): string
    {
        if (strlen($secret) !== 16) {
            throw ConnectionIdException::invalidSecretLength(strlen($secret));
        }

        // 使用HMAC-SHA256生成重置令牌，截取前16字节
        $hmac = hash_hmac('sha256', $connectionId, $secret, true);
        return substr($hmac, 0, 16);
    }

    /**
     * 验证重置令牌
     *
     * @param string $connectionId 连接ID
     * @param string $token 重置令牌
     * @param string $secret 密钥
     * @return bool 令牌是否有效
     */
    public static function verifyResetToken(string $connectionId, string $token, string $secret): bool
    {
        if (strlen($token) !== 16) {
            return false;
        }

        try {
            $expectedToken = self::generateResetToken($connectionId, $secret);
            return hash_equals($expectedToken, $token);
        } catch (ConnectionIdException) {
            return false;
        }
    }
} 