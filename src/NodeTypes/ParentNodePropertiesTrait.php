<?php

namespace Stoatally\Dom\NodeTypes;

trait ParentNodePropertiesTrait
{
    private function getChildNodes()
    {
        return $this->getChildren();
    }

    private function getNodeValue()
    {
        return $this->getLibxml()->nodeValue;
    }

    private function setNodeValue($value)
    {
        return $this->getLibxml()->nodeValue = $value;
    }
}