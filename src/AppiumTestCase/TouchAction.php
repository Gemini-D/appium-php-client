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

class TouchAction
{
    private $sessionUrl;

    private $driver;

    private $actions;

    public function __construct(
        \PHPUnit\Extensions\Selenium2TestCase\URL $sessionUrl,
        Driver $driver
    ) {
        $this->sessionUrl = $sessionUrl;
        $this->driver = $driver;
        $this->actions = [];
    }

    public function tap($params)
    {
        $options = $this->getOptions($params);

        if (array_key_exists('count', $params)) {
            $options['count'] = $params['count'];
        } else {
            $options['count'] = 1;
        }

        $this->addAction('tap', $options);
        return $this;
    }

    public function press($params)
    {
        $options = $this->getOptions($params);

        $this->addAction('press', $options);
        return $this;
    }

    public function longPress($params)
    {
        $options = $this->getOptions($params);

        if (array_key_exists('duration', $params)) {
            $options['duration'] = $params['duration'];
        } else {
            $options['duration'] = 800;
        }

        $this->addAction('longPress', $options);
        return $this;
    }

    public function moveTo($params)
    {
        $options = $this->getOptions($params);

        $this->addAction('moveTo', $options);
        return $this;
    }

    public function wait($params)
    {
        $options = [];

        if (gettype($params) == 'array') {
            if (array_key_exists('ms', $params)) {
                $options['ms'] = $params['ms'];
            } else {
                $options['ms'] = 0;
            }
        } else {
            $options['ms'] = $params;
        }

        $this->addAction('wait', $options);
        return $this;
    }

    public function release()
    {
        $this->addAction('release', []);
        return $this;
    }

    public function perform()
    {
        $params = [
            'actions' => $this->actions,
        ];
        $url = $this->sessionUrl->descend('touch')->descend('perform');
        $this->driver->curl('POST', $url, $params);
    }

    public function getJSONWireGestures()
    {
        $actions = [];
        foreach ($this->actions as $action) {
            $actions[] = $action;
        }
        return $actions;
    }

    protected function getOptions($params)
    {
        $opts = [];

        if (array_key_exists('element', $params) && $params['element'] != null) {
            $opts['element'] = $params['element']->getId();
        }

        # it makes no sense to have x but no y, or vice versa.
        if (array_key_exists('x', $params) && array_key_exists('y', $params)) {
            $opts['x'] = $params['x'];
            $opts['y'] = $params['y'];
        }

        return $opts;
    }

    protected function addAction($action, $options)
    {
        $gesture = [
            'action' => $action,
            'options' => $options,
        ];

        $this->actions[] = $gesture;
    }
}
