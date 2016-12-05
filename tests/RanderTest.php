<?php

use PHPUnit\Framework\TestCase;
use RandBuilder\Rander;

class RanderTest extends TestCase
{
    public function testRandString()
    {
        $len = 10;
        $prefix = 'rand_';
        $end = '_dnar';

        $string = Rander::rand_string($len, $prefix, $end, null);

        $this->assertEquals($prefix, substr($string, 0, 5));
        $this->assertEquals($end, substr($string, -5));
        $this->assertEquals(20, strlen($string));
    }

    public function testRandIntStr()
    {
        $len = 10;
        $integer = Rander::rand_intstr($len);

        for ($i=0; $i<100; $i++) {
            $integer = Rander::rand_intstr($len);
            $this->assertEquals(1, preg_match('/^\d{10}$/', $integer));
        }
    }

    public function testRandFloat()
    {
        $range = [10, 100];
        $precision = 4;

        for ($i=0; $i<100; $i++) {
            $float = Rander::rand_float($range, $precision);
            $this->assertEquals(true, $float >= $range[0]);
            $this->assertEquals(true, $float <= $range[1]);
        }
    }
}

?>
