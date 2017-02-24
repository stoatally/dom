<?php

namespace Stoatally\Dom\NodeTypes;

use Stoatally\Dom\Nodes;

trait QueryableNodeTrait
{
    public function select(string $query): Iterator
    {
        $results = $this->getXPathResults($query);
        $results->rewind();

        return $results;
    }

    private function getXPathResults($query): Iterator
    {
        $xpath = $this->getDocument()->getXPath();
        $element = $this->getNode();

        return new Nodes\Iterator($this->getDocument(), iterator_to_array($xpath->query($query, $element)));
    }
}