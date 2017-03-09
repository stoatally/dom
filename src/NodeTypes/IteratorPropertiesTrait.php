<?php

namespace Stoatally\Dom\NodeTypes;

trait IteratorPropertiesTrait
{
    use PropertiesTrait;

    private function getOwnerDocument(): Document
    {
        return $this->getDocument();
    }
}