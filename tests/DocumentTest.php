<?php

namespace Stoatally\Dom;

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

        $this->assertEquals(null, $document->documentElement->nodeValue);

        $document->documentElement->set('Awesome <3');

        $this->assertEquals('Awesome <3', $document->documentElement->nodeValue);
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

    public function testDuplicateSelf()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/>');

        $results = $document->documentElement->duplicate(3);

        $this->assertTrue($results instanceof Iterator);
        $this->assertEquals(3, count($results));
        $this->assertEquals("<a></a><a></a><a></a>\n", $document->saveHtml());
    }

    public function testDuplicateSelfOnce()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/>');

        $results = $document->documentElement->duplicate(1);

        $this->assertTrue($results instanceof Iterator);
        $this->assertEquals(1, count($results));
        $this->assertEquals("<a></a>\n", $document->saveHtml());
    }

    public function testQueryForElements()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<ol><li/></ol>');
        $results = $document->select('ol/li');

        $this->assertTrue($results instanceof Iterator);
        $this->assertEquals(1, count($results));
        $this->assertTrue($results->current() instanceof Element);

        $results->next();

        $this->assertNull($results->current());
    }

    public function testQueryForNoElements()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<ol></ol>');
        $results = $document->select('ol/li');

        $this->assertTrue($results instanceof Iterator);
        $this->assertEquals(0, count($results));
        $this->assertNull($results->current());
    }

    public function testQueryForElementsInNamespace()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<ol xmlns:a="b"><a:li/></ol>');
        $results = $document->select('ol/a:li');

        $this->assertTrue($results instanceof Iterator);
        $this->assertEquals(1, count($results));
    }

    public function testQueryForElementsInRootNamespace()
    {
        $documentFactory = new DocumentFactory(new XPathFactory('atom'));
        $document = $documentFactory->createFromString('<entry xmlns="http://www.w3.org/2005/Atom"><published/></entry>');
        $results = $document->select('atom:entry/atom:published');

        $this->assertTrue($results instanceof Iterator);
        $this->assertEquals(1, count($results));
    }

    public function testRepeat()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/>');

        $results = $document->documentElement->repeat([1, 2, 3]);

        $this->assertTrue($results instanceof Iterator);
        $this->assertEquals(3, count($results));
        $this->assertEquals("<a>1</a><a>2</a><a>3</a>\n", $document->saveHtml());
    }

    public function testRepeatWithCallback()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/>');

        $results = $document->documentElement->repeat([1, 2, 3], function($node, $item) {
            $node->set($item * 2);
        });

        $this->assertTrue($results instanceof Iterator);
        $this->assertEquals(3, count($results));
        $this->assertEquals("<a>2</a><a>4</a><a>6</a>\n", $document->saveHtml());
    }
}