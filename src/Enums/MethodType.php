<?php

namespace Shureban\LaravelEasyRequest\Enums;

enum MethodType: string
{
    case String  = 'string';
    case Boolean = 'boolean';
    case Bool    = 'bool';
    case Int     = 'int';
    case Integer = 'integer';
    case Float   = 'float';
    case Array   = 'array';
    case Mixed   = 'mixed';
    case Object  = 'object';
    case Custom  = 'custom';
    case Empty   = '';
}
