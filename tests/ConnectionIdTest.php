<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tourze\QUIC\Core\ConnectionId;
use Tourze\QUIC\Core\Constants;

/**
 * ConnectionId 单元测试
 * 
 * @covers \Tourze\QUIC\Core\ConnectionId
 */
class ConnectionIdTest extends TestCase
{
    /**
     * 测试生成默认长度的连接ID
     */
    public function testGenerateDefault(): void
    {
        $connectionId = ConnectionId::generate();
        $this->assertSame(Constants::DEFAULT_CONNECTION_ID_LENGTH, strlen($connectionId));
    }

    /**
     * 测试生成指定长度的连接ID
     */
    public function testGenerateWithLength(): void
    {
        for ($length = 0; $length <= 20; $length++) {
            $connectionId = ConnectionId::generate($length);
            $this->assertSame($length, strlen($connectionId));
        }
    }

    /**
     * 测试生成长度超出范围的连接ID
     */
    public function testGenerateInvalidLength(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('连接ID长度必须在');
        ConnectionId::generate(21);
    }

    /**
     * 测试生成长度为负数的连接ID
     */
    public function testGenerateNegativeLength(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('连接ID长度必须在');
        ConnectionId::generate(-1);
    }

    /**
     * 测试验证连接ID
     */
    public function testValidate(): void
    {
        // 有效的连接ID
        $this->assertTrue(ConnectionId::validate(''));
        $this->assertTrue(ConnectionId::validate('abcd'));
        $this->assertTrue(ConnectionId::validate(str_repeat('a', 20)));

        // 无效的连接ID
        $this->assertFalse(ConnectionId::validate(str_repeat('a', 21)));
    }

    /**
     * 测试生成随机长度的连接ID
     */
    public function testRandomGeneration(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $connectionId = ConnectionId::random(4, 16);
            $length = strlen($connectionId);
            $this->assertGreaterThanOrEqual(4, $length);
            $this->assertLessThanOrEqual(16, $length);
        }
    }

    /**
     * 测试随机生成参数错误
     */
    public function testRandomInvalidParameters(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('最小长度不能大于最大长度');
        ConnectionId::random(10, 5);
    }

    /**
     * 测试连接ID比较
     */
    public function testEquals(): void
    {
        $id1 = 'test1234';
        $id2 = 'test1234';
        $id3 = 'different';

        $this->assertTrue(ConnectionId::equals($id1, $id2));
        $this->assertFalse(ConnectionId::equals($id1, $id3));
    }

    /**
     * 测试转换为十六进制
     */
    public function testToHex(): void
    {
        $this->assertSame('', ConnectionId::toHex(''));
        $this->assertSame('61626364', ConnectionId::toHex('abcd'));
        $this->assertSame('00ff', ConnectionId::toHex("\x00\xFF"));
    }

    /**
     * 测试从十六进制创建
     */
    public function testFromHex(): void
    {
        $this->assertSame('', ConnectionId::fromHex(''));
        $this->assertSame('abcd', ConnectionId::fromHex('61626364'));
        $this->assertSame("\x00\xFF", ConnectionId::fromHex('00ff'));
    }

    /**
     * 测试从无效十六进制创建
     */
    public function testFromInvalidHex(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('无效的十六进制字符串');
        ConnectionId::fromHex('xyz');
    }

    /**
     * 测试从奇数长度十六进制创建
     */
    public function testFromOddLengthHex(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('十六进制字符串长度必须为偶数');
        ConnectionId::fromHex('abc');
    }

    /**
     * 测试获取长度
     */
    public function testGetLength(): void
    {
        $this->assertSame(0, ConnectionId::getLength(''));
        $this->assertSame(4, ConnectionId::getLength('test'));
        $this->assertSame(8, ConnectionId::getLength('12345678'));
    }

    /**
     * 测试判断是否为空
     */
    public function testIsEmpty(): void
    {
        $this->assertTrue(ConnectionId::isEmpty(''));
        $this->assertFalse(ConnectionId::isEmpty('test'));
    }

    /**
     * 测试toString方法
     */
    public function testToString(): void
    {
        $this->assertSame('[empty]', ConnectionId::toString(''));
        $this->assertSame('[4] 74657374', ConnectionId::toString('test'));
    }

    /**
     * 测试批量生成
     */
    public function testGenerateMultiple(): void
    {
        $connectionIds = ConnectionId::generateMultiple(5, 8);
        $this->assertCount(5, $connectionIds);
        
        foreach ($connectionIds as $connectionId) {
            $this->assertSame(8, strlen($connectionId));
        }
    }

    /**
     * 测试生成数量为负数
     */
    public function testGenerateMultipleNegativeCount(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('生成数量不能为负数');
        ConnectionId::generateMultiple(-1);
    }

    /**
     * 测试验证序列号
     */
    public function testIsValidSequenceNumber(): void
    {
        $this->assertTrue(ConnectionId::isValidSequenceNumber(0));
        $this->assertTrue(ConnectionId::isValidSequenceNumber(100));
        $this->assertTrue(ConnectionId::isValidSequenceNumber(Constants::VARINT_MAX_8_BYTE));
        $this->assertFalse(ConnectionId::isValidSequenceNumber(-1));
    }

    /**
     * 测试生成重置令牌
     */
    public function testGenerateResetToken(): void
    {
        $connectionId = 'test1234';
        $secret = str_repeat('a', 16);
        $token = ConnectionId::generateResetToken($connectionId, $secret);
        
        $this->assertSame(16, strlen($token));
    }

    /**
     * 测试生成重置令牌密钥长度错误
     */
    public function testGenerateResetTokenInvalidSecret(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('密钥长度必须为16字节');
        ConnectionId::generateResetToken('test', 'short');
    }

    /**
     * 测试验证重置令牌
     */
    public function testVerifyResetToken(): void
    {
        $connectionId = 'test1234';
        $secret = str_repeat('a', 16);
        $token = ConnectionId::generateResetToken($connectionId, $secret);
        
        $this->assertTrue(ConnectionId::verifyResetToken($connectionId, $token, $secret));
        $this->assertFalse(ConnectionId::verifyResetToken($connectionId, $token, str_repeat('b', 16)));
        $this->assertFalse(ConnectionId::verifyResetToken($connectionId, 'invalid', $secret));
    }

    /**
     * 测试验证错误长度的重置令牌
     */
    public function testVerifyResetTokenInvalidLength(): void
    {
        $connectionId = 'test1234';
        $secret = str_repeat('a', 16);
        
        $this->assertFalse(ConnectionId::verifyResetToken($connectionId, 'short', $secret));
    }

    /**
     * 测试往返转换（十六进制）
     */
    public function testHexRoundTrip(): void
    {
        $testValues = ['', 'a', 'test', "\x00\xFF\x7F", str_repeat('x', 20)];
        
        foreach ($testValues as $value) {
            $hex = ConnectionId::toHex($value);
            $decoded = ConnectionId::fromHex($hex);
            $this->assertSame($value, $decoded);
        }
    }
} 