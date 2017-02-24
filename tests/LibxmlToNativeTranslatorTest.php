<?php

namespace Stoatally\Dom;

use DomDocument;
use DomElement;
use PHPUnit\Framework\TestCase;

class LibxmlToNativeTranslatorTest extends TestCase
{
    public function createTranslatedDocument($xml)
    {
        $translator = new LibxmlToNativeTranslator();
        $document = new DomDocument();
        $document->loadXml($xml);

        $translator($document->childNodes);

        return $document;
    }

    public function testTranslateDocumentElement()
    {
        $document = $this->createTranslatedDocument('<a/>');

        $this->assertTrue(isset($document->childNodes[0]));
        $this->assertTrue($document->childNodes[0]->native instanceof NodeTypes\Element);
    }

    public function testTranslateNestedElement()
    {
        $document = $this->createTranslatedDocument('<a><b/></a>');

        $this->assertTrue(isset($document->childNodes[0]));
        $this->assertTrue($document->childNodes[0]->native instanceof NodeTypes\Element);

        $element = $document->childNodes[0];

        $this->assertTrue(isset($element->childNodes[0]));
        $this->assertTrue($element->childNodes[0]->native instanceof NodeTypes\Element);
    }

    public function testTranslateAttribute()
    {
        $document = $this->createTranslatedDocument('<a href=""/>');

        $this->assertTrue(isset($document->childNodes[0]->attributes[0]));
        $this->assertTrue($document->childNodes[0]->attributes[0]->native instanceof NodeTypes\Attribute);
    }

    public function testTranslateText()
    {
        $document = $this->createTranslatedDocument('<a>Awesome</a>');

        $this->assertTrue(isset($document->childNodes[0]->childNodes[0]));
        $this->assertTrue($document->childNodes[0]->childNodes[0]->native instanceof NodeTypes\Text);
    }

    public function testClonedElements()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromUri(__DIR__ . '/../data/test.html');

        $check = function($children) use (&$check) {
            foreach ($children as $child) {
                if ($child instanceof DomElement) {
                    $this->assertTrue(isset($child->native));

                    $check($child->childNodes);
                }

                else {
                    $this->assertTrue(isset($child->native));
                }
            }
        };

        $check($document->getLibxml()->childNodes);

        $clone = clone $document;
        $check($clone->getLibxml()->childNodes);
    }
}