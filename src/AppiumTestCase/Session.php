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

use Appium\AppiumTestCase\SessionCommand\Context;
use PHPUnit\Extensions\Selenium2TestCase\SessionCommand\GenericAccessor;

/**
 * @internal
 * @coversNothing
 */
class Session extends \PHPUnit\Extensions\Selenium2TestCase\Session
{
    /**
     * @var string the base URL for this session,
     *             which all relative URLs will refer to
     */
    private $baseUrl;

    public function __construct(
        $driver,
        \PHPUnit\Extensions\Selenium2TestCase\URL $url,
        \PHPUnit\Extensions\Selenium2TestCase\URL $baseUrl,
        \PHPUnit\Extensions\Selenium2TestCase\Session\Timeouts $timeouts
    ) {
        $this->baseUrl = $baseUrl;
        parent::__construct($driver, $url, $baseUrl, $timeouts);
    }

    /**
     * @param array   WebElement JSON object
     * @param mixed $value
     * @return \PHPUnit\Extensions\Selenium2TestCase\Element
     */
    public function elementFromResponseValue($value)
    {
        return \PHPUnit\Extensions\Selenium2TestCase\Element::fromResponseValue($value, $this->getSessionUrl()->descend('element'), $this->driver);
    }

    public function reset()
    {
        $url = $this->getSessionUrl()->addCommand('appium/app/reset');
        $this->driver->curl('POST', $url);
    }

    public function appStrings($language = null)
    {
        $url = $this->getSessionUrl()->addCommand('appium/app/strings');
        $data = [];
        if (! is_null($language)) {
            $data['language'] = $language;
        }
        return $this->driver->curl('POST', $url, $data)->getValue();
    }

    public function keyEvent($keycode, $metastate = null)
    {
        $url = $this->getSessionUrl()->addCommand('appium/device/keyevent');
        $data = [
            'keycode' => $keycode,
            'metastate' => $metastate,
        ];
        $this->driver->curl('POST', $url, $data);
    }

    public function currentActivity()
    {
        $url = $this->getSessionUrl()->addCommand('appium/device/current_activity');
        return $this->driver->curl('GET', $url)->getValue();
    }

    public function currentPackage()
    {
        $url = $this->getSessionUrl()->addCommand('appium/device/current_package');
        return $this->driver->curl('GET', $url)->getValue();
    }

    public function getDriver()
    {
        return $this->driver;
    }

    public function postCommand($name, \PHPUnit\Extensions\Selenium2TestCase\ElementCriteria $criteria)
    {
        $response = $this->driver->curl(
            'POST',
            $this->url->addCommand($name),
            $criteria->getArrayCopy()
        );
        return $response->getValue();
    }

    protected function initCommands()
    {
        $baseUrl = $this->baseUrl;
        $commands = parent::initCommands();

        $commands['contexts'] = GenericAccessor::class;
        $commands['context'] = Context::class;

        return $commands;
    }
}
