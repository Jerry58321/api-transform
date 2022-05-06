<?php


namespace Data;


abstract class DataProvider
{
    protected array $keyNames;

    public function __construct(array $keyNames)
    {
        $this->keyNames = $keyNames;
    }

    public abstract function getVerifications(): array;

    public function onlyFunc(array $funcNames): array
    {
        $verifications = [];
        foreach ($funcNames as $name) {
            if (method_exists($this, $name)) {
                $verifications = array_merge($verifications, $this->{$name}());
            }
        }

        return $verifications;
    }
}