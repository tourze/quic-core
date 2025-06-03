<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Enum;

/**
 * QUIC流接收状态枚举
 * 
 * 定义流接收端的状态机
 * 参考：https://tools.ietf.org/html/rfc9000#section-3.2
 */
enum StreamRecvState: string
{
    case RECV = 'recv';                 // 可以接收数据
    case SIZE_KNOWN = 'size_known';     // 已知最终大小
    case DATA_RECVD = 'data_recvd';     // 已接收所有数据
    case RESET_RECVD = 'reset_recvd';   // 收到 RESET_STREAM
    case RESET_READ = 'reset_read';     // 应用已读取 RESET

    /**
     * 判断是否可以接收数据
     */
    public function canReceiveData(): bool
    {
        return match($this) {
            self::RECV, self::SIZE_KNOWN => true,
            default => false
        };
    }

    /**
     * 判断是否已重置
     */
    public function isReset(): bool
    {
        return in_array($this, [
            self::RESET_RECVD,
            self::RESET_READ
        ]);
    }

    /**
     * 判断是否为终止状态
     */
    public function isTerminal(): bool
    {
        return in_array($this, [
            self::DATA_RECVD,
            self::RESET_READ
        ]);
    }

    /**
     * 获取状态的中文描述
     */
    public function getDescription(): string
    {
        return match($this) {
            self::RECV => '可以接收数据',
            self::SIZE_KNOWN => '已知最终大小',
            self::DATA_RECVD => '已接收所有数据',
            self::RESET_RECVD => '收到重置流',
            self::RESET_READ => '应用已读取重置',
        };
    }
}
