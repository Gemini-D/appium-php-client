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

use InvalidArgumentException;

/**
 * @internal
 * @coversNothing
 */
class Element extends \PHPUnit\Extensions\Selenium2TestCase\Element
{
    /**
     * @throws InvalidArgumentException
     * @return \self
     */
    public static function fromResponseValue(
        array $value,
        \PHPUnit\Extensions\Selenium2TestCase\URL $parentFolder,
        \PHPUnit\Extensions\Selenium2TestCase\Driver $driver
    ) {
        if (! isset($value['ELEMENT'])) {
            throw new InvalidArgumentException('Element not found.');
        }
        $url = $parentFolder->descend($value['ELEMENT']);
        return new self($driver, $url);
    }

    public function byIOSUIAutomation($value)
    {
        return $this->by('-ios uiautomation', $value);
    }

    public function byAndroidUIAutomator($value)
    {
        return $this->by('-android uiautomator', $value);
    }

    public function byAccessibilityId($value)
    {
        return $this->by('accessibility id', $value);
    }

    public function setImmediateValue($value)
    {
        $data = [
            'id' => $this->getId(),
            'value' => $value,
        ];
        $url = $this->getSessionUrl()->descend('appium')->descend('element')->descend($this->getId())->descend('value');
        $this->driver->curl('POST', $url, $data);
    }

    public function setText($keys)
    {
        $data = [
            'id' => $this->getId(),
            'value' => [$keys],
        ];
        $url = $this->getSessionUrl()->descend('appium')->descend('element')->descend($this->getId())->descend('replace_value');
        $this->driver->curl('POST', $url, $data);
    }

    public function by($strategy, $value)
    {
        return $this->element($this->using($strategy)->value($value));
    }

    // override to return Appium element
    public function element(\PHPUnit\Extensions\Selenium2TestCase\ElementCriteria $criteria)
    {
        $value = $this->postCommand('element', $criteria);
        return Element::fromResponseValue(
            $value,
            $this->getSessionUrl()->descend('element'),
            $this->driver
        );
    }

    public function elements(\PHPUnit\Extensions\Selenium2TestCase\ElementCriteria $criteria)
    {
        $values = $this->postCommand('elements', $criteria);
        $elements = [];
        foreach ($values as $value) {
            $elements[] =
                Element::fromResponseValue(
                    $value,
                    $this->getSessionUrl()->descend('element'),
                    $this->driver
                );
        }
        return $elements;
    }

    /**
     * @param string $value e.g. 'container'
     * @return \PHPUnit\Extensions\Selenium2TestCase\Element
     */
    public function byClassName($value)
    {
        return $this->by('class name', $value);
    }

    /**
     * @param string $value e.g. 'div.container'
     * @return \PHPUnit\Extensions\Selenium2TestCase\Element
     */
    public function byCssSelector($value)
    {
        return $this->by('css selector', $value);
    }

    /**
     * @param string $value e.g. 'uniqueId'
     * @return \PHPUnit\Extensions\Selenium2TestCase\Element
     */
    public function byId($value)
    {
        return $this->by('id', $value);
    }

    /**
     * @param string $value e.g. 'Link text'
     * @return \PHPUnit\Extensions\Selenium2TestCase\Element
     */
    public function byLinkText($value)
    {
        return $this->by('link text', $value);
    }

    /**
     * @param string $value e.g. 'Link te'
     * @return \PHPUnit\Extensions\Selenium2TestCase\Element
     */
    public function byPartialLinkText($value)
    {
        return $this->by('partial link text', $value);
    }

    /**
     * @param string $value e.g. 'email_address'
     * @return \PHPUnit\Extensions\Selenium2TestCase\Element
     */
    public function byName($value)
    {
        return $this->by('name', $value);
    }

    /**
     * @param string $value e.g. 'body'
     * @return \PHPUnit\Extensions\Selenium2TestCase\Element
     */
    public function byTag($value)
    {
        return $this->by('tag name', $value);
    }

    /**
     * @param string $value e.g. '/div[@attribute="value"]'
     * @return \PHPUnit\Extensions\Selenium2TestCase\Element
     */
    public function byXPath($value)
    {
        return $this->by('xpath', $value);
    }
}
