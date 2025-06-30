<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core;

/**
 * QUIC协议常量定义
 *
 * 包含所有RFC 9000定义的协议常量和默认值
 * 参考：https://tools.ietf.org/html/rfc9000
 */
final class Constants
{
    // 协议版本
    public const VERSION_1 = 0x00000001;
    public const VERSION_DRAFT_29 = 0xff00001d;

    // 包大小限制
    public const MIN_INITIAL_PACKET_SIZE = 1200;
    public const MAX_PACKET_SIZE = 65535;
    public const MAX_UDP_PAYLOAD_SIZE = 1472; // 典型以太网MTU - IP头 - UDP头

    // 连接ID
    public const MIN_CONNECTION_ID_LENGTH = 0;
    public const MAX_CONNECTION_ID_LENGTH = 20;
    public const DEFAULT_CONNECTION_ID_LENGTH = 8;

    // 超时设置 (毫秒)
    public const DEFAULT_IDLE_TIMEOUT = 30000;
    public const DEFAULT_MAX_ACK_DELAY = 25;
    public const DEFAULT_HANDSHAKE_TIMEOUT = 10000;
    public const DEFAULT_INITIAL_RTT = 333; // 333ms

    // 流控制
    public const DEFAULT_MAX_DATA = 1048576; // 1MB
    public const DEFAULT_MAX_STREAM_DATA = 262144; // 256KB
    public const DEFAULT_MAX_STREAMS_BIDI = 100;
    public const DEFAULT_MAX_STREAMS_UNI = 100;

    // 变长整数编码
    public const VARINT_MAX_1_BYTE = 63;
    public const VARINT_MAX_2_BYTE = 16383;
    public const VARINT_MAX_4_BYTE = 1073741823;
    public const VARINT_MAX_8_BYTE = 4611686018427387903;

    // 帧大小限制
    public const MAX_FRAME_SIZE = 0x3FFFFFFF; // 2^30 - 1
    public const MAX_STREAM_ID = 0x3FFFFFFFFFFFFFFF; // 2^62 - 1

    // 加密级别
    public const ENCRYPTION_LEVEL_INITIAL = 0;
    public const ENCRYPTION_LEVEL_EARLY_DATA = 1;
    public const ENCRYPTION_LEVEL_HANDSHAKE = 2;
    public const ENCRYPTION_LEVEL_APPLICATION = 3;

    // 传输参数ID
    public const TRANSPORT_PARAM_ORIGINAL_DESTINATION_CONNECTION_ID = 0x00;
    public const TRANSPORT_PARAM_MAX_IDLE_TIMEOUT = 0x01;
    public const TRANSPORT_PARAM_STATELESS_RESET_TOKEN = 0x02;
    public const TRANSPORT_PARAM_MAX_UDP_PAYLOAD_SIZE = 0x03;
    public const TRANSPORT_PARAM_INITIAL_MAX_DATA = 0x04;
    public const TRANSPORT_PARAM_INITIAL_MAX_STREAM_DATA_BIDI_LOCAL = 0x05;
    public const TRANSPORT_PARAM_INITIAL_MAX_STREAM_DATA_BIDI_REMOTE = 0x06;
    public const TRANSPORT_PARAM_INITIAL_MAX_STREAM_DATA_UNI = 0x07;
    public const TRANSPORT_PARAM_INITIAL_MAX_STREAMS_BIDI = 0x08;
    public const TRANSPORT_PARAM_INITIAL_MAX_STREAMS_UNI = 0x09;
    public const TRANSPORT_PARAM_ACK_DELAY_EXPONENT = 0x0a;
    public const TRANSPORT_PARAM_MAX_ACK_DELAY = 0x0b;
    public const TRANSPORT_PARAM_DISABLE_ACTIVE_MIGRATION = 0x0c;
    public const TRANSPORT_PARAM_PREFERRED_ADDRESS = 0x0d;
    public const TRANSPORT_PARAM_ACTIVE_CONNECTION_ID_LIMIT = 0x0e;
    public const TRANSPORT_PARAM_INITIAL_SOURCE_CONNECTION_ID = 0x0f;
    public const TRANSPORT_PARAM_RETRY_SOURCE_CONNECTION_ID = 0x10;

    // 默认传输参数值
    public const DEFAULT_ACK_DELAY_EXPONENT = 3;
    public const DEFAULT_MAX_UDP_PAYLOAD_SIZE = 65527;
    public const DEFAULT_ACTIVE_CONNECTION_ID_LIMIT = 2;

    // 重传相关
    public const INITIAL_RTT = 333; // ms
    public const GRANULARITY = 1; // ms
    public const TIMER_GRANULARITY = 1; // ms
    public const INITIAL_PACKET_THRESHOLD = 3;
    public const INITIAL_TIME_THRESHOLD = 9.0 / 8.0; // 1.125

    // 拥塞控制
    public const INITIAL_WINDOW = 10;
    public const MINIMUM_WINDOW = 2;
    public const LOSS_REDUCTION_FACTOR = 0.5;

    // 路径验证
    public const PATH_CHALLENGE_SIZE = 8;
    public const PATH_RESPONSE_SIZE = 8;

    // 连接迁移
    public const DEFAULT_CONNECTION_MIGRATION_DISABLED = false;

    // ECN (Explicit Congestion Notification)
    public const ECN_NOT_ECT = 0x00;
    public const ECN_ECT_1 = 0x01;
    public const ECN_ECT_0 = 0x02;
    public const ECN_CE = 0x03;

    /**
     * 获取版本字符串
     */
    public static function getVersionString(int $version): string
    {
        return match ($version) {
            self::VERSION_1 => 'QUIC v1',
            self::VERSION_DRAFT_29 => 'QUIC Draft 29',
            default => sprintf('Unknown Version (0x%08x)', $version),
        };
    }

    /**
     * 判断是否为支持的版本
     */
    public static function isSupportedVersion(int $version): bool
    {
        return in_array($version, [
            self::VERSION_1,
            self::VERSION_DRAFT_29,
        ]);
    }

    // 错误码定义 (RFC 9000 Section 20)
    public const ERROR_NO_ERROR = 0x00;
    public const ERROR_INTERNAL_ERROR = 0x01;
    public const ERROR_CONNECTION_REFUSED = 0x02;
    public const ERROR_FLOW_CONTROL_ERROR = 0x03;
    public const ERROR_STREAM_LIMIT_ERROR = 0x04;
    public const ERROR_STREAM_STATE_ERROR = 0x05;
    public const ERROR_FINAL_SIZE_ERROR = 0x06;
    public const ERROR_FRAME_ENCODING_ERROR = 0x07;
    public const ERROR_TRANSPORT_PARAMETER_ERROR = 0x08;
    public const ERROR_CONNECTION_ID_LIMIT_ERROR = 0x09;
    public const ERROR_PROTOCOL_VIOLATION = 0x0A;
    public const ERROR_INVALID_TOKEN = 0x0B;
    public const ERROR_APPLICATION_ERROR = 0x0C;
    public const ERROR_CRYPTO_BUFFER_EXCEEDED = 0x0D;
    public const ERROR_KEY_UPDATE_ERROR = 0x0E;
    public const ERROR_AEAD_LIMIT_REACHED = 0x0F;
    public const ERROR_NO_VIABLE_PATH = 0x10;

    /**
     * 获取支持的版本列表
     */
    public static function getSupportedVersions(): array
    {
        return [
            self::VERSION_1,
            self::VERSION_DRAFT_29,
        ];
    }

    /**
     * 获取默认的传输参数
     */
    public static function getDefaultTransportParameters(): array
    {
        return [
            'max_idle_timeout' => self::DEFAULT_IDLE_TIMEOUT,
            'max_udp_payload_size' => self::DEFAULT_MAX_UDP_PAYLOAD_SIZE,
            'initial_max_data' => self::DEFAULT_MAX_DATA,
            'initial_max_stream_data_bidi_local' => self::DEFAULT_MAX_STREAM_DATA,
            'initial_max_stream_data_bidi_remote' => self::DEFAULT_MAX_STREAM_DATA,
            'initial_max_stream_data_uni' => self::DEFAULT_MAX_STREAM_DATA,
            'initial_max_streams_bidi' => self::DEFAULT_MAX_STREAMS_BIDI,
            'initial_max_streams_uni' => self::DEFAULT_MAX_STREAMS_UNI,
            'ack_delay_exponent' => self::DEFAULT_ACK_DELAY_EXPONENT,
            'max_ack_delay' => self::DEFAULT_MAX_ACK_DELAY,
            'active_connection_id_limit' => self::DEFAULT_ACTIVE_CONNECTION_ID_LIMIT,
        ];
    }
} 