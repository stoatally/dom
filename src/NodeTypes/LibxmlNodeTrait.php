<?php

namespace Stoatally\Dom\NodeTypes;

use DomNode;
use Stoatally\Dom\LibxmlToNativeTranslator;

trait LibxmlNodeTrait
{
    private $libxml;

    public function __clone() {
        $translator = new LibxmlToNativeTranslator();
        $libxml = $this->getLibxml()->cloneNode(true);

        $translator([$libxml]);

        $this->setLibxml($libxml);
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