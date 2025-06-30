<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

/**
 * QUIC帧类型枚举
 *
 * 根据RFC 9000定义的所有QUIC帧类型
 * 参考：https://tools.ietf.org/html/rfc9000#section-12.4
 */
enum FrameType: int implements Itemable, Labelable, Selectable
{
    use ItemTrait;
    use SelectTrait;
    case PADDING = 0x00;
    case PING = 0x01;
    case ACK = 0x02;
    case ACK_ECN = 0x03;
    case RESET_STREAM = 0x04;
    case STOP_SENDING = 0x05;
    case CRYPTO = 0x06;
    case NEW_TOKEN = 0x07;
    case STREAM = 0x08;
    case STREAM_FIN = 0x09;
    case STREAM_LEN = 0x0A;
    case STREAM_LEN_FIN = 0x0B;
    case STREAM_OFF = 0x0C;
    case STREAM_OFF_FIN = 0x0D;
    case STREAM_OFF_LEN = 0x0E;
    case STREAM_OFF_LEN_FIN = 0x0F;
    case MAX_DATA = 0x10;
    case MAX_STREAM_DATA = 0x11;
    case MAX_STREAMS = 0x12;
    case MAX_STREAMS_UNI = 0x13;
    case DATA_BLOCKED = 0x14;
    case STREAM_DATA_BLOCKED = 0x15;
    case STREAMS_BLOCKED = 0x16;
    case STREAMS_BLOCKED_UNI = 0x17;
    case NEW_CONNECTION_ID = 0x18;
    case RETIRE_CONNECTION_ID = 0x19;
    case PATH_CHALLENGE = 0x1A;
    case PATH_RESPONSE = 0x1B;
    case CONNECTION_CLOSE = 0x1C;
    case CONNECTION_CLOSE_APP = 0x1D;
    case HANDSHAKE_DONE = 0x1E;
    case PONG = 0x1F;
    case PREFERRED_ADDRESS = 0x20;

    /**
     * 判断是否为流帧
     */
    public function isStreamFrame(): bool
    {
        return $this->value >= 0x08 && $this->value <= 0x0F;
    }

    /**
     * 判断帧类型是否被流量控制
     */
    public function isFlowControlled(): bool
    {
        return in_array($this, [
            self::STREAM,
            self::STREAM_FIN,
            self::STREAM_LEN,
            self::STREAM_LEN_FIN,
            self::STREAM_OFF,
            self::STREAM_OFF_FIN,
            self::STREAM_OFF_LEN,
            self::STREAM_OFF_LEN_FIN,
            self::CRYPTO,
        ]);
    }

    /**
     * 判断是否需要可靠传输
     */
    public function needsReliableDelivery(): bool
    {
        return !in_array($this, [
            self::PADDING,
            self::PING,
            self::ACK,
            self::ACK_ECN,
            self::PATH_CHALLENGE,
            self::PATH_RESPONSE,
        ]);
    }

    /**
     * 判断是否为流控制帧
     */
    public function isFlowControl(): bool
    {
        return in_array($this, [
            self::MAX_DATA,
            self::MAX_STREAM_DATA,
            self::MAX_STREAMS,
            self::MAX_STREAMS_UNI,
            self::DATA_BLOCKED,
            self::STREAM_DATA_BLOCKED,
            self::STREAMS_BLOCKED,
            self::STREAMS_BLOCKED_UNI,
        ]);
    }

    /**
     * 判断是否为连接管理帧
     */
    public function isConnectionManagement(): bool
    {
        return in_array($this, [
            self::NEW_CONNECTION_ID,
            self::RETIRE_CONNECTION_ID,
            self::PATH_CHALLENGE,
            self::PATH_RESPONSE,
            self::CONNECTION_CLOSE,
            self::CONNECTION_CLOSE_APP,
            self::HANDSHAKE_DONE,
        ]);
    }

    /**
     * 判断是否为确认帧
     */
    public function isAckFrame(): bool
    {
        return in_array($this, [
            self::ACK,
            self::ACK_ECN,
        ]);
    }

    /**
     * 判断是否包含偏移量字段
     */
    public function hasOffset(): bool
    {
        return $this->isStreamFrame() && ($this->value & 0x04) !== 0;
    }

    /**
     * 判断是否包含长度字段
     */
    public function hasLength(): bool
    {
        return $this->isStreamFrame() && ($this->value & 0x02) !== 0;
    }

    /**
     * 判断是否设置了FIN标志
     */
    public function hasFin(): bool
    {
        return $this->isStreamFrame() && ($this->value & 0x01) !== 0;
    }

    /**
     * 判断是否可以在Initial包中发送
     */
    public function allowedInInitial(): bool
    {
        return in_array($this, [
            self::PADDING,
            self::PING,
            self::ACK,
            self::ACK_ECN,
            self::CRYPTO,
            self::CONNECTION_CLOSE,
        ]);
    }

    /**
     * 判断是否可以在Handshake包中发送
     */
    public function allowedInHandshake(): bool
    {
        return in_array($this, [
            self::PADDING,
            self::PING,
            self::ACK,
            self::ACK_ECN,
            self::CRYPTO,
            self::CONNECTION_CLOSE,
        ]);
    }

    /**
     * 判断是否可以在1-RTT包中发送
     */
    public function allowedIn1RTT(): bool
    {
        return true; // 1-RTT包可以包含所有帧类型
    }

    /**
     * 获取帧类型的描述
     */
    public function getDescription(): string
    {
        return match($this) {
            self::PADDING => '填充帧',
            self::PING => 'PING帧',
            self::ACK => '确认帧',
            self::ACK_ECN => 'ECN确认帧',
            self::RESET_STREAM => '重置流帧',
            self::STOP_SENDING => '停止发送帧',
            self::CRYPTO => '加密帧',
            self::NEW_TOKEN => '新令牌帧',
            self::STREAM, self::STREAM_FIN, self::STREAM_LEN, self::STREAM_LEN_FIN,
            self::STREAM_OFF, self::STREAM_OFF_FIN, self::STREAM_OFF_LEN, self::STREAM_OFF_LEN_FIN => '流帧',
            self::MAX_DATA => '最大数据帧',
            self::MAX_STREAM_DATA => '最大流数据帧',
            self::MAX_STREAMS => '最大流数量帧',
            self::MAX_STREAMS_UNI => '最大单向流数量帧',
            self::DATA_BLOCKED => '数据阻塞帧',
            self::STREAM_DATA_BLOCKED => '流数据阻塞帧',
            self::STREAMS_BLOCKED => '流阻塞帧',
            self::STREAMS_BLOCKED_UNI => '单向流阻塞帧',
            self::NEW_CONNECTION_ID => '新连接ID帧',
            self::RETIRE_CONNECTION_ID => '退役连接ID帧',
            self::PATH_CHALLENGE => '路径挑战帧',
            self::PATH_RESPONSE => '路径响应帧',
            self::CONNECTION_CLOSE => '连接关闭帧',
            self::CONNECTION_CLOSE_APP => '应用连接关闭帧',
            self::HANDSHAKE_DONE => '握手完成帧',
            self::PONG => 'PONG帧',
            self::PREFERRED_ADDRESS => '首选地址帧',
        };
    }

    /**
     * 获取标签 (EnumExtra 接口要求)
     */
    public function getLabel(): string
    {
        return $this->getDescription();
    }
}
