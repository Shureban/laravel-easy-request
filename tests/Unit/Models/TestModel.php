<?php

namespace Shureban\LaravelEasyRequest\Tests\Unit\Models;

use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    protected $fillable = [
        'id',
    ];

    public static function find(mixed $value): static
    {
        return new TestModel(['id' => $value]);
    }
}
