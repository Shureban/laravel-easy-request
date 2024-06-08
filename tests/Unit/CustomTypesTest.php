<?php

namespace Shureban\LaravelEasyRequest\Tests\Unit;

use Carbon\Carbon;
use DateTime;
use Shureban\LaravelEasyRequest\Exceptions\UndefinedClassException;
use Shureban\LaravelEasyRequest\Tests\Unit\Requests\CustomTypesRequest;
use Tests\TestCase;

class CustomTypesTest extends TestCase
{
    public function test_DateTime()
    {
        $request = new CustomTypesRequest();
        $request->merge(['testDateTime' => '2024-01-01']);

        $this->assertTrue($request->testDateTime() instanceof DateTime);
        $this->assertEquals($request->testDateTime(), new DateTime('2024-01-01'));
    }

    public function test_Carbon()
    {
        $request = new CustomTypesRequest();
        $request->merge(['testCarbon' => '2024-01-01']);

        $this->assertTrue($request->testCarbon() instanceof Carbon);
        $this->assertEquals($request->testCarbon(), new Carbon('2024-01-01'));
    }

    public function test_UndefinedClassException()
    {
        $request = new CustomTypesRequest();
        $request->merge(['testUndefinedClass' => 'some value']);

        $this->expectException(UndefinedClassException::class);

        $request->testUndefinedClass();
    }
}
