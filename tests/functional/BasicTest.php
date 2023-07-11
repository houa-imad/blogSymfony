<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;   

class BasicTest extends KernelTestCase
{
    public function testEnvironnementOk(): void
    {
        $this->assertTrue(true);
    }
}
