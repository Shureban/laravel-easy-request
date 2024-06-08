<?php

namespace Shureban\LaravelEasyRequest\Tests\Unit;

use Shureban\LaravelEasyRequest\Tests\Unit\Requests\SnakeCamelCaseRequest;
use Tests\TestCase;

class SnakeCamelCaseTest extends TestCase
{
    public function test_CamelCaseAsSnakeCase()
    {
        $request = new SnakeCamelCaseRequest();
        $request->merge(['testCamelCase' => 'Camel case as snake case']);

        $this->assertEquals('Camel case as snake case', $request->test_camel_case());
    }

    public function test_SnakeCaseAsSnakeCase()
    {
        $request = new SnakeCamelCaseRequest();
        $request->merge(['test_snake_case' => 'Snake case as camel case']);

        $this->assertEquals('Snake case as camel case', $request->testSnakeCase());
    }
}
