<?php


namespace Data;


abstract class DataProvider
{
    protected array $keyNames;

    public function __construct(array $keyNames)
    {
        $this->keyNames = $keyNames;
    }
}