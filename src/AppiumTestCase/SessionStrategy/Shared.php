<?php

declare(strict_types=1);
/**
 * 此项目衍生于 `appium/php-client`
 *
 * @link   https://github.com/appium/appium-php
 * @link   https://github.com/Gemini-D/appium-php-client
 * @author Isaac Murchie <isaac@saucelabs.com>
 * @author limingxinleo <l@hyperf.io>
 */
namespace Appium\AppiumTestCase\SessionStrategy;

/**
 * @internal
 * @coversNothing
 */
class Shared implements \PHPUnit\Extensions\Selenium2TestCase\SessionStrategy
{
    private $original;

    private $session;

    private $mainWindow;

    private $lastTestWasNotSuccessful = false;

    public function __construct(\PHPUnit\Extensions\Selenium2TestCase\SessionStrategy $originalStrategy)
    {
        $this->original = $originalStrategy;
    }

    public function session(array $parameters)
    {
        if ($this->lastTestWasNotSuccessful) {
            if ($this->session !== null) {
                $this->session->stop();
                $this->session = null;
            }
            $this->lastTestWasNotSuccessful = false;
        }
        if ($this->session === null) {
            $this->session = $this->original->session($parameters);
            $this->mainWindow = $this->session->windowHandle();
        } else {
            $this->session->window($this->mainWindow);
        }
        return $this->session;
    }

    public function notSuccessfulTest()
    {
        $this->lastTestWasNotSuccessful = true;
    }

    public function endOfTest(\PHPUnit\Extensions\Selenium2TestCase\Session $session = null)
    {
    }
}
