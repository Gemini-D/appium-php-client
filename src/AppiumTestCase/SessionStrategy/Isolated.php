<?php

declare(strict_types=1);
/**
 * 此项目衍生于 `appium/php-client`
 *
 * @link   https://github.com/appium-boneyard/php-client
 * @link   https://github.com/Gemini-D/appium-php-client
 * @author Isaac Murchie <isaac@saucelabs.com>
 * @author limingxinleo <l@hyperf.io>
 */
namespace Appium\AppiumTestCase\SessionStrategy;

use Appium\AppiumTestCase;

/**
 * @internal
 * @coversNothing
 */
class Isolated implements \PHPUnit\Extensions\Selenium2TestCase\SessionStrategy
{
    public function session(array $parameters)
    {
        $seleniumServerUrl = \PHPUnit\Extensions\Selenium2TestCase\URL::fromHostAndPort($parameters['host'], $parameters['port'], $parameters['secure'] ?? false);
        $driver = new AppiumTestCase\Driver($seleniumServerUrl, $parameters['seleniumServerRequestsTimeout']);
        $capabilities = array_merge(
            $parameters['desiredCapabilities'],
            [
                'browserName' => $parameters['browserName'],
            ]
        );
        return $driver->startSession($capabilities, $parameters['browserUrl']);
    }

    public function notSuccessfulTest()
    {
    }

    public function endOfTest(\PHPUnit\Extensions\Selenium2TestCase\Session $session = null)
    {
        if ($session !== null) {
            $session->stop();
        }
    }
}
