<?php

namespace Stoatally\Dom;

use PHPUnit\Framework\TestCase;

class XPathFactoryTest extends TestCase
{
    public function testCreateXPath()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a/>');

        $xpathFactory = new XPathFactory();
        $xpath = $xpathFactory->createFromDocument($document);

        $this->assertTrue($xpath->evaluate('boolean(/a)'));
    }

    public function testCreateXPathWithNamespace()
    {
        $documentFactory = new DocumentFactory();
        $document = $documentFactory->createFromString('<a xmlns="http://a"/>');

        $xpathFactory = new XPathFactory('z');
        $xpath = $xpathFactory->createFromDocument($document);

        $this->assertTrue($xpath->evaluate('boolean(/z:a)'));
        $this->assertFalse($xpath->evaluate('boolean(/a)'));
    }
}