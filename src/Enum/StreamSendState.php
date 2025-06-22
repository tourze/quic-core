<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

/**
 * QUIC流发送状态枚举
 * 
 * 定义流发送端的状态机
 * 参考：https://tools.ietf.org/html/rfc9000#section-3.1
 */
enum StreamSendState: string implements Itemable, Labelable, Selectable
{
    use ItemTrait;
    use SelectTrait;
    case READY = 'ready';              // 准备发送
    case SEND = 'send';                // 正在发送
    case DATA_SENT = 'data_sent';      // 数据已发送
    case RESET_SENT = 'reset_sent';    // 已发送重置
    case RESET_RECVD = 'reset_recvd';  // 已收到重置确认

    /**
     * 判断是否可以发送数据
     */
    public function canSendData(): bool
    {
        return match($this) {
            self::READY, self::SEND, self::DATA_SENT => true,
            default => false
        };
    }

    /**
     * 判断是否已重置
     */
    public function isReset(): bool
    {
        return in_array($this, [
            self::RESET_SENT,
            self::RESET_RECVD
        ]);
    }

    /**
     * 判断是否为终止状态
     */
    public function isTerminal(): bool
    {
        return in_array($this, [
            self::DATA_SENT,
            self::RESET_RECVD
        ]);
    }

    /**
     * 获取状态的中文描述
     */
    public function getDescription(): string
    {
        return match($this) {
            self::READY => '准备发送',
            self::SEND => '正在发送',
            self::DATA_SENT => '数据已发送',
            self::RESET_SENT => '已发送重置',
            self::RESET_RECVD => '已收到重置确认',
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
