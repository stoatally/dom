<?php

namespace Stoatally\Dom;

use DomNodeList;
use LogicException;
use OutOfBoundsException;

class ArrayIteratorTest extends IteratorTest
{
    protected function create($html)
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString($html);
        $items = [];

        foreach ($document->childNodes as $child) {
            $items[] = $child;
        }

        return [$document, new ArrayIterator($items)];
    }

    protected function createEmpty()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/>');

        return [$document, new ArrayIterator([])];
    }

    public function testCreateIteratorFromArray()
    {
        list($document, $iterator) = $this->create('<a/><b/><c/>');

        $this->assertTrue($iterator instanceof Iterator);
        $this->assertEquals(3, count($iterator));
    }
}