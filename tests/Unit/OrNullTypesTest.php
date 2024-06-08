<?php

namespace Shureban\LaravelEasyRequest\Tests\Unit;

use Shureban\LaravelEasyRequest\Tests\Unit\Requests\OrNullTypesRequest;
use Tests\TestCase;

class OrNullTypesTest extends TestCase
{
    public function test_String()
    {
        $request = new OrNullTypesRequest();

        $this->assertEquals(null, $request->testString());

        $request->merge(['testString' => 'Test name']);

        $this->assertEquals('Test name', $request->testString());
    }

    public function test_Boolean()
    {
        $request = new OrNullTypesRequest();

        $this->assertEquals(null, $request->testBoolean());
        $this->assertEquals(null, $request->testBool());

        $request->merge(['testBoolean' => 'true']);
        $request->merge(['testBool' => 'true']);

        $this->assertEquals(true, $request->testBoolean());
        $this->assertEquals(true, $request->testBool());
    }

    public function test_Integer()
    {
        $request = new OrNullTypesRequest();

        $this->assertEquals(null, $request->testInteger());
        $this->assertEquals(null, $request->testInt());

        $request->merge(['testInteger' => '1']);
        $request->merge(['testInt' => '1']);

        $this->assertEquals(1, $request->testInteger());
        $this->assertEquals(1, $request->testInt());
    }

    public function test_Float()
    {
        $request = new OrNullTypesRequest();

        $this->assertEquals(null, $request->testFloat());

        $request->merge(['testFloat' => '1']);

        $this->assertEquals(1, $request->testFloat());
    }

    public function test_Array()
    {
        $request = new OrNullTypesRequest();

        $this->assertEquals(null, $request->testArray());

        $request->merge(['testArray' => [[], 'a' => ['b', 'c' => 'd'], 1 => 12]]);

        $this->assertEquals([[], 'a' => ['b', 'c' => 'd'], 1 => 12], $request->testArray());
    }

    public function test_Mixed()
    {
        $request = new OrNullTypesRequest();

        $this->assertEquals(null, $request->testMixed());
        
        $request->merge(['testMixed' => 'string']);

        $this->assertEquals('string', $request->testMixed());
    }
}
