<?php

namespace Stoatally\Dom;

use LogicException;
use PHPUnit\Framework\TestCase;

class QueryableNodeTest extends TestCase
{
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

        $this->assertTrue($results instanceof NodeTypes\Iterator);
        $this->assertEquals(1, count($results));
        $this->assertTrue($results->current() instanceof NodeTypes\Element);

        $results->next();

        $this->assertNull($results->current());
    }

    public function testQueryNodeWhenEmpty()
    {
        $document = $this->createDocument('<ol></ol>');
        $results = $document->select('ol/li');

        $this->assertTrue($results instanceof NodeTypes\Iterator);
        $this->assertEquals(0, count($results));
        $this->assertNull($results->current());
    }

    public function testQueryNodeInNamespace()
    {
        $document = $this->createDocument('<ol xmlns:a="b"><a:li/></ol>');
        $results = $document->select('ol/a:li');

        $this->assertTrue($results instanceof NodeTypes\Iterator);
        $this->assertEquals(1, count($results));
    }

    public function testQueryNodeInRootNamespace()
    {
        $documentFactory = new DocumentFactory(new XPathFactory('atom'));
        $document = $documentFactory->createFromString('<entry xmlns="http://www.w3.org/2005/Atom"><published/></entry>');
        $results = $document->select('atom:entry/atom:published');

        $this->assertTrue($results instanceof NodeTypes\Iterator);
        $this->assertEquals(1, count($results));
    }

    public function testQueryIterator()
    {
        list($document, $iterator) = $this->createIterator('<a><b/><c/></a>');

        $results = $iterator->select('b');

        $this->assertTrue($results instanceof NodeTypes\Iterator);
        $this->assertEquals(1, count($results));
    }

    public function testQueryIteratorWhenEmpty()
    {
        list($document, $iterator) = $this->createEmptyIterator();

        $this->expectException(LogicException::class);
        $iterator->select('a');
    }
}