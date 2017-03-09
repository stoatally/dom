<?php

namespace Stoatally\Dom\NodeTypes;

use DomDocument;
use Stoatally\Dom\Exceptions\ReadOnlyPropertyException;
use Stoatally\Dom\Exceptions\UndefinedPropertyException;
use ReflectionException;
use ReflectionObject;

trait NodePropertiesTrait
{
    use PropertiesTrait;

    private function getOwnerDocument(): Document
    {
        if ($this->getLibxml() instanceof DomDocument) {
            return $this->getLibxml()->native;
        }

        return $this->getLibxml()->ownerDocument->native;
    }
}