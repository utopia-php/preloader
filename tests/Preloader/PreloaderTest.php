<?php

namespace Utopia\Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use Utopia\Preloader\Preloader;

ini_set('display_errors', "1");
ini_set('display_startup_errors', "1");
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
       
        $preloader = new Preloader();
        
        $preloader->load();
        
        $autoloaded = $preloader->getCount();

        $this->assertEquals(0, $autoloaded);
        $this->assertCount(0, $preloader->getList());

        $preloader = new Preloader();
        
        $preloader
            ->paths(__DIR__ . '/../resources1/')
            ->load();

            $this->assertEquals(3, $preloader->getCount());
            $this->assertCount(3, $preloader->getList());

        $preloader = new Preloader();
        
        $preloader
            ->paths(__DIR__ . '/../resources2')
            ->ignore(__DIR__ . '/../resources2/nested')
            ->load();
        
            $this->assertEquals(2, $preloader->getCount());
            $this->assertCount(2, $preloader->getList());
    }
}