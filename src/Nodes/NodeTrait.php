<?php

namespace Stoatally\Dom\Nodes;

use Stoatally\Dom\NodeTypes;

trait NodeTrait {
    public function getDocument(): NodeTypes\Document
    {
        return $this->ownerDocument;
    }

    public function getNode(): NodeTypes\Node
    {
        return $this;
    }

    public function import($value): NodeTypes\Node
    {
        if ($value instanceof NodeTypes\ImportableNode) {
            $value = $value->getImportableNode();
        }

        return $this->importOrCreateNode($value);
    }

    private function importOrCreateNode($value): NodeTypes\Node
    {
        $document = $this->getDocument();

        if ($value instanceof NodeTypes\Node) {
            if ($value->ownerDocument === $document) {
                return $value;
            }

            return $document->importNode($value, true);
        }

        return $document->createTextNode((string) $value);
    }

    public function getContents(): ?string
    {
        return $this->nodeValue;
    }

    public function setContents($value): NodeTypes\Node
    {
        $this->nodeValue = null;
        $this->appendChild($this->import($value));

        return $this;
    }

    public function append($value): NodeTypes\Node
    {
        return $this->appendChild($this->import($value));
    }

    public function prepend($value): NodeTypes\Node
    {
        $node = $this->import($value);

        if ($this->firstChild) {
            return $this->insertBefore($node, $this->firstChild);
        }

        return $this->appendChild($node);
    }

    public function duplicateNode(int $times): NodeTypes\Iterator
    {
        if ($times < 2) {
            return new Iterator($this->getDocument(), [$this]);
        }

        $results = [$this];
        $item = $this;

        foreach (range(1, $times - 1) as $index) {
            $clone = $results[] = $item->cloneNode(true);

            if ($item->parentNode) {
                $item->appendSibling($clone);
                $item = $clone;
            }
        }

        return new Iterator($this->getDocument(), $results);
    }

    public function repeatNode($items, ?Callable $callback = null): NodeTypes\Iterator
    {
        return $this->duplicateNode(count($items))->fillNodes($items, $callback);
    }
}