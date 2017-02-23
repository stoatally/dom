<?php

namespace Stoatally\Dom;

use DomDocument;
use DomNode;

trait NodeTrait {
    public function getDocument(): DomDocument
    {
        return $this->ownerDocument;
    }

    public function getNode(): DomNode
    {
        return $this;
    }

    public function import($value): DomNode
    {
        if ($value instanceof ImportableNode) {
            $value = $value->getImportableNode();
        }

        return $this->importOrCreateNode($value);
    }

    private function importOrCreateNode($value): DomNode
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

    public function set($value): DomNode
    {
        $this->nodeValue = null;
        $this->appendChild($this->import($value));

        return $this;
    }

    public function get(): ?string
    {
        return $this->nodeValue;
    }

    public function after($value): DomNode
    {
        $node = $this->import($value);

        if (isset($this->nextSibling)) {
            return $this->parentNode->insertBefore($node, $this->nextSibling);
        }

        return $this->parentNode->appendChild($node);
    }

    public function before($value): DomNode
    {
        return $this->parentNode->insertBefore($this->import($value), $this);
    }

    public function append($value): DomNode
    {
        return $this->appendChild($this->import($value));
    }

    public function prepend($value): DomNode
    {
        $node = $this->import($value);

        if ($this->firstChild) {
            return $this->insertBefore($node, $this->firstChild);
        }

        return $this->appendChild($node);
    }

    public function replace($value): DomNode
    {
        return $this->parentNode->replaceChild($this->import($value), $this);
    }

    public function duplicate(int $times): Iterator
    {
        if ($times < 2) {
            return new ArrayIterator([$this]);
        }

        $results = [$this];
        $item = $this;

        foreach (range(1, $times - 1) as $index) {
            $clone = $results[] = $item->cloneNode(true);

            if ($item->parentNode) {
                $item->after($clone);
                $item = $clone;
            }
        }

        return new ArrayIterator($results);
    }

    public function repeat($items, ?Callable $callback = null): Iterator
    {
        return $this->duplicate(count($items))->fill($items, $callback);
    }
}