<?php

namespace Stoatally\DocumentObjectModel;

use DomDocument;
use DomElement;
use DomNode;

trait NodeTrait {
    public function import($value): DomNode
    {
        if ($value instanceof ImportableNode) {
            $value = $value->getImportableNode();
        }

        return $this->importOrCreateNode($value);
    }

    private function importOrCreateNode($value)
    {
        $document = $this->getDocument();

        if ($value instanceof DomNode) {
            if ($value->ownerDocument === $document) {
                return $value;
            }

            return $document->importNode($value, true);
        }

        return $document->createTextNode((string) $value);
    }

    public function getDocument()
    {
        return $this->ownerDocument;
    }

    public function after($value) {
        $node = $this->import($value);

        if (isset($this->nextSibling)) {
            return $this->parentNode->insertBefore($node, $this->nextSibling);
        }

        return $this->parentNode->appendChild($node);
    }

    public function before($value)
    {
        return $this->parentNode->insertBefore($this->import($value), $this);
    }

    public function append($value)
    {
        return $this->appendChild($this->import($value));
    }

    public function prepend($value)
    {
        $node = $this->import($value);

        if ($this->firstChild) {
            return $this->insertBefore($node, $this->firstChild);
        }

        return $this->appendChild($node);
    }

    public function replace($value)
    {
        return $this->parentNode->replaceChild($this->import($value), $this);
    }

    public function set($value)
    {
        $this->nodeValue = null;
        $this->appendChild($this->import($value));

        return $this;
    }

    public function get()
    {
        return $this->nodeValue;
    }
}