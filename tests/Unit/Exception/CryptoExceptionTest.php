<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Tests\Unit\Exception;

use PHPUnit\Framework\TestCase;
use Tourze\QUIC\Core\Enum\QuicError;
use Tourze\QUIC\Core\Exception\CryptoException;

class CryptoExceptionTest extends TestCase
{
    public function testBufferExceeded(): void
    {
        $exception = CryptoException::bufferExceeded();
        
        $this->assertInstanceOf(CryptoException::class, $exception);
        $this->assertSame('加密缓冲区溢出', $exception->getMessage());
        $this->assertSame(QuicError::CRYPTO_BUFFER_EXCEEDED->value, $exception->getCode());
    }

    public function testBufferExceededWithDetails(): void
    {
        $exception = CryptoException::bufferExceeded('超过最大限制');
        
        $this->assertInstanceOf(CryptoException::class, $exception);
        $this->assertSame('加密缓冲区溢出: 超过最大限制', $exception->getMessage());
        $this->assertSame(QuicError::CRYPTO_BUFFER_EXCEEDED->value, $exception->getCode());
    }

    public function testKeyUpdateError(): void
    {
        $exception = CryptoException::keyUpdateError();
        
        $this->assertInstanceOf(CryptoException::class, $exception);
        $this->assertSame('密钥更新错误', $exception->getMessage());
        $this->assertSame(QuicError::KEY_UPDATE_ERROR->value, $exception->getCode());
    }

    public function testKeyUpdateErrorWithDetails(): void
    {
        $exception = CryptoException::keyUpdateError('无效的密钥版本');
        
        $this->assertInstanceOf(CryptoException::class, $exception);
        $this->assertSame('密钥更新错误: 无效的密钥版本', $exception->getMessage());
        $this->assertSame(QuicError::KEY_UPDATE_ERROR->value, $exception->getCode());
    }

    public function testAeadLimitReached(): void
    {
        $exception = CryptoException::aeadLimitReached();
        
        $this->assertInstanceOf(CryptoException::class, $exception);
        $this->assertSame('AEAD限制达到', $exception->getMessage());
        $this->assertSame(QuicError::AEAD_LIMIT_REACHED->value, $exception->getCode());
    }

    public function testAeadLimitReachedWithDetails(): void
    {
        $exception = CryptoException::aeadLimitReached('需要密钥轮换');
        
        $this->assertInstanceOf(CryptoException::class, $exception);
        $this->assertSame('AEAD限制达到: 需要密钥轮换', $exception->getMessage());
        $this->assertSame(QuicError::AEAD_LIMIT_REACHED->value, $exception->getCode());
    }

    public function testCryptoError(): void
    {
        $exception = CryptoException::cryptoError();
        
        $this->assertInstanceOf(CryptoException::class, $exception);
        $this->assertSame('加密错误', $exception->getMessage());
        $this->assertSame(QuicError::CRYPTO_ERROR->value, $exception->getCode());
    }

    public function testCryptoErrorWithDetails(): void
    {
        $exception = CryptoException::cryptoError('解密失败');
        
        $this->assertInstanceOf(CryptoException::class, $exception);
        $this->assertSame('加密错误: 解密失败', $exception->getMessage());
        $this->assertSame(QuicError::CRYPTO_ERROR->value, $exception->getCode());
    }
}