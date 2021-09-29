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
namespace Appium\AppiumTestCase\SessionCommand;

use BadMethodCallException;

 /**
  * @internal
  * @coversNothing
  */
 class Context extends \PHPUnit\Extensions\Selenium2TestCase\Command
 {
     public function __construct($name, $commandUrl)
     {
         if (is_string($name)) {
             $jsonParameters = ['name' => $name];
         } elseif ($name == null) {
             $jsonParameters = null;
         } else {
             throw new BadMethodCallException('Wrong Parameters for context().');
         }

         parent::__construct($jsonParameters, $commandUrl);
     }

     public function httpMethod()
     {
         if ($this->jsonParameters) {
             return 'POST';
         }
         return 'GET';
     }
 }
