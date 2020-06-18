<?php

namespace Utopia\Tests;

use PHPUnit\Framework\TestCase;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class PreloaderTest extends TestCase
{
    public function setUp()
    {
        
    }

    public function tearDown()
    {
        $this->test = null;
    }

    public function testTest()
    {
        // $this->assertEquals(include __DIR__.'/demo.php', Config::getParam('key5', []));
        // $this->assertEquals([], Config::getParam('key6', []));
        // $this->assertEquals('value1', Config::getParam('key5.key1', 'default'));
        // $this->assertEquals('value2', Config::getParam('key5.key2', 'default'));
        // $this->assertEquals('default2', Config::getParam('key5.x', 'default2'));
    }
}