<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tourze\QUIC\Core\Constants;
use Tourze\QUIC\Core\VariableInteger;

/**
 * VariableInteger 单元测试
 * 
 * @covers \Tourze\QUIC\Core\VariableInteger
 */
class VariableIntegerTest extends TestCase
{
    /**
     * 测试1字节编码
     */
    public function testEncode1Byte(): void
    {
        // 测试边界值
        $this->assertSame("\x00", VariableInteger::encode(0));
        $this->assertSame("\x3F", VariableInteger::encode(63));
        
        // 测试中间值
        $this->assertSame("\x20", VariableInteger::encode(32));
    }

    /**
     * 测试2字节编码
     */
    public function testEncode2Byte(): void
    {
        // 测试边界值
        $this->assertSame("\x40\x40", VariableInteger::encode(64));
        $this->assertSame("\x7F\xFF", VariableInteger::encode(16383));
        
        // 测试中间值
        $this->assertSame("\x44\x00", VariableInteger::encode(1024));
    }

    /**
     * 测试4字节编码
     */
    public function testEncode4Byte(): void
    {
        // 测试边界值
        $this->assertSame("\x80\x00\x40\x00", VariableInteger::encode(16384));
        $this->assertSame("\xBF\xFF\xFF\xFF", VariableInteger::encode(1073741823));
        
        // 测试中间值
        $this->assertSame("\x80\x10\x00\x00", VariableInteger::encode(1048576));
    }

    /**
     * 测试8字节编码
     */
    public function testEncode8Byte(): void
    {
        // 测试边界值
        $this->assertSame("\xC0\x00\x00\x00\x40\x00\x00\x00", VariableInteger::encode(1073741824));
        $this->assertSame("\xFF\xFF\xFF\xFF\xFF\xFF\xFF\xFF", VariableInteger::encode(Constants::VARINT_MAX_8_BYTE));
    }

    /**
     * 测试编码无效值
     */
    public function testEncodeInvalidValues(): void
    {
        // 测试负数
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('变长整数不能为负数');
        VariableInteger::encode(-1);
    }

    /**
     * 测试编码超出范围的值
     */
    public function testEncodeOutOfRange(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('变长整数超出最大值');
        VariableInteger::encode(Constants::VARINT_MAX_8_BYTE + 1);
    }

    /**
     * 测试1字节解码
     */
    public function testDecode1Byte(): void
    {
        $this->assertSame([0, 1], VariableInteger::decode("\x00"));
        $this->assertSame([63, 1], VariableInteger::decode("\x3F"));
        $this->assertSame([32, 1], VariableInteger::decode("\x20"));
    }

    /**
     * 测试2字节解码
     */
    public function testDecode2Byte(): void
    {
        $this->assertSame([64, 2], VariableInteger::decode("\x40\x40"));
        $this->assertSame([16383, 2], VariableInteger::decode("\x7F\xFF"));
        $this->assertSame([1024, 2], VariableInteger::decode("\x44\x00"));
    }

    /**
     * 测试4字节解码
     */
    public function testDecode4Byte(): void
    {
        $this->assertSame([16384, 4], VariableInteger::decode("\x80\x00\x40\x00"));
        $this->assertSame([1073741823, 4], VariableInteger::decode("\xBF\xFF\xFF\xFF"));
        $this->assertSame([1048576, 4], VariableInteger::decode("\x80\x10\x00\x00"));
    }

    /**
     * 测试8字节解码
     */
    public function testDecode8Byte(): void
    {
        $this->assertSame([1073741824, 8], VariableInteger::decode("\xC0\x00\x00\x00\x40\x00\x00\x00"));
        $this->assertSame([Constants::VARINT_MAX_8_BYTE, 8], VariableInteger::decode("\xFF\xFF\xFF\xFF\xFF\xFF\xFF\xFF"));
    }

    /**
     * 测试带偏移量的解码
     */
    public function testDecodeWithOffset(): void
    {
        $data = "\xFF\x20\x44\x00";
        $this->assertSame([32, 1], VariableInteger::decode($data, 1));
        $this->assertSame([1024, 2], VariableInteger::decode($data, 2));
    }

    /**
     * 测试解码数据不足
     */
    public function testDecodeInsufficientData(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('数据不足，无法解码2字节变长整数');
        VariableInteger::decode("\x40");
    }

    /**
     * 测试解码偏移量超出范围
     */
    public function testDecodeOffsetOutOfRange(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('偏移量超出数据范围');
        VariableInteger::decode("\x20", 10);
    }

    /**
     * 测试解码负偏移量
     */
    public function testDecodeNegativeOffset(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('偏移量不能为负数');
        VariableInteger::decode("\x20", -1);
    }

    /**
     * 测试获取编码长度
     */
    public function testGetLength(): void
    {
        $this->assertSame(1, VariableInteger::getLength(0));
        $this->assertSame(1, VariableInteger::getLength(63));
        $this->assertSame(2, VariableInteger::getLength(64));
        $this->assertSame(2, VariableInteger::getLength(16383));
        $this->assertSame(4, VariableInteger::getLength(16384));
        $this->assertSame(4, VariableInteger::getLength(1073741823));
        $this->assertSame(8, VariableInteger::getLength(1073741824));
        $this->assertSame(8, VariableInteger::getLength(Constants::VARINT_MAX_8_BYTE));
    }

    /**
     * 测试获取无效值的编码长度
     */
    public function testGetLengthInvalidValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('变长整数不能为负数');
        VariableInteger::getLength(-1);
    }

    /**
     * 测试检查完整的变长整数
     */
    public function testHasCompleteVarint(): void
    {
        // 1字节编码
        $this->assertTrue(VariableInteger::hasCompleteVarint("\x20"));
        $this->assertTrue(VariableInteger::hasCompleteVarint("\x20\x00", 0));
        $this->assertFalse(VariableInteger::hasCompleteVarint("", 0));
        
        // 2字节编码
        $this->assertTrue(VariableInteger::hasCompleteVarint("\x40\x40"));
        $this->assertFalse(VariableInteger::hasCompleteVarint("\x40"));
        
        // 4字节编码
        $this->assertTrue(VariableInteger::hasCompleteVarint("\x80\x00\x00\x00"));
        $this->assertFalse(VariableInteger::hasCompleteVarint("\x80\x00\x00"));
        
        // 8字节编码
        $this->assertTrue(VariableInteger::hasCompleteVarint("\xC0\x00\x00\x00\x00\x00\x00\x00"));
        $this->assertFalse(VariableInteger::hasCompleteVarint("\xC0\x00\x00\x00\x00"));
    }

    /**
     * 测试获取编码类型
     */
    public function testGetEncodingType(): void
    {
        $this->assertSame(0, VariableInteger::getEncodingType("\x20"));
        $this->assertSame(1, VariableInteger::getEncodingType("\x40"));
        $this->assertSame(2, VariableInteger::getEncodingType("\x80"));
        $this->assertSame(3, VariableInteger::getEncodingType("\xC0"));
    }

    /**
     * 测试批量编码
     */
    public function testEncodeMultiple(): void
    {
        $values = [0, 64, 16384, 1073741824];
        $expected = "\x00\x40\x40\x80\x00\x40\x00\xC0\x00\x00\x00\x40\x00\x00\x00";
        $this->assertSame($expected, VariableInteger::encodeMultiple($values));
    }

    /**
     * 测试批量解码
     */
    public function testDecodeMultiple(): void
    {
        $data = "\x00\x40\x40\x80\x00\x40\x00";
        [$values, $consumed] = VariableInteger::decodeMultiple($data, 3);
        
        $this->assertSame([0, 64, 16384], $values);
        $this->assertSame(7, $consumed);
    }

    /**
     * 测试编码和解码的往返
     */
    public function testRoundTrip(): void
    {
        $testValues = [
            0, 1, 63, 64, 100, 16383, 16384, 50000, 1073741823, 1073741824,
            Constants::VARINT_MAX_8_BYTE
        ];
        
        foreach ($testValues as $value) {
            $encoded = VariableInteger::encode($value);
            [$decoded, $consumed] = VariableInteger::decode($encoded);
            
            $this->assertSame($value, $decoded, "往返测试失败，值: {$value}");
            $this->assertSame(strlen($encoded), $consumed, "消耗字节数不匹配，值: {$value}");
        }
    }

    /**
     * 测试性能（基准测试）
     */
    public function testPerformance(): void
    {
        $iterations = 10000;
        $testValue = 1048576; // 4字节编码的中等值
        
        // 编码性能测试
        $startTime = microtime(true);
        for ($i = 0; $i < $iterations; $i++) {
            VariableInteger::encode($testValue);
        }
        $encodeTime = microtime(true) - $startTime;
        
        // 解码性能测试
        $encoded = VariableInteger::encode($testValue);
        $startTime = microtime(true);
        for ($i = 0; $i < $iterations; $i++) {
            VariableInteger::decode($encoded);
        }
        $decodeTime = microtime(true) - $startTime;
        
        // 确保性能合理（每秒至少10万次操作）
        $this->assertLessThan(0.1, $encodeTime, "编码性能不达标");
        $this->assertLessThan(0.1, $decodeTime, "解码性能不达标");
    }
} 