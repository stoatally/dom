<?php

namespace Stoatally\DocumentObjectModel;

use DomNodeList;
use LogicException;
use PHPUnit\Framework\TestCase;
use OutOfBoundsException;

class IteratorTest extends TestCase
{
    public function testCreateIteratorFromNodeList()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/><b/><c/>');
        $iterator = new Iterator($document->childNodes);

        $this->assertTrue($iterator->getIterator() instanceof DomNodeList);
        $this->assertEquals(3, count($iterator));
    }

    public function testIteratorAccessFirstItem()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/><b/><c/>');
        $iterator = new Iterator($document->childNodes);

        $this->assertEquals('a', $iterator[0]->tagName);
    }

    public function testIteratorAccessLastItem()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/><b/><c/>');
        $iterator = new Iterator($document->childNodes);

        $this->assertEquals('c', $iterator[2]->tagName);
        $this->assertEquals('c', $iterator[-1]->tagName);
    }

    public function testIteratorAccessOutOfBoundsItem()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/><b/><c/>');
        $iterator = new Iterator($document->childNodes);

        $this->expectException(OutOfBoundsException::class);
        $iterator[100];
    }

    public function testIteratorSetImmutability()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/><b/><c/>');
        $iterator = new Iterator($document->childNodes);

        $this->expectException(LogicException::class);
        $iterator[1] = $iterator[-1];
    }

    public function testIteratorUnsetImmutability()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/><b/><c/>');
        $iterator = new Iterator($document->childNodes);

        $this->expectException(LogicException::class);
        unset($iterator[-1]);
    }
}