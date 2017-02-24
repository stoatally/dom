<?php

namespace Stoatally\Dom\NodeTypes;

use DomNode;
use Stoatally\Dom\Nodes;

trait NodeTrait
{
    public function getDocument(): Document
    {
        return $this->getLibxml()->ownerDocument->native;
    }

    public function getParent(): Node
    {
        return $this->getLibxml()->parentNode->native;
    }

    public function hasParent(): bool
    {
        return isset($this->getLibxml()->parentNode);
    }

    public function getNode(): Node
    {
        return $this;
    }

    public function importNode($value): Node
    {
        if ($value instanceof ImportableNode) {
            $value = $value->getImportableNode();
        }

        return $this->importOrCreateNode($value);
    }

    private function importOrCreateNode($value): Node
    {
        $document = $this->getDocument();

        if ($value instanceof Node) {
            if ($value->getDocument()->getLibxml() !== $document->getLibxml()) {
                $value->setLibxml(
                    $document->getLibxml()->importNode($value->getLibxml(), true)
                );
            }

            return $value;
        }

        return $document->createTextNode((string) $value);
    }

    public function duplicate(int $times): Iterator
    {
        if ($times < 2) {
            return new Nodes\Iterator($this->getDocument(), [$this]);
        }

        $results = [$this];
        $item = $this;

        foreach (range(1, $times - 1) as $index) {
            $clone = $results[] = clone $item;

            if ($item->getParent()) {
                $item->appendSibling($clone);
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