<?php

namespace Stoatally\Dom;

use DomNodeList;
use LogicException;
use PHPUnit\Framework\TestCase;
use OutOfBoundsException;

abstract class IteratorTest extends TestCase
{
    public function testAccessFirstItem()
    {
        list($document, $iterator) = $this->create('<a/><b/><c/>');

        $this->assertEquals('a', $iterator[0]->tagName);
    }

    public function testAccessLastItem()
    {
        list($document, $iterator) = $this->create('<a/><b/><c/>');

        $this->assertEquals('c', $iterator[2]->tagName);
        $this->assertEquals('c', $iterator[-1]->tagName);
    }

    public function testAccessOutOfBoundsItem()
    {
        list($document, $iterator) = $this->create('<a/><b/><c/>');

        $this->expectException(OutOfBoundsException::class);
        $iterator[100];
    }

    public function testSetImmutability()
    {
        list($document, $iterator) = $this->create('<a/><b/><c/>');

        $this->expectException(LogicException::class);
        $iterator[1] = $iterator[-1];
    }

    public function testUnsetImmutability()
    {
        list($document, $iterator) = $this->create('<a/><b/><c/>');

        $this->expectException(LogicException::class);
        unset($iterator[-1]);
    }

    public function testGetDocument()
    {
        list($document, $iterator) = $this->create('<a/><b/><c/>');

        $this->assertEquals($document, $iterator->getDocument());
    }

    public function testGetDocumentWhenEmpty()
    {
        list($document, $iterator) = $this->createEmpty();

        $this->expectException(LogicException::class);
        $iterator->getDocument();
    }

    public function testGetNode()
    {
        list($document, $iterator) = $this->create('<a/><b/><c/>');

        $this->assertEquals($document->firstChild, $iterator->getNode());
    }

    public function testGetNodeWhenEmpty()
    {
        list($document, $iterator) = $this->createEmpty();

        $this->expectException(LogicException::class);
        $iterator->getNode();
    }

    public function testSetContents()
    {
        list($document, $iterator) = $this->create('<a/>');

        $iterator->set('1');

        $this->assertEquals('1', $iterator[0]->nodeValue);
    }

    public function testSetContentsWhenEmpty()
    {
        list($document, $iterator) = $this->createEmpty();

        $this->expectException(LogicException::class);
        $iterator->set('1');
    }

    public function testGetContents()
    {
        list($document, $iterator) = $this->create('<a>1</a>');

        $this->assertEquals('1', $iterator->get());
    }

    public function testGetContentsWhenEmpty()
    {
        list($document, $iterator) = $this->createEmpty();

        $this->expectException(LogicException::class);
        $iterator->get();
    }

    public function testImportNode()
    {
        list($documentA, $iteratorA) = $this->create('<a/>');
        list($documentB, $iteratorB) = $this->create('<b/>');

        $result = $iteratorA->import($iteratorB[0]);
        $documentA->appendChild($result);

        $this->assertEquals($documentA, $result->ownerDocument);
        $this->assertEquals("<a></a><b></b>\n", $documentA->saveHtml());
    }

    public function testImportNodeWhenEmpty()
    {
        list($documentA, $iteratorA) = $this->createEmpty();
        list($documentB, $iteratorB) = $this->create('<b/>');

        $this->expectException(LogicException::class);
        $iteratorA->import($iteratorB[0]);
    }

    public function testImportIterator()
    {
        list($documentA, $iteratorA) = $this->create('<a/>');
        list($documentB, $iteratorB) = $this->create('<b/>');

        $result = $iteratorA->import($iteratorB);
        $documentA->appendChild($result);

        $this->assertEquals($documentA, $result->ownerDocument);
        $this->assertEquals("<a></a><b></b>\n", $documentA->saveHtml());
    }

    public function testAppendSibling()
    {
        list($document, $iterator) = $this->create('<a/>');

        $iterator->after(
            $document->createElement('b')
        );

        $this->assertEquals("<a></a><b></b>\n", $document->saveHtml());
    }

    public function testAppendSiblingWhenEmpty()
    {
        list($document, $iterator) = $this->createEmpty();

        $this->expectException(LogicException::class);
        $iterator->after(
            $document->createElement('b')
        );
    }

    public function testPrependSibling()
    {
        list($document, $iterator) = $this->create('<b/>');

        $iterator->before(
            $document->createElement('a')
        );

        $this->assertEquals("<a></a><b></b>\n", $document->saveHtml());
    }

    public function testPrependSiblingWhenEmpty()
    {
        list($document, $iterator) = $this->createEmpty();

        $this->expectException(LogicException::class);
        $iterator->before(
            $document->createElement('a')
        );
    }

    public function testAppendChild()
    {
        list($document, $iterator) = $this->create('<a/>');

        $iterator->append(
            $document->createElement('b')
        );

        $this->assertEquals("<a><b></b></a>\n", $document->saveHtml());
    }

    public function testAppendChildWhenEmpty()
    {
        list($document, $iterator) = $this->createEmpty();

        $this->expectException(LogicException::class);
        $iterator->append(
            $document->createElement('b')
        );
    }

    public function testPrependChild()
    {
        list($document, $iterator) = $this->create('<a/>');

        $iterator->prepend(
            $document->createElement('b')
        );

        $this->assertEquals("<a><b></b></a>\n", $document->saveHtml());
    }

    public function testPrependChildWhenEmpty()
    {
        list($document, $iterator) = $this->createEmpty();

        $this->expectException(LogicException::class);
        $iterator->prepend(
            $document->createElement('b')
        );
    }

    public function testReplaceSelf()
    {
        list($document, $iterator) = $this->create('<a/>');

        $iterator->replace(
            $document->createElement('b')
        );

        $this->assertEquals("<b></b>\n", $document->saveHtml());
    }

    public function testReplaceSelfWhenEmpty()
    {
        list($document, $iterator) = $this->createEmpty();

        $this->expectException(LogicException::class);
        $iterator->replace(
            $document->createElement('b')
        );
    }

    public function testDuplicateSelf()
    {
        list($document, $iterator) = $this->create('<a/>');

        $iterator->duplicate(3);

        $this->assertEquals("<a></a><a></a><a></a>\n", $document->saveHtml());
    }

    public function testDuplicateSelfWhenEmpty()
    {
        list($document, $iterator) = $this->createEmpty();

        $this->expectException(LogicException::class);
        $iterator->duplicate(3);
    }

    public function testRepeatSelf()
    {
        list($document, $iterator) = $this->create('<a/>');

        $results = $iterator->repeat([1, 2, 3]);

        $this->assertTrue($results instanceof Iterator);
        $this->assertEquals(3, count($results));
        $this->assertEquals("<a>1</a><a>2</a><a>3</a>\n", $document->saveHtml());
    }

    public function testRepeatSelfWhenEmpty()
    {
        list($document, $iterator) = $this->createEmpty();

        $this->expectException(LogicException::class);
        $iterator->repeat([1, 2, 3]);
    }

    public function testQuery()
    {
        list($document, $iterator) = $this->create('<a><b/><c/></a>');

        $results = $iterator->select('b');

        $this->assertTrue($results instanceof Iterator);
        $this->assertEquals(1, count($results));
    }

    public function testQueryWhenEmpty()
    {
        list($document, $iterator) = $this->createEmpty();

        $this->expectException(LogicException::class);
        $iterator->select('a');
    }

    public function testFillNodes()
    {
        list($document, $iterator) = $this->create('<a/><b/><c/>');

        $results = $iterator->fill([1, 2, 3]);

        $this->assertTrue($results instanceof Iterator);
        $this->assertEquals(3, count($results));
        $this->assertEquals("<a>1</a><b>2</b><c>3</c>\n", $document->saveHtml());
    }

    public function testUnderFillNodes()
    {
        list($document, $iterator) = $this->create('<a/><b/><c/>');

        $results = $iterator->fill([1]);

        $this->assertTrue($results instanceof Iterator);
        $this->assertEquals(3, count($results));
        $this->assertEquals("<a>1</a><b></b><c></c>\n", $document->saveHtml());
    }
}