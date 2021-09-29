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
namespace Appium\AppiumTestCase;

class MultiAction
{
    private $sessionUrl;

    private $driver;

    private $element;

    private $actions;

    public function __construct(
        \PHPUnit\Extensions\Selenium2TestCase\URL $sessionUrl,
        Driver $driver,
        Element $element = null
    ) {
        $this->sessionUrl = $sessionUrl;
        $this->driver = $driver;
        $this->element = $element;
        $this->actions = [];
    }

    public function add(TouchAction $action)
    {
        if (is_null($this->actions)) {
            $this->actions = [];
        }

        $this->actions[] = $action;
    }

    public function perform()
    {
        $params = $this->getJSONWireGestures();

        $url = $this->sessionUrl->descend('touch')->descend('multi')->descend('perform');
        $this->driver->curl('POST', $url, $params);
    }

    public function getJSONWireGestures()
    {
        $actions = [];
        foreach ($this->actions as $action) {
            $actions[] = $action->getJSONWireGestures();
        }

        $gestures = [
            'actions' => $actions,
        ];
        if (! is_null($this->element)) {
            $gestures['elementId'] = $this->element->getId();
        }

        return $gestures;
    }
}
