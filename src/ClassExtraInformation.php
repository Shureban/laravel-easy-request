<?php

namespace Shureban\LaravelEasyRequest;

use ReflectionClass;

class ClassExtraInformation
{
    private const NamespaceRegex    = '/namespace (?<namespace>[\\a-zA-Z0-9]+)(as .*)?;/U';
    private const UseRegex          = '/^use.*$/m';
    private const UseNamespaceRegex = '/use (?<namespace>[\\a-zA-Z]+\\\(?<name>[a-zA-Z]+))( as (?<alias>\w+))?;/U';

    private ReflectionClass $class;
    private string          $content = '';

    /**
     * @param ReflectionClass $class
     */
    public function __construct(ReflectionClass $class)
    {
        $this->class = $class;
    }

    public function phpDoc(): PhpDoc
    {
        return new PhpDoc($this->class->getDocComment());
    }

    /**
     * @param string $objectName
     *
     * @return string|null
     */
    public function getFullObjectUseNamespace(string $objectName): ?string
    {
        if (class_exists($objectName)) {
            return $objectName;
        }

        if (preg_match_all(self::UseRegex, $this->getClassFileContent(), $useRegexResult)) {
            foreach (current($useRegexResult) as $useLine) {
                if (preg_match(self::UseNamespaceRegex, $useLine, $lineRegexResult) === 0) {
                    continue;
                }

                $useName = $lineRegexResult['alias'] ?? $lineRegexResult['name'] ?? '';

                if ($useName === $objectName) {
                    return $lineRegexResult['namespace'];
                }
            }
        }

        $namespace = sprintf('%s\%s', $this->getNamespace(), $objectName);

        return class_exists($namespace) ? $namespace : null;
    }

    /**
     * @return string|null
     */
    private function getNamespace(): ?string
    {
        if (preg_match(self::NamespaceRegex, $this->getClassFileContent(), $regexResult)) {
            return $regexResult['namespace'];
        }

        return null;
    }

    /**
     * @return string
     */
    private function getClassFileContent(): string
    {
        if ($this->content) {
            return $this->content;
        }

        $phpTagLine           = 1;
        $classDeclarationLine = $this->class->getStartLine() - 1 - $phpTagLine;
        $content              = array_slice(file($this->class->getFileName()), $phpTagLine, $classDeclarationLine);

        return $this->content = implode('', $content);
    }
}
