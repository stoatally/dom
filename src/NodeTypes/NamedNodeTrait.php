<?php

namespace Stoatally\Dom\NodeTypes;

trait NamedNodeTrait
{
    public function getName(): string
    {
        return $this->libxml->tagName;
    }
}