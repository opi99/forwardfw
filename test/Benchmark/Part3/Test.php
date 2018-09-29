<?php

require_once 'Base.php';

class Benchmark_Part3_Test extends Benchmark_Part3_Base
{
    private $strData = '';

    private $object = null;

    public function setData(string $strData)
    {
        $this->strData = $strData;
    }

    public function getData()
    {
        return $this->strData;
    }

    public function setObject(Benchmark_Part3_Test $obj)
    {
        $this->object = $obj;
    }

    public function getObject()
    {
        return $this->object;
    }
}