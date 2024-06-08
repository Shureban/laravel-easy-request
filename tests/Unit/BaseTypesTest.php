<?php

namespace Shureban\LaravelEasyRequest\Tests\Unit;

use Shureban\LaravelEasyRequest\Tests\Unit\Requests\BaseTypesRequest;
use Tests\TestCase;

class BaseTypesTest extends TestCase
{
    public function test_String()
    {
        $request = new BaseTypesRequest();
        $request->merge(['testString' => 'Test name']);

        $this->assertEquals('Test name', $request->testString());
    }

    public function test_Boolean()
    {
        $request = new BaseTypesRequest();
        $request->merge([
            'testBoolean1' => 'true',
            'testBoolean2' => 'false',
            'testBoolean3' => '1',
            'testBoolean4' => '0',
            'testBool'     => '1',
        ]);

        $this->assertEquals(true, $request->testBoolean1());
        $this->assertEquals(false, $request->testBoolean2());
        $this->assertEquals(true, $request->testBoolean3());
        $this->assertEquals(false, $request->testBoolean4());
        $this->assertEquals(true, $request->testBool());
    }

    public function test_Integer()
    {
        $request = new BaseTypesRequest();
        $request->merge([
            'testInteger1' => '1',
            'testInteger2' => '0',
            'testInteger3' => (string)PHP_INT_MAX,
            'testInteger4' => (string)PHP_INT_MIN,
            'testInteger5' => '123.123',
            'testInt'      => '1',
        ]);

        $this->assertEquals(1, $request->testInteger1());
        $this->assertEquals(0, $request->testInteger2());
        $this->assertEquals(PHP_INT_MAX, $request->testInteger3());
        $this->assertEquals(PHP_INT_MIN, $request->testInteger4());
        $this->assertEquals(123, $request->testInteger5());
        $this->assertEquals(1, $request->testInt());
    }

    public function test_Float()
    {
        $request = new BaseTypesRequest();
        $request->merge([
            'testFloat1' => '1',
            'testFloat2' => '0.1',
            'testFloat3' => '.1',
            'testFloat4' => '0.0001',
            'testFloat5' => '0.0000000000000001',
            'testFloat6' => '1000000000000000.1',
        ]);

        $this->assertEquals(1, $request->testFloat1());
        $this->assertEquals(0.1, $request->testFloat2());
        $this->assertEquals(0.1, $request->testFloat3());
        $this->assertEquals(0.0001, $request->testFloat4());
        $this->assertEquals(0.0000000000000001, $request->testFloat5());
        $this->assertEquals(1000000000000000.1, $request->testFloat6());
    }

    public function test_Array()
    {
        $request = new BaseTypesRequest();
        $request->merge([
            'testArray' => [[], 'a' => ['b', 'c' => 'd'], 1 => 12],
        ]);

        $this->assertEquals([[], 'a' => ['b', 'c' => 'd'], 1 => 12], $request->testArray());
    }

    public function test_Mixed()
    {
        $request = new BaseTypesRequest();
        $request->merge([
            'testMixed1' => 'string',
            'testMixed2' => '1',
            'testMixed3' => 'bool',
            'testMixed4' => [1, 2, 3],
            'testMixed5' => '1.1',
            'testMixed6' => null,
        ]);

        $this->assertEquals('string', $request->testMixed1());
        $this->assertEquals('1', $request->testMixed2());
        $this->assertEquals('bool', $request->testMixed3());
        $this->assertEquals([1, 2, 3], $request->testMixed4());
        $this->assertEquals('1.1', $request->testMixed5());
        $this->assertEquals(null, $request->testMixed6());
    }

    public function test_Empty()
    {
        $request = new BaseTypesRequest();
        $request->merge([
            'testEmptyType1' => 'string',
            'testEmptyType2' => '1',
            'testEmptyType3' => 'bool',
            'testEmptyType4' => [1, 2, 3],
            'testEmptyType5' => '1.1',
            'testEmptyType6' => null,
        ]);

        $this->assertEquals('string', $request->testEmptyType1());
        $this->assertEquals('1', $request->testEmptyType2());
        $this->assertEquals('bool', $request->testEmptyType3());
        $this->assertEquals([1, 2, 3], $request->testEmptyType4());
        $this->assertEquals('1.1', $request->testEmptyType5());
        $this->assertEquals(null, $request->testEmptyType6());
    }
}
