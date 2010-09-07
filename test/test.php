<?php

require_once 'Benchmark/Part1/Test.php';
require_once 'Benchmark/Part2/Test.php';
require_once 'Benchmark/Part3/Test.php';
require_once 'Benchmark/Part4/Test.php';
require_once 'Benchmark/Part5/Test.php';
require_once 'Benchmark/Part6/Test.php';

$strData = 'This is a test string.';

// Part 1
$object = new Benchmark_Part1_Test();
$nTimeStart = microtime(true);

for ($i = 0; $i < 100000; $i++) {
    $obj = new Benchmark_Part1_Test();
    $obj->strData = $strData;
    $obj->object  = $object;
}

$nTimeStop = microtime(true);

echo 'Part 1: ' . ($nTimeStop - $nTimeStart) * 1000 . ' ms <br />';

// Part 2
$object = new Benchmark_Part2_Test();
$nTimeStart = microtime(true);

for ($i = 0; $i < 100000; $i++) {
    $obj = new Benchmark_Part2_Test();
    $obj->setData($strData);
    $obj->setObject($object);
}

$nTimeStop = microtime(true);

echo 'Part 2: ' . ($nTimeStop - $nTimeStart) * 1000 . ' ms <br />';

// Part 3
$object = new Benchmark_Part3_Test();
$nTimeStart = microtime(true);

for ($i = 0; $i < 100000; $i++) {
    $obj = new Benchmark_Part3_Test();
    $obj->setData($strData);
    $obj->setObject($object);
}

$nTimeStop = microtime(true);

echo 'Part 3: ' . ($nTimeStop - $nTimeStart) * 1000 . ' ms <br />';

// Part 4
$object = new Benchmark_Part4_Test();
$nTimeStart = microtime(true);

for ($i = 0; $i < 100000; $i++) {
    $obj = new Benchmark_Part4_Test();
    $obj->setData($strData)
        ->setObject($object);
}

$nTimeStop = microtime(true);

echo 'Part 4: ' . ($nTimeStop - $nTimeStart) * 1000 . ' ms <br />';

// Part 5
$object = new Benchmark_Part5_Test();
$nTimeStart = microtime(true);

for ($i = 0; $i < 100000; $i++) {
    $obj = new Benchmark_Part5_Test();
    $obj->setData($strData)
        ->setObject($object);
}

$nTimeStop = microtime(true);

echo 'Part 5: ' . ($nTimeStop - $nTimeStart) * 1000 . ' ms <br />';

// Part 6
$object = new Benchmark_Part6_Test();
$nTimeStart = microtime(true);

for ($i = 0; $i < 100000; $i++) {
    $obj = new Benchmark_Part6_Test();
    $obj->strData = $strData;
    $obj->object  = $object;
}

$nTimeStop = microtime(true);

echo 'Part 6: ' . ($nTimeStop - $nTimeStart) * 1000 . ' ms <br />';
phpinfo();
?>