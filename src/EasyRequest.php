<?php

namespace Shureban\LaravelEasyRequest;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use ReflectionClass;
use ReflectionException;
use Shureban\LaravelEasyRequest\Enums\MethodType;
use Shureban\LaravelEasyRequest\Exceptions\UndefinedClassException;
use Str;

abstract class EasyRequest extends FormRequest
{
    /**
     * @param $method
     * @param $parameters
     *
     * @return mixed
     * @throws UndefinedClassException
     */
    public function __call($method, $parameters)
    {
        $reflection       = new ReflectionClass(static::class);
        $classInformation = new ClassExtraInformation($reflection);
        $phpDocMethod     = $classInformation->phpDoc()->getMethod($method);

        $phpDocMethodDoesNotExists = is_null($phpDocMethod);
        if ($reflection->hasMethod($method) || $phpDocMethodDoesNotExists) {
            return parent::__call($phpDocMethod, $parameters);
        }

        return match ($phpDocMethod->getMethodType()) {
            MethodType::Custom => $this->processCustomType($classInformation, $phpDocMethod),
            default            => $this->processBaseType($phpDocMethod),
        };
    }

    /**
     * @param ClassExtraInformation $classInformation
     * @param Method                $method
     *
     * @return mixed
     * @throws UndefinedClassException|ReflectionException
     */
    private function processCustomType(ClassExtraInformation $classInformation, Method $method): mixed
    {
        $paramName = $this->getParamName($method);

        $hasNotValue = !$this->has($paramName);
        if ($hasNotValue && $method->mightBeNull()) {
            return null;
        }

        $value          = $this->input($paramName);
        $classNamespace = $classInformation->getFullObjectUseNamespace($method->getType());

        if (!class_exists($classNamespace)) {
            throw new UndefinedClassException($method->getType());
        }

        /** @var Model $instance */
        $instance = (new ReflectionClass($classNamespace))->newInstanceWithoutConstructor();

        if ($instance instanceof Model) {
            return $instance->find($paramName);
        }

        return new $classNamespace($value);
    }

    /**
     * @param Method $method
     *
     * @return mixed
     */
    private function processBaseType(Method $method): mixed
    {
        $paramName = $this->getParamName($method);

        $hasNotValue = !$this->has($paramName);
        if ($hasNotValue && $method->mightBeNull()) {
            return null;
        }

        return match ($method->getMethodType()) {
            MethodType::String                    => $this->string($paramName),
            MethodType::Boolean, MethodType::Bool => $this->boolean($paramName),
            MethodType::Integer, MethodType::Int  => $this->integer($paramName),
            MethodType::Float                     => $this->float($paramName),
            MethodType::Array                     => (array)$this->input($paramName),
            default                               => $this->input($paramName),
        };
    }

    private function getParamName(Method $method): string
    {
        $snakeCaseName       = Str::snake($method->getName());
        $snakeCaseWithIdName = Str::snake($method->getName()) . '_id';
        $camelCaseName       = Str::camel($snakeCaseName);
        $camelCaseWithIdName = Str::camel($snakeCaseName) . 'Id';

        return match (true) {
            $this->has($method->getName())   => $method->getName(),
            $this->has($snakeCaseName)       => $snakeCaseName,
            $this->has($snakeCaseWithIdName) => $snakeCaseWithIdName,
            $this->has($camelCaseName)       => $camelCaseName,
            $this->has($camelCaseWithIdName) => $camelCaseWithIdName,
            default                          => $method->getName(),
        };
    }
}
