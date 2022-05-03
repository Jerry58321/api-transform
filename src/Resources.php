<?php


namespace Goodgod\ApiTransform;


use ArrayAccess;
use Illuminate\Support\Arr;
use JsonSerializable;

class Resources implements ArrayAccess, JsonSerializable
{
    use DelegatesToResource;

    /** @var mixed $resources */
    protected mixed $resources;

    public function __construct($resources)
    {
        $this->resources = $resources;
    }

    /**
     * @return mixed
     */
    public function get(): mixed
    {
        return $this->resources;
    }

    /**
     * @param $data
     * @param string|null $deep
     * @return $this
     */
    public function push($data, string $deep = null): static
    {
        if (!is_null($deep)) {
            Arr::set($this->resources, $deep, $data);
        } else {
            array_push($this->resources, $data);
        }

        return $this;
    }

    /**
     * @param $data
     * @return $this
     */
    public function merge($data): static
    {
        $this->resources = array_merge($this->resources, $data);
        return $this;
    }

    /**
     * @return $this
     */
    public function mapExecClosure(): static
    {
        foreach ($this->resources as $key => $data) {
            if ($data instanceof \Closure) $data($this, $key);
        }
        return $this;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name): mixed
    {
        return $this->offsetExists($name) ? $this->offsetGet($name) : null;
    }
}