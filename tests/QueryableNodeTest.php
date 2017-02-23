<?php

namespace Stoatally\Dom;

use DomNode;
use DomText;
use LogicException;
use PHPUnit\Framework\TestCase;

class QueryableNodeTest extends TestCase
{
    private function createArrayIterator()
    {
        return function($html) {
            $documentFactory = new DocumentFactory();
            $document = $documentFactory->createFromString($html);
            $items = [];

            foreach ($document->childNodes as $child) {
                $items[] = $child;
            }

            return [$document, new ArrayIterator($items)];
        };
    }

    private function createEmptyArrayIterator()
    {
        return function() {
            $documentFactory = new DocumentFactory();
            $document = $documentFactory->createFromString('<a/>');

            return [$document, new ArrayIterator([])];
        };
    }

    private function createNodeListIterator()
    {
        return function($html) {
            $documentFactory = new DocumentFactory();
            $document = $documentFactory->createFromString($html);

            return [$document, new NodeListIterator($document->childNodes)];
        };
    }

    private function createEmptyNodeListIterator()
    {
        return function() {
            $documentFactory = new DocumentFactory();
            $document = $documentFactory->createFromString('<a/>');

            return [$document, new NodeListIterator($document->documentElement->childNodes)];
        };
    }

    private function createDocument($html)
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString($html);

        return $document;
    }

    public function testQueryNode()
    {
        $document = $this->createDocument('<ol><li/></ol>');
        $results = $document->select('ol/li');

        $this->assertTrue($results instanceof Iterator);
        $this->assertEquals(1, count($results));
        $this->assertTrue($results->current() instanceof Element);

        $results->next();

        $this->assertNull($results->current());
    }

    public function testQueryNodeWhenEmpty()
    {
        $document = $this->createDocument('<ol></ol>');
        $results = $document->select('ol/li');

        $this->assertTrue($results instanceof Iterator);
        $this->assertEquals(0, count($results));
        $this->assertNull($results->current());
    }

    public function testQueryNodeInNamespace()
    {
        $document = $this->createDocument('<ol xmlns:a="b"><a:li/></ol>');
        $results = $document->select('ol/a:li');

        $this->assertTrue($results instanceof Iterator);
        $this->assertEquals(1, count($results));
    }

    public function testQueryNodeInRootNamespace()
    {
        $documentFactory = new DocumentFactory(new XPathFactory('atom'));
        $document = $documentFactory->createFromString('<entry xmlns="http://www.w3.org/2005/Atom"><published/></entry>');
        $results = $document->select('atom:entry/atom:published');

        $this->assertTrue($results instanceof Iterator);
        $this->assertEquals(1, count($results));
    }

    public function testQueryArrayIterator()
    {
        $this->queryIterator($this->createArrayIterator());
    }

    public function testQueryNodeListIterator()
    {
        $this->queryIterator($this->createNodeListIterator());
    }

    public function testQueryArrayIteratorWhenEmpty()
    {
        $this->queryIteratorWhenEmpty($this->createEmptyArrayIterator());
    }

    public function testQueryNodeListIteratorWhenEmpty()
    {
        $this->queryIteratorWhenEmpty($this->createEmptyNodeListIterator());
    }

    private function queryIterator(callable $callback)
    {
        list($document, $iterator) = $callback('<a><b/><c/></a>');

        $results = $iterator->select('b');

        $this->assertTrue($results instanceof Iterator);
        $this->assertEquals(1, count($results));
    }

    private function queryIteratorWhenEmpty(callable $callback)
    {
        list($document, $iterator) = $callback();

        $this->expectException(LogicException::class);
        $iterator->select('a');
    }
}