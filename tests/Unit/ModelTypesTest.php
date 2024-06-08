<?php

namespace Shureban\LaravelEasyRequest\Tests\Unit;

use Shureban\LaravelEasyRequest\Tests\Unit\Models\TestModel;
use Shureban\LaravelEasyRequest\Tests\Unit\Requests\ModelTypesRequest;
use Tests\TestCase;

class ModelTypesTest extends TestCase
{
    public function test_Model()
    {
        $request = new ModelTypesRequest();
        $request->merge(['test_model_id' => 1]);

        $this->assertInstanceOf(TestModel::class, $request->testModel());
    }
}
