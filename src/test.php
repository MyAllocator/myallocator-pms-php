<?php

require_once(dirname(__FILE__) . '/MyAllocator.php');

use MyAllocator\phpsdk\Api\HelloWorld;

$obj = new HelloWorld();
echo $obj->sayHi('hi!') . "\n";
