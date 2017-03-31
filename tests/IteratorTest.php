<?php

namespace Stoatally\Dom;

use LogicException;
use OutOfBoundsException;
use PHPUnit\Framework\TestCase;

class IteratorTest extends TestCase
{
    protected function create($html)
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString($html);
        $items = [];

        foreach ($document->childNodes as $child) {
            $items[] = $child;
        }

        return [$document, new Nodes\Iterator($document, $items)];
    }

    protected function createEmpty()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/>');

        return [$document, new Nodes\Iterator($document, [])];
    }

    public function testCreateIteratorFromArray()
    {
        list($document, $iterator) = $this->create('<a/><b/><c/>');

        $this->assertTrue($iterator instanceof NodeTypes\Iterator);
        $this->assertEquals(3, count($iterator));
    }

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

        $iterator->setContents('1');

        $this->assertEquals('1', $iterator[0]->nodeValue);
    }

    public function testSetContentsWhenEmpty()
    {
        list($document, $iterator) = $this->createEmpty();

        $this->expectException(LogicException::class);
        $iterator->setContents('1');
    }

    public function testGetContents()
    {
        list($document, $iterator) = $this->create('<a>1</a>');

        $this->assertEquals('1', $iterator->getContents());
    }

    public function testGetContentsWhenEmpty()
    {
        list($document, $iterator) = $this->createEmpty();

        $this->expectException(LogicException::class);
        $iterator->getContents();
    }

    public function testImportNode()
    {
        list($documentA, $iteratorA) = $this->create('<a/>');
        list($documentB, $iteratorB) = $this->create('<b/>');

        $result = $iteratorA->import($iteratorB[0]);
        $documentA->append($result);

        $this->assertEquals($documentA, $result->getDocument());
        $this->assertEquals("<a></a><b></b>\n", $documentA->saveHtml());
    }

    public function testImportNodeWhenEmpty()
    {
        list($documentA, $iteratorA) = $this->createEmpty();
        list($documentB, $iteratorB) = $this->create('<b/>');

        $results = $iteratorA->import($iteratorB[0]);

        $this->assertTrue($results instanceof NodeTypes\Element);
    }

    public function testImportIterator()
    {
        list($documentA, $iteratorA) = $this->create('<a/>');
        list($documentB, $iteratorB) = $this->create('<b/>');

        $result = $iteratorA->import($iteratorB);
        $documentA->append($result);

        $this->assertEquals($documentA, $result->getDocument());
        $this->assertEquals("<a></a><b></b>\n", $documentA->saveHtml());
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
        $iterator->append($document->createElement('b'));
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

    public function testDuplicateSelf()
    {
        list($document, $iterator) = $this->create('<a/>');

        $iterator->duplicateNode(3);

        $this->assertEquals("<a></a><a></a><a></a>\n", $document->saveHtml());
    }

    public function testDuplicateSelfWhenEmpty()
    {
        list($document, $iterator) = $this->createEmpty();

        $this->expectException(LogicException::class);
        $iterator->duplicateNode(3);
    }

    public function testRepeatSelf()
    {
        list($document, $iterator) = $this->create('<a/>');

        $results = $iterator->repeatNode([1, 2, 3]);

        $this->assertTrue($results instanceof NodeTypes\Iterator);
        $this->assertEquals(3, count($results));
        $this->assertEquals("<a>1</a><a>2</a><a>3</a>\n", $document->saveHtml());
    }

    public function testRepeatSelfWhenEmpty()
    {
        list($document, $iterator) = $this->createEmpty();

        $this->expectException(LogicException::class);
        $iterator->repeatNode([1, 2, 3]);
    }

    public function testFillNodes()
    {
        list($document, $iterator) = $this->create('<a/><b/><c/>');

        $results = $iterator->fillNodes([1, 2, 3]);

        $this->assertTrue($results instanceof NodeTypes\Iterator);
        $this->assertEquals(3, count($results));
        $this->assertEquals("<a>1</a><b>2</b><c>3</c>\n", $document->saveHtml());
    }

    public function testUnderFillNodes()
    {
        list($document, $iterator) = $this->create('<a/><b/><c/>');

        $results = $iterator->fillNodes([1]);

        $this->assertTrue($results instanceof NodeTypes\Iterator);
        $this->assertEquals(3, count($results));
        $this->assertEquals("<a>1</a><b></b><c></c>\n", $document->saveHtml());
    }
}