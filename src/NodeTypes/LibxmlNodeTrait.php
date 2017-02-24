<?php

namespace Stoatally\Dom\NodeTypes;

use DomNode;

trait LibxmlNodeTrait
{
    private $libxml;

    public function __clone() {
        $this->setLibxml($this->getLibxml()->cloneNode(true));
    }

    public function getLibxml(): DomNode
    {
        return $this->libxml;
    }

    public function setLibxml(DomNode $libxml)
    {
        $this->libxml = $libxml;
        $libxml->native = $this;
    }
}