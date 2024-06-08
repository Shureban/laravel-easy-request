<?php

namespace Shureban\LaravelEasyRequest;

use Shureban\LaravelEasyRequest\Enums\MethodType;

class Method
{
    private string $name;
    private string $type;
    private bool   $mightBeNull = false;

    /**
     * @param string $name
     * @param string $type
     * @param bool   $mightBeNull
     */
    public function __construct(string $name, string $type, bool $mightBeNull)
    {
        $this->name        = $name;
        $this->type        = $type;
        $this->mightBeNull = $mightBeNull;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getMethodType(): MethodType
    {
        return MethodType::tryFrom($this->type) ?? MethodType::Custom;
    }

    public function mightBeNull(): bool
    {
        return $this->mightBeNull;
    }
}
