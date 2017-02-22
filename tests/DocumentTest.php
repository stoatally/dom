<?php

namespace Stoatally\DocumentObjectModel;

use DomNode;
use DomText;
use PHPUnit\Framework\TestCase;

class DocumentTest extends TestCase
{
    public function testDocumentHasHtmlEntities()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->create();
        $fragment = $document->createDocumentFragment();

        $this->assertTrue($fragment->appendXml('&copy;'));
    }

    public function testSetNodeTextContents()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/>');

        $document->documentElement->set('Awesome <3');

        $this->assertEquals("<a>Awesome &lt;3</a>\n", $document->saveHtml());
    }

    public function testSetNodeHtmlContents()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/>');

        $document->documentElement->set(new class($document) implements ImportableNode {
            use ImportableNodeTestTrait;

            public function getImportableNode(): DomNode
            {
                $fragment = $this->document->createDocumentFragment();
                $fragment->appendXml('Awesome &hearts;');

                return $fragment;
            }
        });

        $this->assertEquals("<a>Awesome &hearts;</a>\n", $document->saveHtml());
    }

    public function testGetNodeTextContents()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a>Awesome &lt;3</a>');

        $this->assertEquals('Awesome <3', $document->documentElement->nodeValue);
        $this->assertEquals('Awesome <3', $document->documentElement->get());
    }

    public function testImportNode()
    {
        $documentFactory = new DocumentFactory();
        $documentA = $documentFactory->createFromString('<a/>');
        $documentB = $documentFactory->createFromString('<b/>');

        $result = $documentA->import($documentB->documentElement);
        $documentA->appendChild($result);

        $this->assertEquals($documentA, $result->ownerDocument);
        $this->assertEquals("<a></a><b></b>\n", $documentA->saveHtml());
    }

    public function testImportDocument()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/>');

        $result = $document->import($documentFactory->createFromString('<b/>'));
        $document->appendChild($result);

        $this->assertEquals($document, $result->ownerDocument);
        $this->assertEquals("<a></a><b></b>\n", $document->saveHtml());
    }

    public function testImportText()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/>');

        $result = $document->import('a');
        $document->appendChild($result);

        $this->assertTrue($result instanceof DomText);
        $this->assertEquals('a', $result->nodeValue);
        $this->assertEquals("<a></a>a\n", $document->saveHtml());
    }

    public function testImportAlreadyImportedNode()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/>');

        $result = $document->import($document->documentElement);
        $document->appendChild($result);

        $this->assertEquals($document, $result->ownerDocument);
        $this->assertEquals("<a></a>\n", $document->saveHtml());
    }

    public function testAppendSiblingAtTheEnd()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/>');

        $document->documentElement->after(
            $document->createElement('b')
        );

        $this->assertEquals("<a></a><b></b>\n", $document->saveHtml());
    }

    public function testAppendSiblingInTheMiddle()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/><b/><d/>');

        $document->childNodes[1]->after(
            $document->createElement('c')
        );

        $this->assertEquals("<a></a><b></b><c></c><d></d>\n", $document->saveHtml());
    }

    public function testPrependSibling()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<b/>');

        $document->documentElement->before(
            $document->createElement('a')
        );

        $this->assertEquals("<a></a><b></b>\n", $document->saveHtml());
    }

    public function testAppendChildToEmptyNode()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/>');

        $document->documentElement->append(
            $document->createElement('b')
        );

        $this->assertEquals("<a><b></b></a>\n", $document->saveHtml());
    }

    public function testPrependChildToEmptyNode()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/>');

        $document->documentElement->prepend(
            $document->createElement('b')
        );

        $this->assertEquals("<a><b></b></a>\n", $document->saveHtml());
    }

    public function testPrependChildToNodeWithContents()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a><c/></a>');

        $document->documentElement->prepend(
            $document->createElement('b')
        );

        $this->assertEquals("<a><b></b><c></c></a>\n", $document->saveHtml());
    }

    public function testReplaceSelf()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/>');

        $document->documentElement->replace(
            $document->createElement('b')
        );

        $this->assertEquals("<b></b>\n", $document->saveHtml());
    }
}