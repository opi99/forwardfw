<?php

require_once 'Base.php';

class Benchmark_Part5_Test extends Benchmark_Part5_Base
{
    private $strData = '';

    private $object = null;

    public function setData(string $strData): Benchmark_Part5_Test
    {
        $this->strData = $strData;
        return $this;
    }

    public function getData(): string
    {
        return $this->strData;
    }

    public function setObject(Benchmark_Part5_Test $obj): Benchmark_Part5_Test
    {
        $this->object = $obj;
        return $this;
    }

    public function getObject(): Benchmark_Part5_Test
    {
        return $this->object;
    }
}