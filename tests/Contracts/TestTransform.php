<?php


namespace Contracts;


use Symfony\Component\HttpFoundation\JsonResponse;

interface TestTransform
{
    public function setKeyMethods($name, $value): static;

    public function getKeyMethods($name): \Closure;

    public function mockResponse(): JsonResponse;

    public function __call(string $name, array $arguments);
}