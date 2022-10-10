<?php

namespace Utopia\Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use Utopia\Preloader\Preloader;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class PreloaderTest extends TestCase
{
    public function setUp(): void
    {
        
    }

    public function tearDown(): void
    {
       
    }

    public function testTest(): void
    {   
        $test = new Test('unit-test');

        $test;
        $preloader = new Preloader();
        
        $preloader->load();
        
        $autoloaded = $preloader->getCount();

        assertEquals(0, $autoloaded);
        assertCount(0, $preloader->getList());

        $preloader = new Preloader();
        
        $preloader
            ->paths(realpath(__DIR__ . '/../resources1'))
            ->load();

        assertEquals(3, $preloader->getCount());
        assertCount(3, $preloader->getList());

        $preloader = new Preloader();

        $preloader
            ->paths(realpath(__DIR__ . '/../resources2'))
            ->ignore(realpath(__DIR__ . '/../resources2/nested'))
            ->load();
        
        assertEquals(2, $preloader->getCount());
        assertCount(2, $preloader->getList());
    }
}