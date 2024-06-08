<?php

namespace Shureban\LaravelEasyRequest;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use ReflectionClass;
use ReflectionException;
use Shureban\LaravelEasyRequest\Attributes\RequestFieldName;
use Shureban\LaravelEasyRequest\Enums\MethodType;
use Shureban\LaravelEasyRequest\Exceptions\UndefinedClassException;

abstract class EasyRequest extends FormRequest
{
    private array $models = [];

    /**
     * @param $method
     * @param $parameters
     *
     * @return mixed
     * @throws UndefinedClassException|ReflectionException
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

        $fieldName = (string)new RequestFieldName($this, $phpDocMethod->getName());

        $hasNotValue = !$this->has($fieldName);
        if ($hasNotValue && $phpDocMethod->mightBeNull()) {
            return null;
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
        $fieldName      = (string)new RequestFieldName($this, $method->getName());
        $value          = $this->input($fieldName);
        $classNamespace = $classInformation->getFullObjectUseNamespace($method->getType());

        if (!class_exists($classNamespace)) {
            throw new UndefinedClassException($method->getType());
        }

        $instance = (new ReflectionClass($classNamespace))->newInstanceWithoutConstructor();

        if ($instance instanceof Model) {
            if (empty($this->models[$fieldName])) {
                $this->models[$fieldName] = $instance->find($value);
            }

            return $this->models[$fieldName];
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
        $fieldName = (string)new RequestFieldName($this, $method->getName());

        return match ($method->getMethodType()) {
            MethodType::String                    => $this->string($fieldName),
            MethodType::Boolean, MethodType::Bool => $this->boolean($fieldName),
            MethodType::Integer, MethodType::Int  => $this->integer($fieldName),
            MethodType::Float                     => $this->float($fieldName),
            MethodType::Array                     => (array)$this->input($fieldName),
            default                               => $this->input($fieldName),
        };
    }
}
