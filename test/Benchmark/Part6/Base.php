<?php

class Benchmark_Part6_Base
{
    public function __set($strName, $value)
    {
        $this->$strName = $value;
    }

    public function __get($strName)
    {
        return $this->$strName;
    }
}