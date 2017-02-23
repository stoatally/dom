<?php

namespace Stoatally\Dom;

use DomEntityReference;
use PHPUnit\Framework\TestCase;

class DocumentFactoryTest extends TestCase
{
    public function testCreateDocument()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->create();

        $this->assertTrue($document instanceof Document);
        $this->assertEquals($document->encoding, 'UTF-8');
        $this->assertEquals($document->xmlVersion, '1.0');
    }

    public function testDocumentHasCorrectNodeClasses()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->create();
        $element = $document->createElement('test');
        $attribute = $document->createAttribute('test');

        $this->assertTrue($attribute instanceof Attribute);
        $this->assertTrue($element instanceof Element);
    }

    public function testCreateDocumentFromUri()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromUri(__DIR__ . '/../data/test.xml');

        $this->assertTrue($document instanceof Document);
        $this->assertTrue($document->documentElement instanceof Element);
        $this->assertEquals($document->documentElement->tagName, 'xyz');
    }

    public function testCreateDocumentFromIncorrectUri()
    {
        $documentFactory = new DocumentFactory();

        $this->expectException(FileNotFoundException::class);
        $document = $documentFactory->createFromUri(__DIR__ . '/../data/test.x');
    }

    public function testCreateDocumentFromString()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<xyz/>');

        $this->assertTrue($document instanceof Document);
        $this->assertTrue($document->documentElement instanceof Element);
        $this->assertEquals($document->documentElement->tagName, 'xyz');
    }
}