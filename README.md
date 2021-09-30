# Appium PHP Client

[![PHPUnit](https://github.com/Gemini-D/appium-php-client/actions/workflows/test.yml/badge.svg)](https://github.com/Gemini-D/appium-php-client/actions/workflows/test.yml)

## 安装

```
composer create-project gemini/appium-php-client
```

## 使用

```php
<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace HyperfTest\Cases;

use Appium\AppiumTestCase\Element;
use Appium\AppiumTestCase;

/**
 * @internal
 * @coversNothing
 */
class ExampleTest extends AppiumTestCase
{
    /**
     * @group iOS
     */
    public function testOpen()
    {
        $steps = [
            fn () => $this->byAccessibilityId('好')->click(),
            fn () => $this->byAccessibilityId('允许'),
            fn (Element $el) => $el->click(),
            fn () => $this->byAccessibilityId('LaunchLogo'),
        ];

        $el = $this->runSteps($steps);

        $this->assertNotNull($el);
    }
}

```
