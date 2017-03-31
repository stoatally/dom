<?php

namespace Stoatally\Dom\Nodes;

use Stoatally\Dom\NodeTypes;

trait QueryableNodeTrait
{
    public function select(string $query): NodeTypes\Iterator
    {
        $results = $this->getXPathResults($query);
        $results->rewind();

        return $results;
    }

    private function getXPathResults($query): NodeTypes\Iterator
    {
        $xpath = $this->getDocument()->getXPath();
        $element = $this->getNode();

        return new Iterator($this->getDocument(), iterator_to_array($xpath->query($query, $element)));
    }
}