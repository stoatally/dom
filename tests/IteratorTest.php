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

    public function testAccessFirstItem()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/><b/><c/>');
        $iterator = new Iterator($document->childNodes);

        $this->assertEquals('a', $iterator[0]->tagName);
    }

    public function testAccessLastItem()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/><b/><c/>');
        $iterator = new Iterator($document->childNodes);

        $this->assertEquals('c', $iterator[2]->tagName);
        $this->assertEquals('c', $iterator[-1]->tagName);
    }

    public function testAccessOutOfBoundsItem()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/><b/><c/>');
        $iterator = new Iterator($document->childNodes);

        $this->expectException(OutOfBoundsException::class);
        $iterator[100];
    }

    public function testSetImmutability()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/><b/><c/>');
        $iterator = new Iterator($document->childNodes);

        $this->expectException(LogicException::class);
        $iterator[1] = $iterator[-1];
    }

    public function testUnsetImmutability()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/><b/><c/>');
        $iterator = new Iterator($document->childNodes);

        $this->expectException(LogicException::class);
        unset($iterator[-1]);
    }

    public function testGetDocument()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/><b/><c/>');
        $iterator = new Iterator($document->childNodes);

        $this->assertEquals($document, $iterator->getDocument());
    }

    public function testGetDocumentWhenEmpty()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/>');

        $this->expectException(LogicException::class);
        (new Iterator($document->documentElement->childNodes))->getDocument();
    }

    public function testSetContents()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/>');
        $iterator = new Iterator($document->childNodes);

        $iterator->set('1');

        $this->assertEquals('1', $iterator[0]->nodeValue);
    }

    public function testSetContentsWhenEmpty()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/>');

        $this->expectException(LogicException::class);
        (new Iterator($document->documentElement->childNodes))->set('1');
    }

    public function testGetContents()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a>1</a>');
        $iterator = new Iterator($document->childNodes);

        $this->assertEquals('1', $iterator->get());
    }

    public function testGetContentsWhenEmpty()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/>');

        $this->expectException(LogicException::class);
        (new Iterator($document->documentElement->childNodes))->get();
    }

    public function testImportNode()
    {
        $documentFactory = new DocumentFactory();
        $documentA = $documentFactory->createFromString('<a/>');
        $documentB = $documentFactory->createFromString('<b/>');
        $iteratorA = new Iterator($documentA->childNodes);
        $iteratorB = new Iterator($documentB->childNodes);

        $result = $iteratorA->import($iteratorB[0]);
        $documentA->appendChild($result);

        $this->assertEquals($documentA, $result->ownerDocument);
        $this->assertEquals("<a></a><b></b>\n", $documentA->saveHtml());
    }

    public function testImportNodeWhenEmpty()
    {
        $documentFactory = new DocumentFactory();
        $documentA = $documentFactory->createFromString('<a/>');
        $documentB = $documentFactory->createFromString('<b/>');
        $iteratorA = new Iterator($documentA->documentElement->childNodes);
        $iteratorB = new Iterator($documentB->childNodes);

        $this->expectException(LogicException::class);
        $iteratorA->import($iteratorB[0]);
    }

    public function testImportIterator()
    {
        $documentFactory = new DocumentFactory();
        $documentA = $documentFactory->createFromString('<a/>');
        $documentB = $documentFactory->createFromString('<b/>');
        $iteratorA = new Iterator($documentA->childNodes);
        $iteratorB = new Iterator($documentB->childNodes);

        $result = $iteratorA->import($iteratorB);
        $documentA->appendChild($result);

        $this->assertEquals($documentA, $result->ownerDocument);
        $this->assertEquals("<a></a><b></b>\n", $documentA->saveHtml());
    }

    public function testAppendSibling()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/>');
        $iterator = new Iterator($document->childNodes);

        $iterator->after(
            $document->createElement('b')
        );

        $this->assertEquals("<a></a><b></b>\n", $document->saveHtml());
    }

    public function testAppendSiblingWhenEmpty()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/>');
        $iterator = new Iterator($document->documentElement->childNodes);

        $this->expectException(LogicException::class);
        $iterator->after(
            $document->createElement('b')
        );
    }

    public function testPrependSibling()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<b/>');
        $iterator = new Iterator($document->childNodes);

        $iterator->before(
            $document->createElement('a')
        );

        $this->assertEquals("<a></a><b></b>\n", $document->saveHtml());
    }

    public function testPrependSiblingWhenEmpty()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<b/>');
        $iterator = new Iterator($document->documentElement->childNodes);

        $this->expectException(LogicException::class);
        $iterator->before(
            $document->createElement('a')
        );
    }

    public function testAppendChild()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/>');
        $iterator = new Iterator($document->childNodes);

        $iterator->append(
            $document->createElement('b')
        );

        $this->assertEquals("<a><b></b></a>\n", $document->saveHtml());
    }

    public function testAppendChildWhenEmpty()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/>');
        $iterator = new Iterator($document->documentElement->childNodes);

        $this->expectException(LogicException::class);
        $iterator->append(
            $document->createElement('b')
        );
    }

    public function testPrependChild()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/>');
        $iterator = new Iterator($document->childNodes);

        $iterator->prepend(
            $document->createElement('b')
        );

        $this->assertEquals("<a><b></b></a>\n", $document->saveHtml());
    }

    public function testPrependChildWhenEmpty()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/>');
        $iterator = new Iterator($document->documentElement->childNodes);

        $this->expectException(LogicException::class);
        $iterator->prepend(
            $document->createElement('b')
        );
    }

    public function testReplaceSelf()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/>');
        $iterator = new Iterator($document->childNodes);

        $iterator->replace(
            $document->createElement('b')
        );

        $this->assertEquals("<b></b>\n", $document->saveHtml());
    }

    public function testReplaceSelfWhenEmpty()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/>');
        $iterator = new Iterator($document->documentElement->childNodes);

        $this->expectException(LogicException::class);
        $iterator->replace(
            $document->createElement('b')
        );
    }
}