<?php

namespace Tests\Unit;

use Amunyua\Coop\Coop;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $key = Coop::getAccountBalance();
        dd($key);
    }
}
