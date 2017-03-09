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
        $xpath = $this->ownerDocument->getXPath();
        $element = $this->getNode();
        $results = [];

        foreach ($xpath->query($query, $element->getLibxml()) as $result) {
            $results[] = $result->native;
        }

        return new Nodes\Iterator($this->ownerDocument, $results);
    }
}