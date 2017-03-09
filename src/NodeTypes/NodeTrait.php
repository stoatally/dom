<?php

namespace Stoatally\Dom\NodeTypes;

use Stoatally\Dom\Nodes;

trait NodeTrait
{
    use NodePropertiesTrait;

    public function getDocument(): Document
    {
        return $this->getLibxml()->ownerDocument->native;
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
        $document = $this->ownerDocument;

        if ($value instanceof Node) {
            if ($value->ownerDocument->getLibxml() !== $document->getLibxml()) {
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
            return new Nodes\Iterator($this->ownerDocument, [$this]);
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

        return new Nodes\Iterator($this->ownerDocument, $results);
    }

    public function repeat($items, ?Callable $callback = null): Iterator
    {
        return $this->duplicate(count($items))->fill($items, $callback);
    }
}