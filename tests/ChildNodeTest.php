<?php

namespace Stoatally\Dom;

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

        foreach ($document->childNodes as $child) {
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

    public function testAppendSiblingAtTheEnd()
    {
        $document = $this->createDocument('<a/>');

        $results = $document->documentElement->after($document->createElement('b'));

        $this->assertTrue($results instanceof NodeTypes\Element);
        $this->assertEquals("<a></a><b></b>\n", $document->saveHtml());
    }

    public function testAppendSiblingInTheMiddle()
    {
        $document = $this->createDocument('<a/><b/><d/>');

        $results = $document->childNodes[1]->after($document->createElement('c'));

        $this->assertTrue($results instanceof NodeTypes\Element);
        $this->assertEquals("<a></a><b></b><c></c><d></d>\n", $document->saveHtml());
    }

    public function testAppendIteratorSibling()
    {
        list($document, $iterator) = $this->createIterator('<a/>');

        $results = $iterator->after($document->createElement('b'));

        $this->assertTrue($results instanceof NodeTypes\Iterator);
        $this->assertEquals(2, count($results));
        $this->assertEquals("<a></a><b></b>\n", $document->saveHtml());
    }

    public function testAppendIteratorSiblingInTheMiddle()
    {
        list($document, $iterator) = $this->createIterator('<a/><b/><d/>');

        $results = $iterator[1]->after($document->createElement('c'));

        $this->assertTrue($results instanceof NodeTypes\Element);
        $this->assertEquals("<a></a><b></b><c></c><d></d>\n", $document->saveHtml());
    }

    public function testAppendIteratorSiblingWhenEmpty()
    {
        list($document, $iterator) = $this->createEmptyIterator();

        $results = $iterator->after($document->createElement('a'));

        $this->assertTrue($results instanceof NodeTypes\Iterator);
        $this->assertEquals(1, count($results));
        $this->assertEquals("<a></a>\n", $document->saveHtml());
    }

    public function testPrependSibling()
    {
        $document = $this->createDocument('<b/>');

        $results = $document->documentElement->before($document->createElement('a'));

        $this->assertTrue($results instanceof NodeTypes\Element);
        $this->assertEquals("<a></a><b></b>\n", $document->saveHtml());
    }

    public function testPrependIteratorSibling()
    {
        list($document, $iterator) = $this->createIterator('<b/>');

        $results = $iterator->before($document->createElement('a'));

        $this->assertTrue($results instanceof NodeTypes\Iterator);
        $this->assertEquals(2, count($results));
        $this->assertEquals("<a></a><b></b>\n", $document->saveHtml());
    }

    public function testPrependIteratorSiblingWhenEmpty()
    {
        list($document, $iterator) = $this->createEmptyIterator();

        $results = $iterator->before($document->createElement('a'));

        $this->assertTrue($results instanceof NodeTypes\Iterator);
        $this->assertEquals(1, count($results));
        $this->assertEquals("<a></a>\n", $document->saveHtml());
    }

    public function testInsertSiblingsBetween()
    {
        list($document, $iterator) = $this->createIterator('<a/><a/><a/>');

        $results = $iterator->between('|');

        $this->assertTrue($results instanceof NodeTypes\Iterator);
        $this->assertEquals(5, count($results));
        $this->assertEquals("<a></a>|<a></a>|<a></a>\n", $document->saveHtml());
    }

    public function testInsertSiblingsBetweenWhenEmpty()
    {
        list($document, $iterator) = $this->createEmptyIterator();

        $results = $iterator->between($document->createElement('b'));

        $this->assertTrue($results instanceof NodeTypes\Iterator);
        $this->assertEquals(0, count($results));
        $this->assertEquals("<a></a>\n", $document->saveHtml());
    }

    public function testReplaceNode()
    {
        $document = $this->createDocument('<a/>');

        $results = $document->documentElement->replace($document->createElement('b'));

        $this->assertTrue($results instanceof NodeTypes\Element);
        $this->assertEquals("<b></b>\n", $document->saveHtml());
    }

    public function testReplaceIterator()
    {
        list($document, $iterator) = $this->createIterator('<a/><b/><c/>');

        $results = $iterator->replace($document->createElement('d'));

        $this->assertTrue($results instanceof NodeTypes\Iterator);
        $this->assertEquals(1, count($results));
        $this->assertEquals($document->documentElement, $results[0]);
        $this->assertEquals("<d></d>\n", $document->saveHtml());
    }

    public function testReplaceIteratorWhenEmpty()
    {
        list($document, $iterator) = $this->createEmptyIterator();

        $results = $iterator->replace($document->createElement('b'));

        $this->assertTrue($results instanceof NodeTypes\Iterator);
        $this->assertEquals(1, count($results));
        $this->assertEquals("<a></a>\n", $document->saveHtml());
    }

    public function testWrapNode()
    {
        $document = $this->createDocument('<li/>');

        $results = $document->documentElement->wrap($document->createElement('ol'));

        $this->assertTrue($results instanceof NodeTypes\Element);
        $this->assertEquals($document->documentElement, $results);
        $this->assertEquals("<ol><li></ol>\n", $document->saveHtml());
    }

    public function testWrapIterator()
    {
        list($document, $iterator) = $this->createIterator('<li/><li/><li/>');

        $results = $iterator->wrap($document->createElement('ol'));

        $this->assertTrue($results instanceof NodeTypes\Iterator);
        $this->assertEquals(1, count($results));
        $this->assertEquals($document->documentElement, $results[0]);
        $this->assertEquals("<ol><li><li><li></ol>\n", $document->saveHtml());
    }

    public function testWrapIteratorWhenEmpty()
    {
        list($document, $iterator) = $this->createEmptyIterator();

        $results = $iterator->wrap($document->createElement('ol'));

        $this->assertTrue($results instanceof NodeTypes\Iterator);
        $this->assertEquals(1, count($results));
        $this->assertEquals("<a></a>\n", $document->saveHtml());
    }
}