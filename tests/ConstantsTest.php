<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\QUIC\Core\Constants;

/**
 * Constants 常量类单元测试
 *
 * @internal
 */
#[CoversClass(Constants::class)]
final class ConstantsTest extends TestCase
{
    /**
     * 测试协议版本常量
     */
    public function testVersionConstants(): void
    {
        // 测试版本1为正式版本（版本号应大于0）
        $this->assertGreaterThan(0, Constants::VERSION_1);
        // 测试草案版本远大于正式版本
        $this->assertGreaterThan(Constants::VERSION_1 * 1000, Constants::VERSION_DRAFT_29);
        // 测试草案版本包含草案标识符（高字节非零）
        $this->assertGreaterThan(0x00FFFFFF, Constants::VERSION_DRAFT_29);
    }

    /**
     * 测试包大小限制常量
     */
    public function testPacketSizeConstants(): void
    {
        // 测试最小初始包大小符合RFC要求（至少1200字节）
        $this->assertGreaterThanOrEqual(1200, Constants::MIN_INITIAL_PACKET_SIZE);
        // 测试最大包大小不超过16位无符号整数
        $this->assertLessThanOrEqual(65535, Constants::MAX_PACKET_SIZE);
        // 测试UDP最大载荷小于最大包大小
        $this->assertLessThan(Constants::MAX_PACKET_SIZE, Constants::MAX_UDP_PAYLOAD_SIZE);
    }

    /**
     * 测试连接ID常量
     */
    public function testConnectionIdConstants(): void
    {
        // 测试最小长度允许为0
        $this->assertGreaterThanOrEqual(0, Constants::MIN_CONNECTION_ID_LENGTH);
        // 测试最大长度符合RFC限制（不超过20字节）
        $this->assertLessThanOrEqual(20, Constants::MAX_CONNECTION_ID_LENGTH);
        // 测试默认长度在有效范围内
        $this->assertGreaterThanOrEqual(Constants::MIN_CONNECTION_ID_LENGTH, Constants::DEFAULT_CONNECTION_ID_LENGTH);
        $this->assertLessThanOrEqual(Constants::MAX_CONNECTION_ID_LENGTH, Constants::DEFAULT_CONNECTION_ID_LENGTH);
    }

    /**
     * 测试超时设置常量的合理性
     */
    public function testTimeoutConstants(): void
    {
        // 超时值应该为正数且在合理范围内
        $this->assertGreaterThan(0, Constants::DEFAULT_IDLE_TIMEOUT);
        $this->assertGreaterThan(0, Constants::DEFAULT_MAX_ACK_DELAY);
        $this->assertGreaterThan(0, Constants::DEFAULT_HANDSHAKE_TIMEOUT);
        $this->assertGreaterThan(0, Constants::DEFAULT_INITIAL_RTT);

        // 握手超时应小于空闲超时
        $this->assertLessThan(Constants::DEFAULT_IDLE_TIMEOUT, Constants::DEFAULT_HANDSHAKE_TIMEOUT);
    }

    /**
     * 测试流控制常量的合理性
     */
    public function testFlowControlConstants(): void
    {
        // 所有流控制值应为正数
        $this->assertGreaterThan(0, Constants::DEFAULT_MAX_DATA);
        $this->assertGreaterThan(0, Constants::DEFAULT_MAX_STREAM_DATA);
        $this->assertGreaterThan(0, Constants::DEFAULT_MAX_STREAMS_BIDI);
        $this->assertGreaterThan(0, Constants::DEFAULT_MAX_STREAMS_UNI);

        // 总数据限制应大于单流数据限制
        $this->assertGreaterThan(Constants::DEFAULT_MAX_STREAM_DATA, Constants::DEFAULT_MAX_DATA);
    }

    /**
     * 测试变长整数编码常量的二进制特性
     */
    public function testVarintConstants(): void
    {
        // 测试各字节数的最大值的位数特性
        $this->assertSame(6, (int) log(Constants::VARINT_MAX_1_BYTE + 1, 2));          // 2^6 - 1
        $this->assertSame(14, (int) log(Constants::VARINT_MAX_2_BYTE + 1, 2));         // 2^14 - 1
        $this->assertSame(30, (int) log(Constants::VARINT_MAX_4_BYTE + 1, 2));         // 2^30 - 1
        $this->assertSame(62, (int) log(Constants::VARINT_MAX_8_BYTE + 1, 2));         // 2^62 - 1

        // 验证递增关系 (1字节 < 2字节 < 4字节 < 8字节)
        $sizes = [
            Constants::VARINT_MAX_1_BYTE,
            Constants::VARINT_MAX_2_BYTE,
            Constants::VARINT_MAX_4_BYTE,
            Constants::VARINT_MAX_8_BYTE,
        ];

        // 验证数组是否是递增排序的
        $this->assertSame($sizes, array_values(array_unique($sizes)));
        $sortedSizes = $sizes;
        sort($sortedSizes);
        $this->assertSame($sortedSizes, $sizes);
    }

    /**
     * 测试帧大小限制常量
     */
    public function testFrameSizeConstants(): void
    {
        // 帧大小限制应为正数
        $this->assertGreaterThan(0, Constants::MAX_FRAME_SIZE);
        $this->assertGreaterThan(0, Constants::MAX_STREAM_ID);

        // 流ID的范围应该更大（62位 vs 30位）
        $this->assertGreaterThan(Constants::MAX_FRAME_SIZE, Constants::MAX_STREAM_ID);
    }

    /**
     * 测试加密级别常量的递增性
     */
    public function testEncryptionLevelConstants(): void
    {
        // 加密级别应该是递增的 (0, 1, 2, 3)
        $levels = [
            Constants::ENCRYPTION_LEVEL_INITIAL,
            Constants::ENCRYPTION_LEVEL_EARLY_DATA,
            Constants::ENCRYPTION_LEVEL_HANDSHAKE,
            Constants::ENCRYPTION_LEVEL_APPLICATION,
        ];

        // 验证每个级别都不同且递增
        $this->assertSame(count($levels), count(array_unique($levels)));
        $sortedLevels = $levels;
        sort($sortedLevels);
        $this->assertSame($sortedLevels, $levels);
    }

    /**
     * 测试传输参数ID常量的连续性
     */
    public function testTransportParameterConstants(): void
    {
        // 验证传输参数ID是连续的（从0x00到0x10）
        $paramIds = [
            Constants::TRANSPORT_PARAM_ORIGINAL_DESTINATION_CONNECTION_ID,
            Constants::TRANSPORT_PARAM_MAX_IDLE_TIMEOUT,
            Constants::TRANSPORT_PARAM_STATELESS_RESET_TOKEN,
            Constants::TRANSPORT_PARAM_MAX_UDP_PAYLOAD_SIZE,
            Constants::TRANSPORT_PARAM_INITIAL_MAX_DATA,
            Constants::TRANSPORT_PARAM_INITIAL_MAX_STREAM_DATA_BIDI_LOCAL,
            Constants::TRANSPORT_PARAM_INITIAL_MAX_STREAM_DATA_BIDI_REMOTE,
            Constants::TRANSPORT_PARAM_INITIAL_MAX_STREAM_DATA_UNI,
            Constants::TRANSPORT_PARAM_INITIAL_MAX_STREAMS_BIDI,
            Constants::TRANSPORT_PARAM_INITIAL_MAX_STREAMS_UNI,
            Constants::TRANSPORT_PARAM_ACK_DELAY_EXPONENT,
            Constants::TRANSPORT_PARAM_MAX_ACK_DELAY,
            Constants::TRANSPORT_PARAM_DISABLE_ACTIVE_MIGRATION,
            Constants::TRANSPORT_PARAM_PREFERRED_ADDRESS,
            Constants::TRANSPORT_PARAM_ACTIVE_CONNECTION_ID_LIMIT,
            Constants::TRANSPORT_PARAM_INITIAL_SOURCE_CONNECTION_ID,
            Constants::TRANSPORT_PARAM_RETRY_SOURCE_CONNECTION_ID,
        ];

        // 验证ID是从0开始连续递增的
        foreach ($paramIds as $index => $paramId) {
            $this->assertSame($index, $paramId);
        }
    }

    /**
     * 测试默认传输参数值的合理性
     */
    public function testDefaultTransportParameterConstants(): void
    {
        // ACK延迟指数应该在合理范围内
        $this->assertGreaterThanOrEqual(0, Constants::DEFAULT_ACK_DELAY_EXPONENT);
        $this->assertLessThanOrEqual(20, Constants::DEFAULT_ACK_DELAY_EXPONENT);

        // UDP载荷大小应该小于最大包大小
        $this->assertLessThan(Constants::MAX_PACKET_SIZE, Constants::DEFAULT_MAX_UDP_PAYLOAD_SIZE);

        // 连接ID限制应该为正数
        $this->assertGreaterThan(0, Constants::DEFAULT_ACTIVE_CONNECTION_ID_LIMIT);
    }

    /**
     * 测试ECN常量的二进制属性
     */
    public function testECNConstants(): void
    {
        // ECN标志应该使用最低2位
        $this->assertLessThanOrEqual(3, Constants::ECN_NOT_ECT);
        $this->assertLessThanOrEqual(3, Constants::ECN_ECT_1);
        $this->assertLessThanOrEqual(3, Constants::ECN_ECT_0);
        $this->assertLessThanOrEqual(3, Constants::ECN_CE);

        // ECN值应该互不相同
        $ecnValues = [
            Constants::ECN_NOT_ECT,
            Constants::ECN_ECT_1,
            Constants::ECN_ECT_0,
            Constants::ECN_CE,
        ];
        $this->assertSame(count($ecnValues), count(array_unique($ecnValues)));
    }

    /**
     * 测试版本字符串获取
     */
    public function testGetVersionString(): void
    {
        $this->assertSame('QUIC v1', Constants::getVersionString(Constants::VERSION_1));
        $this->assertSame('QUIC Draft 29', Constants::getVersionString(Constants::VERSION_DRAFT_29));
        $this->assertSame('Unknown Version (0x12345678)', Constants::getVersionString(0x12345678));
    }

    /**
     * 测试版本支持判断
     */
    public function testIsSupportedVersion(): void
    {
        $this->assertTrue(Constants::isSupportedVersion(Constants::VERSION_1));
        $this->assertTrue(Constants::isSupportedVersion(Constants::VERSION_DRAFT_29));
        $this->assertFalse(Constants::isSupportedVersion(0x12345678));
        $this->assertFalse(Constants::isSupportedVersion(0));
    }

    /**
     * 测试默认传输参数获取
     */
    public function testGetDefaultTransportParameters(): void
    {
        $params = Constants::getDefaultTransportParameters();
        $this->assertArrayHasKey('max_idle_timeout', $params);
        $this->assertArrayHasKey('max_udp_payload_size', $params);
        $this->assertArrayHasKey('initial_max_data', $params);
        $this->assertArrayHasKey('initial_max_stream_data_bidi_local', $params);
        $this->assertArrayHasKey('initial_max_stream_data_bidi_remote', $params);
        $this->assertArrayHasKey('initial_max_stream_data_uni', $params);
        $this->assertArrayHasKey('initial_max_streams_bidi', $params);
        $this->assertArrayHasKey('initial_max_streams_uni', $params);
        $this->assertArrayHasKey('ack_delay_exponent', $params);
        $this->assertArrayHasKey('max_ack_delay', $params);
        $this->assertArrayHasKey('active_connection_id_limit', $params);

        // 验证值
        $this->assertSame(Constants::DEFAULT_IDLE_TIMEOUT, $params['max_idle_timeout']);
        $this->assertSame(Constants::DEFAULT_MAX_UDP_PAYLOAD_SIZE, $params['max_udp_payload_size']);
        $this->assertSame(Constants::DEFAULT_MAX_DATA, $params['initial_max_data']);
        $this->assertSame(Constants::DEFAULT_MAX_STREAM_DATA, $params['initial_max_stream_data_bidi_local']);
        $this->assertSame(Constants::DEFAULT_MAX_STREAM_DATA, $params['initial_max_stream_data_bidi_remote']);
        $this->assertSame(Constants::DEFAULT_MAX_STREAM_DATA, $params['initial_max_stream_data_uni']);
        $this->assertSame(Constants::DEFAULT_MAX_STREAMS_BIDI, $params['initial_max_streams_bidi']);
        $this->assertSame(Constants::DEFAULT_MAX_STREAMS_UNI, $params['initial_max_streams_uni']);
        $this->assertSame(Constants::DEFAULT_ACK_DELAY_EXPONENT, $params['ack_delay_exponent']);
        $this->assertSame(Constants::DEFAULT_MAX_ACK_DELAY, $params['max_ack_delay']);
        $this->assertSame(Constants::DEFAULT_ACTIVE_CONNECTION_ID_LIMIT, $params['active_connection_id_limit']);
    }
}
