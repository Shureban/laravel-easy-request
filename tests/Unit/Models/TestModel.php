<?php

namespace Shureban\LaravelEasyRequest\Tests\Unit\Models;

use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    public static function find(): static
    {
        return new TestModel();
    }
}
