<?php


namespace jerry58321\ApiTransform;


use Symfony\Component\HttpFoundation\JsonResponse;

trait DelegatesToResource
{
    /**
     * @param $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return is_array($this->resources) && array_key_exists($offset, $this->resources);
    }

    /**
     * @param $offset
     * @return mixed
     */
    public function offsetGet($offset): mixed
    {
        return $this->resources[$offset];
    }

    /**
     * @param $offset
     * @param $value
     */
    public function offsetSet($offset, $value): void
    {
        $this->resources[$offset] = $value;
    }

    /**
     * @param $offset
     */
    public function offsetUnset($offset): void
    {
        unset($this->resources[$offset]);
    }

    /**
     * @return JsonResponse
     */
    public function jsonSerialize(): JsonResponse
    {
        return new JsonResponse($this->resources);
    }
}