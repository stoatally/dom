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

        $this->assertEquals(null, $document->getDocumentElement()->getContent());

        $document->getDocumentElement()->setContent('Awesome <3');

        $this->assertEquals('Awesome <3', $document->getDocumentElement()->getContent());
        $this->assertEquals("<a>Awesome &lt;3</a>\n", $document->saveHtml());
    }

    public function testGetNodeContents()
    {
        $document = $this->createDocument('<a>Awesome &lt;3</a>');

        $this->assertEquals('Awesome <3', $document->getDocumentElement()->getContent());
        $this->assertEquals('Awesome <3', $document->getDocumentElement()->getContent());
    }

    public function testSetTextNodeContents()
    {
        $document = $this->createDocument('<a>1</a>');

        $this->assertEquals('1', $document->getDocumentElement()->getContent());

        $document->getDocumentElement()->getChildren()[0]->setContent('Awesome <3');

        $this->assertEquals('Awesome <3', $document->getDocumentElement()->getContent());
        $this->assertEquals("<a>Awesome &lt;3</a>\n", $document->saveHtml());
    }

    public function testSetRawNodeContents()
    {
        $document = $this->createDocument('<a/>');

        $this->assertEquals(null, $document->getDocumentElement()->getContent());

        $document->getDocumentElement()->setRawContent('Awesome &lt;3');

        $this->assertEquals('Awesome <3', $document->getDocumentElement()->getContent());
        $this->assertEquals("<a>Awesome &lt;3</a>\n", $document->saveHtml());
    }

    public function testGetRawNodeContents()
    {
        $document = $this->createDocument('<a>Awesome &lt;3</a>');

        $this->assertEquals('Awesome &lt;3', $document->getDocumentElement()->getRawContent());
        $this->assertEquals('Awesome <3', $document->getDocumentElement()->getContent());
        $this->assertEquals("<a>Awesome &lt;3</a>\n", $document->saveHtml());
    }

    public function testImportNode()
    {
        $documentA = $this->createDocument('<a/>');
        $documentB = $this->createDocument('<b/>');

        $result = $documentA->importNode($documentB->getDocumentElement());
        $documentA->appendChild($result);

        $this->assertEquals($documentA, $result->getDocument());
        $this->assertEquals("<a></a><b></b>\n", $documentA->saveHtml());
    }

    public function testImportText()
    {
        $document = $this->createDocument('<a/>');

        $result = $document->importNode('a');
        $document->appendChild($result);

        $this->assertTrue($result instanceof NodeTypes\Text);
        $this->assertEquals('a', $result->getContent());
        $this->assertEquals("<a></a>a\n", $document->saveHtml());
    }

    public function testImportAlreadyImportedNode()
    {
        $document = $this->createDocument('<a/>');

        $result = $document->importNode($document->getDocumentElement());
        $document->appendChild($result);

        $this->assertEquals($document, $result->getDocument());
        $this->assertEquals("<a></a>\n", $document->saveHtml());
    }

    public function testAppendChildToEmptyNode()
    {
        $document = $this->createDocument('<a/>');

        $document->getDocumentElement()->appendChild($document->createElement('b'));

        $this->assertEquals("<a><b></b></a>\n", $document->saveHtml());
    }

    public function testPrependChildToEmptyNode()
    {
        $document = $this->createDocument('<a/>');

        $document->getDocumentElement()->prependChild($document->createElement('b'));

        $this->assertEquals("<a><b></b></a>\n", $document->saveHtml());
    }

    public function testPrependChildToNodeWithContents()
    {
        $document = $this->createDocument('<a><c/></a>');

        $document->getDocumentElement()->prependChild($document->createElement('b'));

        $this->assertEquals("<a><b></b><c></c></a>\n", $document->saveHtml());
    }

    public function testDuplicateSelf()
    {
        $document = $this->createDocument('<a/>');

        $results = $document->getDocumentElement()->duplicate(3);

        $this->assertTrue($results instanceof NodeTypes\Iterator);
        $this->assertEquals(3, count($results));
        $this->assertEquals("<a></a><a></a><a></a>\n", $document->saveHtml());
    }

    public function testDuplicateSelfOnce()
    {
        $document = $this->createDocument('<a/>');

        $results = $document->getDocumentElement()->duplicate(1);

        $this->assertTrue($results instanceof NodeTypes\Iterator);
        $this->assertEquals(1, count($results));
        $this->assertEquals("<a></a>\n", $document->saveHtml());
    }

    public function testRepeat()
    {
        $document = $this->createDocument('<a/>');

        $results = $document->getDocumentElement()->repeat([1, 2, 3]);

        $this->assertTrue($results instanceof NodeTypes\Iterator);
        $this->assertEquals(3, count($results));
        $this->assertEquals("<a>1</a><a>2</a><a>3</a>\n", $document->saveHtml());
    }

    public function testRepeatWithCallback()
    {
        $document = $this->createDocument('<a/>');

        $results = $document->getDocumentElement()->repeat([1, 2, 3], function($node, $item) {
            $node->setContent($item * 2);
        });

        $this->assertTrue($results instanceof NodeTypes\Iterator);
        $this->assertEquals(3, count($results));
        $this->assertEquals("<a>2</a><a>4</a><a>6</a>\n", $document->saveHtml());
    }
}