<?php


namespace Mock;

use jerry58321\ApiTransform\Resources;

class Model extends Resources implements \IteratorAggregate
{
    public function get(): mixed
    {
        return $this;
    }

    public function getItems()
    {
        return $this->resources;
    }

    public function relationLoaded(string $relation): bool
    {
        return $relation === 'user';
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->resources);
    }
}