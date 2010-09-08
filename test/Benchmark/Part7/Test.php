<?php

require_once 'Base.php';

class Benchmark_Part7_Test extends Benchmark_Part7_Base
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

    public function setObject($obj)
    {
        if (!$obj instanceof Benchmark_Part7_Test) {
            throw new Exception();
        }
        $this->object = $obj;
        return $this;
    }

    public function getObject()
    {
        return $this->object;
    }
}