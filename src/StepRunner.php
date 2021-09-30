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
namespace Appium;

use PHPUnit\Extensions\Selenium2TestCase;

class StepRunner
{
    /**
     * @var \Closure[]
     */
    protected $steps = [];

    /**
     * @var Selenium2TestCase
     */
    protected $testCase;

    public function __construct(Selenium2TestCase $testCase, array $steps = [])
    {
        $this->testCase = $testCase;
        $this->steps = $steps;
    }

    /**
     * @return null|mixed
     */
    public function wait()
    {
        $result = null;
        foreach ($this->steps as $step) {
            $waitUntil = new Selenium2TestCase\WaitUntil($this->testCase);
            $result = $waitUntil->run(function () use ($step, $result) {
                return $step($result) ?: true;
            });
        }
        return $result;
    }
}
