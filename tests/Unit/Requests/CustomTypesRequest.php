<?php

namespace Shureban\LaravelEasyRequest\Tests\Unit\Requests;

use Carbon\Carbon;
use DateTime;
use Shureban\LaravelEasyRequest\EasyRequest;

/**
 * @method DateTime testDateTime()
 * @method Carbon testCarbon()
 * @method SomeUnrealClass testUndefinedClass()
 *
 * @package Shureban\LaravelEasyRequest\Tests\Unit\Requests
 */
class CustomTypesRequest extends EasyRequest
{
}
