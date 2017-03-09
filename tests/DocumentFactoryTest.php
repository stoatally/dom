<?php

namespace Stoatally\Dom;

use PHPUnit\Framework\TestCase;

class DocumentFactoryTest extends TestCase
{
    public function testCreateDocument()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->create();

        $this->assertTrue($document instanceof NodeTypes\Document);
    }

    public function testDocumentHasCorrectNodeClasses()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->create();
        $element = $document->createElement('test');
        $attribute = $document->createAttribute('test');

        $this->assertTrue($attribute instanceof NodeTypes\Attribute);
        $this->assertTrue($element instanceof NodeTypes\Element);
    }

    public function testCreateDocumentFromUri()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromUri(__DIR__ . '/../data/test.xml');

        $this->assertTrue($document instanceof NodeTypes\Document);
        $this->assertTrue($document->documentElement instanceof NodeTypes\Element);
        $this->assertEquals($document->documentElement->getName(), 'xyz');
    }

    public function testCreateDocumentFromIncorrectUri()
    {
        $documentFactory = new DocumentFactory();

        $this->expectException(Exceptions\FileNotFoundException::class);
        $document = $documentFactory->createFromUri(__DIR__ . '/../data/test.x');
    }

    public function testCreateDocumentFromString()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<xyz/>');

        $this->assertTrue($document instanceof NodeTypes\Document);
        $this->assertTrue($document->documentElement instanceof NodeTypes\Element);
        $this->assertEquals($document->documentElement->getName(), 'xyz');
    }
}