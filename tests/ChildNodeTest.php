<?php

namespace Stoatally\Dom;

use LogicException;
use PHPUnit\Framework\TestCase;

class ChildNodeTest extends TestCase
{
    private function createDocument($html)
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString($html);

        return $document;
    }

    private function createIterator($html)
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString($html);
        $items = [];

        foreach ($document->getChildren() as $child) {
            $items[] = $child;
        }

        return [$document, new Nodes\Iterator($document, $items)];
    }

    private function createEmptyIterator()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/>');

        return [$document, new Nodes\Iterator($document, [])];
    }

    public function testGetParent()
    {
        $document = $this->createDocument('<a><b/></a>');

        $element = $document->documentElement->getChildren()[0];

        $this->assertTrue($element instanceof NodeTypes\Element);
        $this->assertTrue($element->hasParent());
        $this->assertEquals($document->documentElement, $element->parentNode);
    }

    public function testGetIteratorParent()
    {
        $document = $this->createDocument('<a><b/></a>');

        $results = $document->documentElement->getChildren();

        $this->assertTrue($results instanceof NodeTypes\ChildIterator);
        $this->assertTrue($results->hasParent());
        $this->assertEquals($document->documentElement, $results->getParent());
    }

    public function testGetIteratorParentWhenEmpty()
    {
        $document = $this->createDocument('<a></a>');

        $results = $document->documentElement->getChildren();

        $this->assertTrue($results instanceof NodeTypes\ChildIterator);
        $this->assertFalse($results->hasParent());

        $this->expectException(LogicException::class);
        $results->getParent();
    }

    public function testGetIteratorChildren()
    {
        $document = $this->createDocument('<a><b/></a>');

        $results = $document->getChildren();

        $this->assertTrue($results instanceof NodeTypes\ChildIterator);
        $this->assertTrue($results->hasParent());

        $this->assertEquals($document, $results->getParent());
    }

    public function testAppendSiblingAtTheEnd()
    {
        $document = $this->createDocument('<a/>');

        $results = $document->documentElement->appendSibling($document->createElement('b'));

        $this->assertTrue($results instanceof NodeTypes\Element);
        $this->assertEquals("<a></a><b></b>\n", $document->saveHtml());
    }

    public function testAppendSiblingInTheMiddle()
    {
        $document = $this->createDocument('<a/><b/><d/>');

        $results = $document->getChildren()[1]->appendSibling($document->createElement('c'));

        $this->assertTrue($results instanceof NodeTypes\Element);
        $this->assertEquals("<a></a><b></b><c></c><d></d>\n", $document->saveHtml());
    }

    public function testAppendIteratorSibling()
    {
        list($document, $iterator) = $this->createIterator('<a/>');

        $results = $iterator->appendSibling($document->createElement('b'));

        $this->assertTrue($results instanceof NodeTypes\ChildIterator);
        $this->assertEquals(2, count($results));
        $this->assertEquals("<a></a><b></b>\n", $document->saveHtml());
    }

    public function testAppendIteratorSiblingInTheMiddle()
    {
        list($document, $iterator) = $this->createIterator('<a/><b/><d/>');

        $results = $iterator[1]->appendSibling($document->createElement('c'));

        $this->assertTrue($results instanceof NodeTypes\Element);
        $this->assertEquals("<a></a><b></b><c></c><d></d>\n", $document->saveHtml());
    }

    public function testAppendIteratorSiblingWhenEmpty()
    {
        list($document, $iterator) = $this->createEmptyIterator();

        $results = $iterator->appendSibling($document->createElement('a'));

        $this->assertTrue($results instanceof NodeTypes\ChildIterator);
        $this->assertEquals(1, count($results));
        $this->assertEquals("<a></a>\n", $document->saveHtml());
    }

    public function testPrependSibling()
    {
        $document = $this->createDocument('<b/>');

        $results = $document->documentElement->prependSibling($document->createElement('a'));

        $this->assertTrue($results instanceof NodeTypes\Element);
        $this->assertEquals("<a></a><b></b>\n", $document->saveHtml());
    }

    public function testPrependIteratorSibling()
    {
        list($document, $iterator) = $this->createIterator('<b/>');

        $results = $iterator->prependSibling($document->createElement('a'));

        $this->assertTrue($results instanceof NodeTypes\ChildIterator);
        $this->assertEquals(2, count($results));
        $this->assertEquals("<a></a><b></b>\n", $document->saveHtml());
    }

    public function testPrependIteratorSiblingWhenEmpty()
    {
        list($document, $iterator) = $this->createEmptyIterator();

        $results = $iterator->prependSibling($document->createElement('a'));

        $this->assertTrue($results instanceof NodeTypes\ChildIterator);
        $this->assertEquals(1, count($results));
        $this->assertEquals("<a></a>\n", $document->saveHtml());
    }

    public function testInsertSiblingsBetween()
    {
        list($document, $iterator) = $this->createIterator('<a/><a/><a/>');

        $results = $iterator->between('|');

        $this->assertTrue($results instanceof NodeTypes\ChildIterator);
        $this->assertEquals(5, count($results));
        $this->assertEquals("<a></a>|<a></a>|<a></a>\n", $document->saveHtml());
    }

    public function testInsertSiblingsBetweenWhenEmpty()
    {
        list($document, $iterator) = $this->createEmptyIterator();

        $results = $iterator->between($document->createElement('b'));

        $this->assertTrue($results instanceof NodeTypes\ChildIterator);
        $this->assertEquals(0, count($results));
        $this->assertEquals("<a></a>\n", $document->saveHtml());
    }

    public function testRemoveNode()
    {
        $document = $this->createDocument('<a/>');

        $results = $document->documentElement->remove();

        $this->assertTrue($results instanceof NodeTypes\Element);
        $this->assertEquals("\n", $document->saveHtml());
    }

    public function testRemoveIterator()
    {
        list($document, $iterator) = $this->createIterator('<a/><b/><c/>');

        $results = $iterator->remove();

        $this->assertTrue($results instanceof NodeTypes\ChildIterator);
        $this->assertEquals(3, count($results));
        $this->assertEquals("\n", $document->saveHtml());
    }

    public function testRemoveIteratorWhenEmpty()
    {
        list($document, $iterator) = $this->createEmptyIterator();

        $results = $iterator->remove();

        $this->assertTrue($results instanceof NodeTypes\ChildIterator);
        $this->assertEquals(0, count($results));
        $this->assertEquals("<a></a>\n", $document->saveHtml());
    }

    public function testReplaceNode()
    {
        $document = $this->createDocument('<a/>');

        $results = $document->documentElement->replaceWith($document->createElement('b'));

        $this->assertTrue($results instanceof NodeTypes\Element);
        $this->assertEquals("<b></b>\n", $document->saveHtml());
    }

    public function testReplaceIterator()
    {
        list($document, $iterator) = $this->createIterator('<a/><b/><c/>');

        $results = $iterator->replaceWith($document->createElement('d'));

        $this->assertTrue($results instanceof NodeTypes\ChildIterator);
        $this->assertEquals(1, count($results));
        $this->assertEquals("<d></d>\n", $document->saveHtml());
    }

    public function testReplaceIteratorWhenEmpty()
    {
        list($document, $iterator) = $this->createEmptyIterator();

        $results = $iterator->replaceWith($document->createElement('b'));

        $this->assertTrue($results instanceof NodeTypes\ChildIterator);
        $this->assertEquals(1, count($results));
        $this->assertEquals("<a></a>\n", $document->saveHtml());
    }

    public function testWrapNode()
    {
        $document = $this->createDocument('<li/>');

        $results = $document->documentElement->wrapWith($document->createElement('ol'));

        $this->assertTrue($results instanceof NodeTypes\Element);
        $this->assertEquals($document->documentElement, $results);
        $this->assertEquals("<ol><li></ol>\n", $document->saveHtml());
    }

    public function testWrapIterator()
    {
        list($document, $iterator) = $this->createIterator('<li/><li/><li/>');

        $results = $iterator->wrapWith($document->createElement('ol'));

        $this->assertTrue($results instanceof NodeTypes\ChildIterator);
        $this->assertEquals(1, count($results));
        $this->assertEquals($document->documentElement, $results[0]);
        $this->assertEquals("<ol><li><li><li></ol>\n", $document->saveHtml());
    }

    public function testWrapIteratorWhenEmpty()
    {
        list($document, $iterator) = $this->createEmptyIterator();

        $results = $iterator->wrapWith($document->createElement('ol'));

        $this->assertTrue($results instanceof NodeTypes\ChildIterator);
        $this->assertEquals(1, count($results));
        $this->assertEquals("<a></a>\n", $document->saveHtml());
    }
}