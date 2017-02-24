<?php

namespace Stoatally\Dom;

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
}