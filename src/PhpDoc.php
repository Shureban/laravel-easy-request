<?php

namespace Shureban\LaravelEasyRequest;

/**
 * Class PhpDoc
 *
 * Represents a PHPDoc and provides methods to retrieve information from it.
 */
class PhpDoc
{
    private const MethodNameRegex = '/method(\s+(?<type>\w+)\|?(?<mightBeNull>null)?)?\s+(?<name>\w+)\(\)/U';

    private string $phpDoc;
    private array  $methods = [];

    /**
     * @param string $phpDoc
     */
    public function __construct(string $phpDoc)
    {
        $this->phpDoc = $phpDoc;
    }

    /**
     * Retrieves all methods from the given PHPDoc.
     *
     * @return array An array containing all method names found in the PHPDoc.
     */
    public function methods(): array
    {
        if (!empty($this->methods)) {
            return $this->methods;
        }

        $methodsCount = preg_match_all(self::MethodNameRegex, $this->phpDoc, $regexResult);

        $emptyMethodList = $methodsCount === 0;
        if ($emptyMethodList) {
            return [];
        }

        for ($i = 0; $i < $methodsCount; $i++) {
            $methodName      = $regexResult['name'][$i];
            $methodType      = $regexResult['type'][$i];
            $mightBeNull     = !empty($regexResult['mightBeNull'][$i]);
            $this->methods[] = new Method($methodName, $methodType, $mightBeNull);
        }

        return $this->methods;
    }

    /**
     * Check if the given method exists in the methods array.
     *
     * @param string $method The method to check
     *
     * @return bool True if the method exists, false otherwise
     */
    public function hasMethod(string $method): bool
    {
        return !empty($this->getMethod($method));
    }

    public function getMethod(string $method): ?Method
    {
        $result = array_filter($this->methods(), fn(Method $phpDocMethod) => $phpDocMethod->getName() === $method);

        return current($result) ?: null;
    }
}
