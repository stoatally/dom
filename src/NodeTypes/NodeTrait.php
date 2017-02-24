<?php

namespace Stoatally\Dom\NodeTypes;

use DomDocument;
use DomNode;
use Stoatally\Dom\Nodes;

trait NodeTrait {
    public function getDocument(): DomDocument
    {
        return $this->ownerDocument;
    }

    public function getNode(): DomNode
    {
        return $this;
    }

    public function import($value): Node
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

    public function duplicate(int $times): Iterator
    {
        if ($times < 2) {
            return new Nodes\Iterator($this->getDocument(), [$this]);
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

        return new Nodes\Iterator($this->getDocument(), $results);
    }

    public function repeat($items, ?Callable $callback = null): Iterator
    {
        return $this->duplicate(count($items))->fill($items, $callback);
    }
}