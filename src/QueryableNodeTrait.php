<?php

namespace Stoatally\Dom;

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

        return new NodeListIterator($xpath->query($query, $element));
    }
}