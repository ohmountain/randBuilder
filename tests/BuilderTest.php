<?php

use PHPUnit\Framework\TestCase;

use RandBuilder\Builder;

class BuilderTest extends TestCase
{

    public function testBuildString()
    {
        $count = 100;

        $schema = [
            "object" => [
              'title' => [
                  'type' => 'string',
                  'length' => [10, 40],
              ]
          ],
          "count" => $count
        ];

        $objects = Builder::build($schema);

        $len = count($objects);

        $this->assertEquals($count, $len);

        for ($i=0; $i<$len; $i++) {
            $this->assertTrue(array_key_exists("title", $objects[$i]));
            $this->assertEquals(1, preg_match('/^\w{10,40}$/', $objects[$i]['title']));
        }

    }

    public function testBuildInteger()
    {
        $range = [10, 100];
        $count = 100;

        $schema = [
            'object' => [
                'age' => [
                    'type' => 'integer',
                    'range' => $range
                ]
            ],
            'count' => $count
        ];
        $objects = Builder::build($schema);

        $len = count($objects);

        $this->assertEquals($count, $len);

        for ($i=0; $i<$len; $i++) {
            $this->assertTrue(array_key_exists('age', $objects[$i]));
            $this->assertTrue($objects[$i]['age'] >= $range[0] && $objects[$i]['age'] <= $range[1]);
        }
    }
    public function testBuildIntegerWithUnique()
    {
        $range = [10, 100];
        $count = 100;

        $schema = [
            'object' => [
                'age' => [
                    'type' => 'integer',
                    'range' => $range,
                    'unique' => true
                ]
            ],
            'count' => $count
        ];
        $objects = Builder::build($schema);

        $len = count($objects);

        $this->assertEquals($count - 10, $len);

        for ($i=0; $i<$len; $i++) {
            $this->assertTrue(array_key_exists('age', $objects[$i]));
            $this->assertTrue($objects[$i]['age'] >= $range[0] && $objects[$i]['age'] <= $range[1]);
        }
    }

    public function testBuildFloat()
    {
       $range = [10, 100];
       $count = 100;
       $precision = 6;

       $schema = [
           'object' => [
               'score' => [
                   'type' => 'float',
                   'range' => $range,
                   'unique' => true,
                   'precision' => $precision
               ]
           ],
           'count' => $count
       ];

       $objects = Builder::build($schema);
       $len = count($objects);


       for ($i=0; $i<$len; $i++) {
           $strval = strval($objects[$i]['score']);
           $arr = explode('.', $strval);

           $this->assertTrue(count($arr[0]) >= 0 && ( isset($arr[1]) ? count($arr[1]) <= $precision : true));
           $this->assertTrue(array_key_exists('score', $objects[$i]));
           $this->assertTrue($objects[$i]['score'] >= $range[0] && $objects[$i]['score'] < $range[1] + 1);
       }
    }
}

