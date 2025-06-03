<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Tests;

use PHPUnit\Framework\TestCase;
use Tourze\QUIC\Core\Constants;

/**
 * Constants 常量类单元测试
 * 
 * @covers \Tourze\QUIC\Core\Constants
 */
class ConstantsTest extends TestCase
{
    /**
     * 测试协议版本常量
     */
    public function testVersionConstants(): void
    {
        $this->assertSame(0x00000001, Constants::VERSION_1);
        $this->assertSame(0xff00001d, Constants::VERSION_DRAFT_29);
    }

    /**
     * 测试包大小限制常量
     */
    public function testPacketSizeConstants(): void
    {
        $this->assertSame(1200, Constants::MIN_INITIAL_PACKET_SIZE);
        $this->assertSame(65535, Constants::MAX_PACKET_SIZE);
        $this->assertSame(1472, Constants::MAX_UDP_PAYLOAD_SIZE);
    }

    /**
     * 测试连接ID常量
     */
    public function testConnectionIdConstants(): void
    {
        $this->assertSame(0, Constants::MIN_CONNECTION_ID_LENGTH);
        $this->assertSame(20, Constants::MAX_CONNECTION_ID_LENGTH);
        $this->assertSame(8, Constants::DEFAULT_CONNECTION_ID_LENGTH);
    }

    /**
     * 测试超时设置常量
     */
    public function testTimeoutConstants(): void
    {
        $this->assertSame(30000, Constants::DEFAULT_IDLE_TIMEOUT);
        $this->assertSame(25, Constants::DEFAULT_MAX_ACK_DELAY);
        $this->assertSame(10000, Constants::DEFAULT_HANDSHAKE_TIMEOUT);
        $this->assertSame(333, Constants::DEFAULT_INITIAL_RTT);
    }

    /**
     * 测试流控制常量
     */
    public function testFlowControlConstants(): void
    {
        $this->assertSame(1048576, Constants::DEFAULT_MAX_DATA);
        $this->assertSame(262144, Constants::DEFAULT_MAX_STREAM_DATA);
        $this->assertSame(100, Constants::DEFAULT_MAX_STREAMS_BIDI);
        $this->assertSame(100, Constants::DEFAULT_MAX_STREAMS_UNI);
    }

    /**
     * 测试变长整数编码常量
     */
    public function testVarintConstants(): void
    {
        $this->assertSame(63, Constants::VARINT_MAX_1_BYTE);
        $this->assertSame(16383, Constants::VARINT_MAX_2_BYTE);
        $this->assertSame(1073741823, Constants::VARINT_MAX_4_BYTE);
        $this->assertSame(4611686018427387903, Constants::VARINT_MAX_8_BYTE);
    }

    /**
     * 测试帧大小限制常量
     */
    public function testFrameSizeConstants(): void
    {
        $this->assertSame(0x3FFFFFFF, Constants::MAX_FRAME_SIZE);
        $this->assertSame(0x3FFFFFFFFFFFFFFF, Constants::MAX_STREAM_ID);
    }

    /**
     * 测试加密级别常量
     */
    public function testEncryptionLevelConstants(): void
    {
        $this->assertSame(0, Constants::ENCRYPTION_LEVEL_INITIAL);
        $this->assertSame(1, Constants::ENCRYPTION_LEVEL_EARLY_DATA);
        $this->assertSame(2, Constants::ENCRYPTION_LEVEL_HANDSHAKE);
        $this->assertSame(3, Constants::ENCRYPTION_LEVEL_APPLICATION);
    }

    /**
     * 测试传输参数ID常量
     */
    public function testTransportParameterConstants(): void
    {
        $this->assertSame(0x00, Constants::TRANSPORT_PARAM_ORIGINAL_DESTINATION_CONNECTION_ID);
        $this->assertSame(0x01, Constants::TRANSPORT_PARAM_MAX_IDLE_TIMEOUT);
        $this->assertSame(0x02, Constants::TRANSPORT_PARAM_STATELESS_RESET_TOKEN);
        $this->assertSame(0x03, Constants::TRANSPORT_PARAM_MAX_UDP_PAYLOAD_SIZE);
        $this->assertSame(0x04, Constants::TRANSPORT_PARAM_INITIAL_MAX_DATA);
        $this->assertSame(0x05, Constants::TRANSPORT_PARAM_INITIAL_MAX_STREAM_DATA_BIDI_LOCAL);
        $this->assertSame(0x06, Constants::TRANSPORT_PARAM_INITIAL_MAX_STREAM_DATA_BIDI_REMOTE);
        $this->assertSame(0x07, Constants::TRANSPORT_PARAM_INITIAL_MAX_STREAM_DATA_UNI);
        $this->assertSame(0x08, Constants::TRANSPORT_PARAM_INITIAL_MAX_STREAMS_BIDI);
        $this->assertSame(0x09, Constants::TRANSPORT_PARAM_INITIAL_MAX_STREAMS_UNI);
        $this->assertSame(0x0a, Constants::TRANSPORT_PARAM_ACK_DELAY_EXPONENT);
        $this->assertSame(0x0b, Constants::TRANSPORT_PARAM_MAX_ACK_DELAY);
        $this->assertSame(0x0c, Constants::TRANSPORT_PARAM_DISABLE_ACTIVE_MIGRATION);
        $this->assertSame(0x0d, Constants::TRANSPORT_PARAM_PREFERRED_ADDRESS);
        $this->assertSame(0x0e, Constants::TRANSPORT_PARAM_ACTIVE_CONNECTION_ID_LIMIT);
        $this->assertSame(0x0f, Constants::TRANSPORT_PARAM_INITIAL_SOURCE_CONNECTION_ID);
        $this->assertSame(0x10, Constants::TRANSPORT_PARAM_RETRY_SOURCE_CONNECTION_ID);
    }

    /**
     * 测试默认传输参数值常量
     */
    public function testDefaultTransportParameterConstants(): void
    {
        $this->assertSame(3, Constants::DEFAULT_ACK_DELAY_EXPONENT);
        $this->assertSame(65527, Constants::DEFAULT_MAX_UDP_PAYLOAD_SIZE);
        $this->assertSame(2, Constants::DEFAULT_ACTIVE_CONNECTION_ID_LIMIT);
    }

    /**
     * 测试重传相关常量
     */
    public function testRetransmissionConstants(): void
    {
        $this->assertSame(333, Constants::INITIAL_RTT);
        $this->assertSame(1, Constants::GRANULARITY);
        $this->assertSame(1, Constants::TIMER_GRANULARITY);
        $this->assertSame(3, Constants::INITIAL_PACKET_THRESHOLD);
        $this->assertSame(9.0 / 8.0, Constants::INITIAL_TIME_THRESHOLD);
    }

    /**
     * 测试拥塞控制常量
     */
    public function testCongestionControlConstants(): void
    {
        $this->assertSame(10, Constants::INITIAL_WINDOW);
        $this->assertSame(2, Constants::MINIMUM_WINDOW);
        $this->assertSame(0.5, Constants::LOSS_REDUCTION_FACTOR);
    }

    /**
     * 测试路径验证常量
     */
    public function testPathValidationConstants(): void
    {
        $this->assertSame(8, Constants::PATH_CHALLENGE_SIZE);
        $this->assertSame(8, Constants::PATH_RESPONSE_SIZE);
    }

    /**
     * 测试ECN常量
     */
    public function testECNConstants(): void
    {
        $this->assertSame(0x00, Constants::ECN_NOT_ECT);
        $this->assertSame(0x01, Constants::ECN_ECT_1);
        $this->assertSame(0x02, Constants::ECN_ECT_0);
        $this->assertSame(0x03, Constants::ECN_CE);
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
        
        $this->assertIsArray($params);
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