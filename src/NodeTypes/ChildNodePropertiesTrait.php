<?php

namespace Stoatally\Dom\NodeTypes;

trait ChildNodePropertiesTrait
{
    private function getParentNode(): Node
    {
        return $this->getLibxml()->parentNode->native;
    }
}