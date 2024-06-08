<?php

namespace Shureban\LaravelEasyRequest\Attributes;

use Illuminate\Foundation\Http\FormRequest;
use Str;
use Stringable;

class RequestFieldName implements Stringable
{
    private FormRequest $request;
    private string      $originalName;

    /**
     * @param FormRequest $request
     * @param string      $originalName
     */
    public function __construct(FormRequest $request, string $originalName)
    {
        $this->request      = $request;
        $this->originalName = $originalName;
    }

    public function __toString(): string
    {
        $snakeCaseName       = Str::snake($this->originalName);
        $camelCaseName       = Str::camel($snakeCaseName);
        $snakeCaseWithIdName = Str::snake($this->originalName) . '_id';
        $camelCaseWithIdName = Str::camel($snakeCaseName) . 'Id';

        return match (true) {
            $this->request->has($this->originalName)  => $this->originalName,
            $this->request->has($snakeCaseName)       => $snakeCaseName,
            $this->request->has($camelCaseName)       => $camelCaseName,
            $this->request->has($snakeCaseWithIdName) => $snakeCaseWithIdName,
            $this->request->has($camelCaseWithIdName) => $camelCaseWithIdName,
            default                                   => $this->originalName,
        };
    }
}
