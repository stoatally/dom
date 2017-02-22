<?php

namespace Stoatally\DocumentObjectModel;

use DomDocument;
use DomNode;

class Document extends DomDocument implements ImportableNode
{
    use NodeTrait;

    public function getDocument()
    {
        return $this;
    }

    public function getImportableNode(): DomNode
    {
        $fragment = $this->createDocumentFragment();

        foreach ($this->childNodes as $node) {
            $fragment->appendChild($node->cloneNode(true));
        }

        return $fragment;
    }
}