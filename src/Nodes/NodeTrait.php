<?php

namespace Stoatally\Dom\Nodes;

use BadMethodCallException;
use DOMNode;
use Stoatally\Dom\NodeTypes;

trait NodeTrait
{
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
        $this->append($value);

        return $this;
    }

    /**
     * @deprecated  Use the `append` method instead as it behaves in the same was as `prepend`.
     */
    public function appendChild(DOMNode $node)
    {
        throw new BadMethodCallException('Deprecated method `appendChild`.');
    }

    public function append($value): NodeTypes\Node
    {
        return parent::appendChild($this->import($value));
    }

    public function prepend($value): NodeTypes\Node
    {
        if ($this->firstChild) {
            return $this->insertBefore($this->import($value), $this->firstChild);
        }

        return $this->append($value);
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

    public function extractNode(): NodeTypes\Iterator
    {
        $children = new Iterator($this->getDocument(), iterator_to_array($this->childNodes));

        foreach ($children as $child) {
            $this->prependSibling($child);
        }

        $this->removeNode();

        return $children;
    }

    public function removeNode(): NodeTypes\Node
    {
        if ($this->parentNode) {
            $this->parentNode->removeChild($this);
        }

        return $this;
    }

    public function repeatNode($items, ?Callable $callback = null): NodeTypes\Iterator
    {
        return $this->duplicateNode(count($items))->fillNodes($items, $callback);
    }
}