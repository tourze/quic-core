<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

/**
 * TLS连接状态枚举
 *
 * 定义TLS握手过程中的状态
 */
enum TLSState: string implements Itemable, Labelable, Selectable
{
    use ItemTrait;
    use SelectTrait;
    case START = 'start';
    case WAIT_CLIENT_HELLO = 'wait_client_hello';
    case WAIT_SERVER_HELLO = 'wait_server_hello';
    case WAIT_ENCRYPTED_EXTENSIONS = 'wait_encrypted_extensions';
    case WAIT_CERTIFICATE = 'wait_certificate';
    case WAIT_CERTIFICATE_VERIFY = 'wait_certificate_verify';
    case WAIT_FINISHED = 'wait_finished';
    case CONNECTED = 'connected';
    case CLOSED = 'closed';

    /**
     * 判断是否正在握手
     */
    public function isHandshaking(): bool
    {
        return !in_array($this, [
            self::START,
            self::CONNECTED,
            self::CLOSED
        ]);
    }

    /**
     * 判断是否已连接
     */
    public function isConnected(): bool
    {
        return $this === self::CONNECTED;
    }

    /**
     * 判断是否已关闭
     */
    public function isClosed(): bool
    {
        return $this === self::CLOSED;
    }

    /**
     * 获取状态的中文描述
     */
    public function getDescription(): string
    {
        return match($this) {
            self::START => '开始',
            self::WAIT_CLIENT_HELLO => '等待客户端Hello',
            self::WAIT_SERVER_HELLO => '等待服务器Hello',
            self::WAIT_ENCRYPTED_EXTENSIONS => '等待加密扩展',
            self::WAIT_CERTIFICATE => '等待证书',
            self::WAIT_CERTIFICATE_VERIFY => '等待证书验证',
            self::WAIT_FINISHED => '等待完成',
            self::CONNECTED => '已连接',
            self::CLOSED => '已关闭',
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
