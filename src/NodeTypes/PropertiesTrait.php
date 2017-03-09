<?php

namespace Stoatally\Dom\NodeTypes;

use Stoatally\Dom\Exceptions\ReadOnlyPropertyException;
use Stoatally\Dom\Exceptions\UndefinedPropertyException;
use ReflectionException;
use ReflectionObject;

trait PropertiesTrait
{
    public function __get($name)
    {
        $method = $this->getPropertyGetter($name);

        return $this->{$method}();
    }

    public function __set($name, $value) {
        $method = $this->getPropertySetter($name);

        return $this->{$method}($value);
    }

    private function getPropertyGetter($name)
    {
        $reflection = new ReflectionObject($this);
        $undefined = false;

        try {
            $method = $reflection->getMethod('get' . $name);
            $undefined = (
                $method->isPublic()
                || $method->getNumberOfRequiredParameters()
            );
        }

        catch (ReflectionException $error) {
            $undefined = true;
        }

        finally {
            if ($undefined) {
                throw $this->createUndefinedPropertyException($name);
            }
        }

        return $method->getName();
    }

    private function getPropertySetter($name)
    {
        $reflection = new ReflectionObject($this);
        $readOnly = false;
        $undefined = false;

        try {
            $method = $reflection->getMethod('set' . $name);
            $undefined = (
                $method->isPublic()
                || $method->getNumberOfRequiredParameters() !== 1
            );
        }

        catch (ReflectionException $error) {
            $undefined = true;

            try {
                $this->getPropertyGetter($name);
            }

            catch (UndefinedPropertyException $error) {
                $readOnly = true;
            }
        }

        finally {
            if ($readOnly) {
                throw $this->createReadOnlyPropertyException($name);
            }

            else if ($undefined) {
                throw $this->createUndefinedPropertyException($name);
            }
        }

        return $method->getName();
    }

    private function createReadOnlyPropertyException($name)
    {
        return new ReadOnlyPropertyException(sprintf(
            'Read only property: %s::$%s',
            static::class,
            $name
        ));
    }

    private function createUndefinedPropertyException($name)
    {
        return new UndefinedPropertyException(sprintf(
            'Undefined property: %s::$%s',
            static::class,
            $name
        ));
    }
}