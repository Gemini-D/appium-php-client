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
namespace Appium\AppiumTestCase;

/**
 * @internal
 * @coversNothing
 */
class Driver extends \PHPUnit\Extensions\Selenium2TestCase\Driver
{
    private $seleniumServerUrl;

    private $seleniumServerRequestsTimeout;

    public function __construct(\PHPUnit\Extensions\Selenium2TestCase\URL $seleniumServerUrl, $timeout = 60)
    {
        parent::__construct($seleniumServerUrl, $timeout);

        $this->seleniumServerUrl = $seleniumServerUrl;
        $this->seleniumServerRequestsTimeout = $timeout;
    }

    public function startSession(
        array $desiredCapabilities,
        \PHPUnit\Extensions\Selenium2TestCase\URL $browserUrl
    ) {
        $sessionCreation = $this->seleniumServerUrl->descend('/wd/hub/session');
        $response = $this->curl('POST', $sessionCreation, [
            'desiredCapabilities' => $desiredCapabilities,
        ]);
        $sessionPrefix = $response->getURL();

        $timeouts = new \PHPUnit\Extensions\Selenium2TestCase\Session\Timeouts(
            $this,
            $sessionPrefix->descend('timeouts'),
            $this->seleniumServerRequestsTimeout * 1000
        );
        return new Session(
            $this,
            $sessionPrefix,
            $browserUrl,
            $timeouts
        );
    }

    public function getServerUrl()
    {
        return $this->seleniumServerUrl;
    }
}
