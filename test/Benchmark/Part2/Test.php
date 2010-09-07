<?php

require_once 'Base.php';

class Benchmark_Part2_Test extends Benchmark_Part2_Base
{
    private $strData = '';

    private $object = null;

    public function setData($strData)
    {
        $this->strData = $strData;
    }

    public function getData()
    {
        return $this->strData;
    }

    public function setObject($obj)
    {
        $this->object = $obj;
    }

    public function getObject()
    {
        return $this->object;
    }
}