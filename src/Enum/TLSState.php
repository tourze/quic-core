<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Enum;

use Tourze\Arrayable\Arrayable;
use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

/**
 * TLS连接状态枚举
 *
 * 定义TLS握手过程中的状态
 *
 * @implements Arrayable<string, string>
 */
enum TLSState: string implements Labelable, Itemable, Selectable, Arrayable
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

    public function getLabel(): string
    {
        return match ($this) {
            self::START => '开始',
            self::WAIT_CLIENT_HELLO => '等待客户端 Hello',
            self::WAIT_SERVER_HELLO => '等待服务端 Hello',
            self::WAIT_ENCRYPTED_EXTENSIONS => '等待加密扩展',
            self::WAIT_CERTIFICATE => '等待证书',
            self::WAIT_CERTIFICATE_VERIFY => '等待证书验证',
            self::WAIT_FINISHED => '等待完成',
            self::CONNECTED => '已连接',
            self::CLOSED => '已关闭',
        };
    }

    /**
     * 获取所有枚举的选项数组（用于下拉列表等）
     *
     * @return array<int, array{value: string, label: string}>
     */
    public static function toSelectItems(): array
    {
        $result = [];
        foreach (self::cases() as $case) {
            $result[] = [
                'value' => $case->value,
                'label' => $case->getLabel(),
            ];
        }

        return $result;
    }

    /**
     * 判断是否在握手过程中
     */
    public function isHandshaking(): bool
    {
        return match ($this) {
            self::WAIT_CLIENT_HELLO, self::WAIT_SERVER_HELLO,
            self::WAIT_ENCRYPTED_EXTENSIONS, self::WAIT_CERTIFICATE,
            self::WAIT_CERTIFICATE_VERIFY, self::WAIT_FINISHED => true,
            self::START, self::CONNECTED, self::CLOSED => false,
        };
    }

    /**
     * 判断是否已连接
     */
    public function isConnected(): bool
    {
        return self::CONNECTED === $this;
    }

    /**
     * 判断是否已关闭
     */
    public function isClosed(): bool
    {
        return self::CLOSED === $this;
    }

    /**
     * 获取状态描述
     */
    public function getDescription(): string
    {
        return match ($this) {
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
}
