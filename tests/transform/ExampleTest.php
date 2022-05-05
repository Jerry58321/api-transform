<?php


namespace transform;


use Symfony\Component\HttpFoundation\JsonResponse;

interface ExampleTest
{
    public function setAttribute($name, $value): static;

    public function mockResponse(): JsonResponse;

    public function __call(string $name, array $arguments);
}