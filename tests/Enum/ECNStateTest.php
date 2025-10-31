<?php

declare(strict_types=1);

namespace Tourze\QUIC\Core\Tests\Enum;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;
use Tourze\QUIC\Core\Enum\ECNState;

/**
 * ECNState 枚举单元测试
 *
 * @internal
 */
#[CoversClass(ECNState::class)]
final class ECNStateTest extends AbstractEnumTestCase
{
    /**
     * 测试枚举值
     */
    public function testEnumValues(): void
    {
        $this->assertSame('testing', ECNState::TESTING->value);
        $this->assertSame('unknown', ECNState::UNKNOWN->value);
        $this->assertSame('capable', ECNState::CAPABLE->value);
        $this->assertSame('failed', ECNState::FAILED->value);
    }

    /**
     * 测试是否支持ECN
     */
    public function testIsCapable(): void
    {
        $this->assertFalse(ECNState::TESTING->isCapable());
        $this->assertFalse(ECNState::UNKNOWN->isCapable());
        $this->assertTrue(ECNState::CAPABLE->isCapable());
        $this->assertFalse(ECNState::FAILED->isCapable());
    }

    /**
     * 测试是否正在测试
     */
    public function testIsTesting(): void
    {
        $this->assertTrue(ECNState::TESTING->isTesting());
        $this->assertFalse(ECNState::UNKNOWN->isTesting());
        $this->assertFalse(ECNState::CAPABLE->isTesting());
        $this->assertFalse(ECNState::FAILED->isTesting());
    }

    /**
     * 测试ECN是否失败
     */
    public function testIsFailed(): void
    {
        $this->assertFalse(ECNState::TESTING->isFailed());
        $this->assertFalse(ECNState::UNKNOWN->isFailed());
        $this->assertFalse(ECNState::CAPABLE->isFailed());
        $this->assertTrue(ECNState::FAILED->isFailed());
    }

    /**
     * 测试状态描述
     */
    public function testGetDescription(): void
    {
        $this->assertSame('正在测试ECN', ECNState::TESTING->getDescription());
        $this->assertSame('ECN状态未知', ECNState::UNKNOWN->getDescription());
        $this->assertSame('ECN功能可用', ECNState::CAPABLE->getDescription());
        $this->assertSame('ECN功能不可用', ECNState::FAILED->getDescription());
    }

    /**
     * 测试 toArray 方法
     */
    public function testToArray(): void
    {
        $result = ECNState::TESTING->toArray();
        $this->assertIsArray($result);
        $this->assertArrayHasKey('label', $result);
        $this->assertArrayHasKey('value', $result);
        $this->assertSame('测试中', $result['label']);
        $this->assertSame('testing', $result['value']);

        $result = ECNState::CAPABLE->toArray();
        $this->assertSame('支持', $result['label']);
        $this->assertSame('capable', $result['value']);
    }

    /**
     * 测试具体的 toSelectItem 结果
     */
    public function testSpecificToSelectItemResults(): void
    {
        $result = ECNState::TESTING->toSelectItem();
        $this->assertIsArray($result);
        $this->assertArrayHasKey('label', $result);
        $this->assertArrayHasKey('value', $result);
        $this->assertSame('测试中', $result['label']);
        $this->assertSame('testing', $result['value']);

        $result = ECNState::CAPABLE->toSelectItem();
        $this->assertSame('支持', $result['label']);
        $this->assertSame('capable', $result['value']);
    }
}
