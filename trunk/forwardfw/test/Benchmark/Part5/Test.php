<?php

require_once 'Base.php';

class Benchmark_Part5_Test extends Benchmark_Part5_Base
{
    private $strData = '';

    private $object = null;

    public function setData($strData)
    {
        $this->strData = $strData;
        return $this;
    }

    public function getData()
    {
        return $this->strData;
    }

    public function setObject(Benchmark_Part5_Test $obj)
    {
        $this->object = $obj;
        return $this;
    }

    public function getObject()
    {
        return $this->object;
    }
}