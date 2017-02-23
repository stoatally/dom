<?php

namespace Stoatally\Dom;

use DomNode;
use DomText;
use LogicException;
use PHPUnit\Framework\TestCase;

class MovableNodeTest extends TestCase
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

    public function testAppendSiblingAtTheEnd()
    {
        $document = $this->createDocument('<a/>');

        $document->documentElement->after(
            $document->createElement('b')
        );

        $this->assertEquals("<a></a><b></b>\n", $document->saveHtml());
    }

    public function testAppendSiblingInTheMiddle()
    {
        $document = $this->createDocument('<a/><b/><d/>');

        $document->childNodes[1]->after(
            $document->createElement('c')
        );

        $this->assertEquals("<a></a><b></b><c></c><d></d>\n", $document->saveHtml());
    }

    public function testAppendIteratorSibling()
    {
        $this->appendIteratorSibling($this->createArrayIterator());
        $this->appendIteratorSibling($this->createNodeListIterator());
    }

    private function appendIteratorSibling(callable $callback)
    {
        list($document, $iterator) = $callback('<a/>');

        $iterator->after(
            $document->createElement('b')
        );

        $this->assertEquals("<a></a><b></b>\n", $document->saveHtml());
    }

    public function testAppendIteratorSiblingWhenEmpty()
    {
        $this->appendIteratorSiblingWhenEmpty($this->createEmptyArrayIterator());
        $this->appendIteratorSiblingWhenEmpty($this->createEmptyNodeListIterator());
    }

    private function appendIteratorSiblingWhenEmpty(callable $callback)
    {
        list($document, $iterator) = $callback();

        $this->expectException(LogicException::class);
        $iterator->after(
            $document->createElement('a')
        );
    }




    public function testPrependSibling()
    {
        $document = $this->createDocument('<b/>');

        $document->documentElement->before(
            $document->createElement('a')
        );

        $this->assertEquals("<a></a><b></b>\n", $document->saveHtml());
    }

    public function testPrependIteratorSibling()
    {
        $this->prependIteratorSibling($this->createArrayIterator());
        $this->prependIteratorSibling($this->createNodeListIterator());
    }

    private function prependIteratorSibling(callable $callback)
    {
        list($document, $iterator) = $callback('<b/>');

        $iterator->before(
            $document->createElement('a')
        );

        $this->assertEquals("<a></a><b></b>\n", $document->saveHtml());
    }

    public function testPrependIteratorSiblingWhenEmpty()
    {
        $this->prependIteratorSiblingWhenEmpty($this->createEmptyArrayIterator());
        $this->prependIteratorSiblingWhenEmpty($this->createEmptyNodeListIterator());
    }

    private function prependIteratorSiblingWhenEmpty(callable $callback)
    {
        list($document, $iterator) = $callback();

        $this->expectException(LogicException::class);
        $iterator->before(
            $document->createElement('a')
        );
    }

    public function testReplaceNode()
    {
        $document = $this->createDocument('<a/>');

        $document->documentElement->replace(
            $document->createElement('b')
        );

        $this->assertEquals("<b></b>\n", $document->saveHtml());
    }

    public function testReplaceIterator()
    {
        $this->replaceIterator($this->createArrayIterator());
        $this->replaceIterator($this->createNodeListIterator());
    }

    private function replaceIterator(callable $callback)
    {
        list($document, $iterator) = $callback('<a/>');

        $element = $iterator->replace($document->createElement('b'));

        $this->assertEquals("<b></b>\n", $document->saveHtml());
    }

    public function testReplaceIteratorWhenEmpty()
    {
        $this->replaceIteratorWhenEmpty($this->createEmptyArrayIterator());
        $this->replaceIteratorWhenEmpty($this->createEmptyNodeListIterator());
    }

    private function replaceIteratorWhenEmpty(callable $callback)
    {
        list($document, $iterator) = $callback();

        $this->expectException(LogicException::class);
        $iterator->replace($document->createElement('b'));
    }

    public function testWrapNode()
    {
        $document = $this->createDocument('<li/>');
        $element = $document->documentElement->wrap($document->createElement('ol'));

        $this->assertEquals($document->documentElement, $element);
        $this->assertEquals("<ol><li></ol>\n", $document->saveHtml());
    }

    public function testWrapIterator()
    {
        $this->wrapIterator($this->createArrayIterator());
        $this->wrapIterator($this->createNodeListIterator());
    }

    private function wrapIterator(callable $callback)
    {
        list($document, $iterator) = $callback('<li/><li/><li/>');

        $element = $iterator->wrap($document->createElement('ol'));

        $this->assertEquals($document->documentElement, $element);
        $this->assertEquals("<ol><li><li><li></ol>\n", $document->saveHtml());
    }

    public function testWrapIteratorWhenEmpty()
    {
        $this->wrapIteratorWhenEmpty($this->createEmptyArrayIterator());
        $this->wrapIteratorWhenEmpty($this->createEmptyNodeListIterator());
    }

    private function wrapIteratorWhenEmpty(callable $callback)
    {
        list($document, $iterator) = $callback();

        $this->expectException(LogicException::class);
        $iterator->wrap($document->createElement('ol'));
    }
}