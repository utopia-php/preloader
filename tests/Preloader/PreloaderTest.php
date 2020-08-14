<?php

namespace Utopia\Tests;

use PHPUnit\Framework\TestCase;
use Utopia\Preloader\Preloader;

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
        $preloader = new Preloader();
        
        $preloader
            //->paths(realpath(__DIR__ . '/../resources'))
            // ->paths(realpath(__DIR__ . '/../resources/nested'))
            ->load();

        $autoloaded = $preloader->getCount();

        $this->assertGreaterThan(30, $autoloaded);

        $preloader = new Preloader();
        
        $preloader
            ->paths(realpath(__DIR__ . '/../resources'))
            ->load();
        
        $this->assertEquals($autoloaded + 3 + 4, $preloader->getCount());
        
        $preloader
            ->paths(realpath(__DIR__ . '/../resources'))
            ->ignore(realpath(__DIR__ . '/../resources/nested'))
            ->load();
        
        $this->assertEquals($autoloaded + 2 + 5, $preloader->getCount());
    }
}