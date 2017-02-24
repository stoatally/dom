<?php

namespace Stoatally\Dom;

use DomNode;
use PHPUnit\Framework\TestCase;

class ImportableNodeTest extends TestCase
{
    private function createDocument($html)
    {
        $documentFactory = new DocumentFactory();

        return $documentFactory->createFromString($html);
    }

    public function testSetImportableNode()
    {
        $document = $this->createDocument('<a/>');

        $document->getDocumentElement()->set(new class($document) implements NodeTypes\ImportableNode {
            use ImportableNodeTestTrait;

            public function getImportableNode(): NodeTypes\Node
            {
                $fragment = $this->document->createDocumentFragment();
                $fragment->appendXml('Awesome &hearts;');

                return $fragment;
            }
        });

        $this->assertEquals("<a>Awesome &hearts;</a>\n", $document->saveHtml());
    }

    public function testImportDocument()
    {
        $document = $this->createDocument('<a/>');

        $result = $document->import($this->createDocument('<b/>'));
        $document->append($result);

        $this->assertEquals($document, $result->getDocument());
        $this->assertEquals("<a></a><b></b>\n", $document->saveHtml());
    }
}