# QUIC Core Package

[English](README.md) | [中文](README.zh-CN.md)

[![PHP Version](https://img.shields.io/badge/PHP-8.1%2B-blue.svg)](https://php.net)
[![Tests](https://img.shields.io/badge/Tests-186%20Passed-green.svg)](tests/)
[![PHPStan](https://img.shields.io/badge/PHPStan-Level%20Max-brightgreen.svg)](https://github.com/phpstan/phpstan)
[![License](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)
[![Coverage](https://img.shields.io/badge/Coverage-100%25-brightgreen.svg)](tests/)

QUIC协议核心基础库，提供QUIC协议的核心枚举、常量、工具类和异常处理机制。

## 目录

- [功能特性](#功能特性)
- [安装](#安装)
- [快速开始](#快速开始)
- [规范遵循](#规范遵循)
- [架构设计](#架构设计)
- [测试](#测试)
- [开发](#开发)
- [性能](#性能)
- [贡献](#贡献)
- [许可证](#许可证)

## 功能特性

### 🎯 核心枚举
- **QuicError** - QUIC错误码枚举，包含所有RFC 9000定义的错误类型
- **ConnectionState** - 连接状态管理，支持状态转换验证
- **FrameType** - 帧类型定义，包含完整的帧特性判断方法
- **PacketType** - 包类型枚举，支持长/短包头判断
- **StreamSendState/StreamRecvState** - 流状态机实现
- **StreamType** - 流类型识别（双向/单向，客户端/服务端）
- **TLSState** - TLS握手状态枚举
- **ECNState** - ECN功能状态枚举
- **PathState** - 路径验证状态枚举

### 🛠️ 工具类
- **VariableInteger** - 变长整数编解码器，完全符合RFC 9000规范
- **ConnectionId** - 连接ID生成器和管理工具
- **Constants** - 完整的QUIC协议常量定义

### ⚠️ 异常处理
- **QuicException** - 基础异常类，集成QUIC错误码
- **ConnectionException** - 连接层异常
- **StreamException** - 流层异常
- **FrameException** - 帧处理异常
- **CryptoException** - 加密层异常

## 安装

```bash
composer require tourze/quic-core
```

## 快速开始

### 变长整数编解码

```php
use Tourze\QUIC\Core\VariableInteger;

// 编码
$encoded = VariableInteger::encode(12345);

// 解码
[$value, $consumed] = VariableInteger::decode($encoded);
echo $value; // 12345
echo $consumed; // 消耗的字节数

// 批量操作
$values = [100, 200, 300];
$encoded = VariableInteger::encodeMultiple($values);
[$decoded, $totalConsumed] = VariableInteger::decodeMultiple($encoded, 3);
```

### 连接ID管理

```php
use Tourze\QUIC\Core\ConnectionId;

// 生成连接ID
$connectionId = ConnectionId::generate(8); // 生成8字节连接ID
$randomId = ConnectionId::random(4, 16);   // 生成4-16字节随机长度ID

// 十六进制转换
$hex = ConnectionId::toHex($connectionId);
$restored = ConnectionId::fromHex($hex);

// 重置令牌
$secret = random_bytes(16);
$token = ConnectionId::generateResetToken($connectionId, $secret);
$isValid = ConnectionId::verifyResetToken($connectionId, $token, $secret);
```

### 错误处理

```php
use Tourze\QUIC\Core\Exception\ConnectionException;
use Tourze\QUIC\Core\Exception\StreamException;

// 连接异常
throw ConnectionException::refused('服务器拒绝连接');
throw ConnectionException::protocolViolation('无效帧格式');

// 流异常
throw StreamException::stateError('流已关闭');
throw StreamException::flowControlError('超出流量限制');
```

### 枚举使用

```php
use Tourze\QUIC\Core\Enum\ConnectionState;
use Tourze\QUIC\Core\Enum\FrameType;
use Tourze\QUIC\Core\Enum\QuicError;

// 连接状态管理
$state = ConnectionState::NEW;
if ($state->canSendData()) {
    // 可以发送数据
}

// 帧类型判断
$frameType = FrameType::STREAM;
if ($frameType->isStreamFrame()) {
    // 处理流帧
}

// 错误分类
$error = QuicError::CONNECTION_REFUSED;
if ($error->isFatal()) {
    // 致命错误，关闭连接
}
```

### 常量使用

```php
use Tourze\QUIC\Core\Constants;

// 协议版本
$version = Constants::VERSION_1;
$isSupported = Constants::isSupportedVersion($version);

// 默认配置
$params = Constants::getDefaultTransportParameters();

// 大小限制
$maxPacketSize = Constants::MAX_PACKET_SIZE;
$maxConnectionIdLength = Constants::MAX_CONNECTION_ID_LENGTH;
```

## 规范遵循

本包严格遵循以下规范：

- 📋 **RFC 9000** - QUIC: A UDP-Based Multiplexed and Secure Transport
- 🔐 **RFC 9001** - Using TLS to Secure QUIC  
- 🛡️ **RFC 9002** - QUIC Loss Detection and Congestion Control
- 📝 **PSR-1** - Basic Coding Standard
- 🏗️ **PSR-4** - Autoloader Standard
- ✨ **PSR-12** - Extended Coding Style

## 架构设计

### 设计原则

- **第一性原理** - 从QUIC协议本质出发设计
- **SOLID原则** - 单一职责、开闭原则、里式替换等
- **DRY原则** - 避免重复代码
- **KISS原则** - 保持简单易懂
- **YAGNI原则** - 只实现需要的功能

### 包结构

```text
src/
├── Enum/           # 核心枚举类
├── Exception/      # 异常处理类
├── Constants.php   # 协议常量
├── VariableInteger.php  # 变长整数工具
└── ConnectionId.php     # 连接ID工具

tests/
├── Enum/          # 枚举测试
├── Exception/     # 异常测试
└── *.php         # 工具类测试
```

## 测试

运行所有测试：

```bash
composer install
vendor/bin/phpunit
```

测试统计：
- ✅ **186个测试** 全部通过
- ✅ **898个断言** 全部成功
- 📊 **100%** 核心功能覆盖

## 开发

### 环境要求

- PHP 8.1+
- ext-mbstring

### 开发依赖

- PHPUnit 10.0+ （测试框架）
- PHPStan 2.1+ （静态分析）

### 代码风格

本项目遵循PSR-12编码标准，并有以下额外规范：

- 使用PHP 8.1+特性（枚举、readonly属性等）
- 中文注释说明
- 严格类型声明
- 完整的PHPDoc注释

## 性能

### 基准测试

VariableInteger编解码性能（10,000次迭代）：
- 编码：< 0.1秒
- 解码：< 0.1秒
- 往返转换：< 0.2秒

### 内存使用

- 枚举类：零运行时开销
- 工具类：最小内存占用
- 异常类：继承标准Exception

## 贡献

欢迎提交Issue和Pull Request！

### 开发流程

1. Fork本仓库
2. 创建特性分支 (`git checkout -b feature/AmazingFeature`)
3. 提交更改 (`git commit -m 'Add some AmazingFeature'`)
4. 推送到分支 (`git push origin feature/AmazingFeature`)
5. 开启Pull Request

### 提交规范

- 🎯 feat: 新功能
- 🐛 fix: 修复bug
- 📚 docs: 文档更新
- 🎨 style: 代码格式调整
- ♻️ refactor: 重构代码
- ✅ test: 添加测试
- 🔧 chore: 构建工具等

## 许可证

[MIT License](LICENSE)

## 相关链接

- [QUIC Working Group](https://quicwg.org/)
- [RFC 9000 - QUIC Protocol](https://tools.ietf.org/html/rfc9000)
- [PHP Documentation](https://php.net)

---

**注意**: 这是一个基础库，提供QUIC协议的核心类型和工具。完整的QUIC实现需要配合其他协议层包使用。
