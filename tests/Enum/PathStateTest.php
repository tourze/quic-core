<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Tests\Enum;

use PHPUnit\Framework\TestCase;
use Tourze\QUIC\Core\Enum\PathState;

/**
 * PathState 枚举单元测试
 *
 * @covers \Tourze\QUIC\Core\Enum\PathState
 */
class PathStateTest extends TestCase
{
    /**
     * 测试枚举值
     */
    public function testEnumValues(): void
    {
        $this->assertSame('probing', PathState::PROBING->value);
        $this->assertSame('validating', PathState::VALIDATING->value);
        $this->assertSame('validated', PathState::VALIDATED->value);
        $this->assertSame('active', PathState::ACTIVE->value);
    }

    /**
     * 测试路径是否已验证
     */
    public function testIsValidated(): void
    {
        $this->assertFalse(PathState::PROBING->isValidated());
        $this->assertFalse(PathState::VALIDATING->isValidated());
        $this->assertTrue(PathState::VALIDATED->isValidated());
        $this->assertTrue(PathState::ACTIVE->isValidated());
    }

    /**
     * 测试路径是否正在验证过程中
     */
    public function testIsValidating(): void
    {
        $this->assertTrue(PathState::PROBING->isValidating());
        $this->assertTrue(PathState::VALIDATING->isValidating());
        $this->assertFalse(PathState::VALIDATED->isValidating());
        $this->assertFalse(PathState::ACTIVE->isValidating());
    }

    /**
     * 测试路径是否激活
     */
    public function testIsActive(): void
    {
        $this->assertFalse(PathState::PROBING->isActive());
        $this->assertFalse(PathState::VALIDATING->isActive());
        $this->assertFalse(PathState::VALIDATED->isActive());
        $this->assertTrue(PathState::ACTIVE->isActive());
    }

    /**
     * 测试状态描述
     */
    public function testGetDescription(): void
    {
        $this->assertSame('正在探测路径', PathState::PROBING->getDescription());
        $this->assertSame('正在验证路径', PathState::VALIDATING->getDescription());
        $this->assertSame('路径已验证', PathState::VALIDATED->getDescription());
        $this->assertSame('路径已激活', PathState::ACTIVE->getDescription());
    }
} 