<?php

namespace Stoatally\Dom\NodeTypes;

use DomNode;

interface LibxmlNode
{
    public function getLibxml(): DomNode;

    public function setLibxml(DomNode $libxml);
}