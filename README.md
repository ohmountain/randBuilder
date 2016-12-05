# RandBuilder
> A random data generator

# Schema
```php

$schema = array(
    "object" => array(
        "name" => array(
            "type" => "string",
            "length" => 10,
            "prefix" => "builder_",
            "end" => "_redliub",
            "unique" => true
        ),
        "hash" => array(
            "type" => "string",
            "length" => array(10,20),
            "reducer" => "md5"
        ),
        "number" => array(
            "type" => "integer",
            "range" => array(10, 100),
            "unique" => true
        ),
        "price" => array(
            "type" => "float",
            "range" => array(100, 1000),
            "precision" => 2
        )
    ),

    "count" => 10  // How many object will be make
);
```

Ok, `$scheme` is a scheme used to generate the data you want, it's an array php, this array contains two items: `object` and `count`, `object` is the real **schema**, and `count` indicate how many you want.  
Let's see the `object`, however of whatever, `object` is an array, items in `object` are fields to be generated, the most important thing is to define a field, items has a few attributes:  
* type
&emsp;&emsp;&emsp;&emsp;`type` is required, now RandBuilder support three types: string, integer and float.  
* length
&emsp;&emsp;&emsp;&emsp;`type` is required if `type` is string  
* prefix
&emsp;&emsp;&emsp;&emsp;`prefix` defaults to ''  
* end
&emsp;&emsp;&emsp;&emsp;`end` defaults to ''
* unique
&emsp;&emsp;&emsp;&emsp;`unique` indicate the field is unique or not, defaults to `false`  
* range
&emsp;&emsp;&emsp;&emsp;`range` is not required for integer and float, it indicate the min and the max number to be generated
* precision
&emsp;&emsp;&emsp;&emsp;`range` for `float`, it default to 0  



Note: if `unique` is true, the actual quantity produced may be less than the specified quantity.

# Example
```php

use RandBuilder\Builder;

$schema = array(
    "object" => array(
        "name" => array(
            "type" => "string",
            "length" => 10,
            "prefix" => "builder_",
            "end" => "_redliub",
            "unique" => true
        ),
        "hash" => array(
            "type" => "string",
            "length" => array(10,20),
            "reducer" => "md5"
        ),
        "number" => array(
            "type" => "integer",
            "range" => array(10, 100),
            "unique" => true
        ),
        "price" => array(
            "type" => "float",
            "range" => array(100, 1000),
            "precision" => 2
        )
    ),

    "count" => 10  // How many object will be make
);

$objects = Builder::build($schema);
``
