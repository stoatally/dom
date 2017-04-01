<?php

namespace Stoatally\Dom;

use DomNode;
use DomText;
use PHPUnit\Framework\TestCase;

class NodeTest extends TestCase
{
    private function createDocument($html)
    {
        $documentFactory = new DocumentFactory();

        return $documentFactory->createFromString($html);
    }

    public function testSetNodeContents()
    {
        $document = $this->createDocument('<a/>');

        $this->assertEquals(null, $document->documentElement->nodeValue);

        $document->documentElement->setContents('Awesome <3');

        $this->assertEquals('Awesome <3', $document->documentElement->nodeValue);
        $this->assertEquals("<a>Awesome &lt;3</a>\n", $document->saveHtml());
    }

    public function testGetNodeContents()
    {
        $document = $this->createDocument('<a>Awesome &lt;3</a>');

        $this->assertEquals('Awesome <3', $document->documentElement->nodeValue);
        $this->assertEquals('Awesome <3', $document->documentElement->getContents());
    }

    public function testImportNode()
    {
        $documentA = $this->createDocument('<a/>');
        $documentB = $this->createDocument('<b/>');

        $result = $documentA->import($documentB->documentElement);
        $documentA->appendChild($result);

        $this->assertEquals($documentA, $result->ownerDocument);
        $this->assertEquals("<a></a><b></b>\n", $documentA->saveHtml());
    }

    public function testImportText()
    {
        $document = $this->createDocument('<a/>');

        $result = $document->import('a');
        $document->appendChild($result);

        $this->assertTrue($result instanceof DomText);
        $this->assertEquals('a', $result->nodeValue);
        $this->assertEquals("<a></a>a\n", $document->saveHtml());
    }

    public function testImportAlreadyImportedNode()
    {
        $document = $this->createDocument('<a/>');

        $result = $document->import($document->documentElement);
        $document->appendChild($result);

        $this->assertEquals($document, $result->ownerDocument);
        $this->assertEquals("<a></a>\n", $document->saveHtml());
    }

    public function testAppendChildToEmptyNode()
    {
        $document = $this->createDocument('<a/>');

        $document->documentElement->append($document->createElement('b'));

        $this->assertEquals("<a><b></b></a>\n", $document->saveHtml());
    }

    public function testPrependChildToEmptyNode()
    {
        $document = $this->createDocument('<a/>');

        $document->documentElement->prepend($document->createElement('b'));

        $this->assertEquals("<a><b></b></a>\n", $document->saveHtml());
    }

    public function testPrependChildToNodeWithContents()
    {
        $document = $this->createDocument('<a><c/></a>');

        $document->documentElement->prepend($document->createElement('b'));

        $this->assertEquals("<a><b></b><c></c></a>\n", $document->saveHtml());
    }

    public function testDuplicateSelf()
    {
        $document = $this->createDocument('<a/>');

        $results = $document->documentElement->duplicateNode(3);

        $this->assertTrue($results instanceof NodeTypes\Iterator);
        $this->assertEquals(3, count($results));
        $this->assertEquals("<a></a><a></a><a></a>\n", $document->saveHtml());
    }

    public function testDuplicateSelfOnce()
    {
        $document = $this->createDocument('<a/>');

        $results = $document->documentElement->duplicateNode(1);

        $this->assertTrue($results instanceof NodeTypes\Iterator);
        $this->assertEquals(1, count($results));
        $this->assertEquals("<a></a>\n", $document->saveHtml());
    }

    public function testExtractNode()
    {
        $document = $this->createDocument('<a><b/></a>');

        $results = $document->documentElement->extractNode();

        $this->assertTrue($results instanceof NodeTypes\Iterator);
        $this->assertEquals(1, count($results));
        $this->assertEquals("<b></b>\n", $document->saveHtml());
    }

    public function testRemoveNode()
    {
        $document = $this->createDocument('<a><b/></a>');

        $results = $document->documentElement->removeNode();

        $this->assertTrue($results instanceof NodeTypes\Node);
        $this->assertEquals("\n", $document->saveHtml());
    }

    public function testRepeatNode()
    {
        $document = $this->createDocument('<a/>');

        $results = $document->documentElement->repeatNode([1, 2, 3]);

        $this->assertTrue($results instanceof NodeTypes\Iterator);
        $this->assertEquals(3, count($results));
        $this->assertEquals("<a>1</a><a>2</a><a>3</a>\n", $document->saveHtml());
    }

    public function testRepeatNodeWithCallback()
    {
        $document = $this->createDocument('<a/>');

        $results = $document->documentElement->repeatNode([1, 2, 3], function($node, $item) {
            $node->setContents($item * 2);
        });

        $this->assertTrue($results instanceof NodeTypes\Iterator);
        $this->assertEquals(3, count($results));
        $this->assertEquals("<a>2</a><a>4</a><a>6</a>\n", $document->saveHtml());
    }
}