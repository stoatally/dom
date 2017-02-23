<?php

namespace Stoatally\Dom;

use DomXPath;

class XPathFactory
{
    private $rootPrefix;

    public function __construct(string $rootPrefix = 'root')
    {
        $this->rootPrefix = $rootPrefix;
    }

    public function createFromDocument(Document $document)
    {
        $xpath = new DomXPath($document);

        $this->initialiseXPathNamespaces($xpath);

        return $xpath;
    }

    private function initialiseXPathNamespaces(DomXPath $xpath)
    {
        foreach ($xpath->query('namespace::*') as $namespace) {
            if ($namespace->nodeName === 'xmlns:xml') continue;

            if ($namespace->nodeName === 'xmlns') {
                $name = $this->rootPrefix;
            }

            else {
                $name = explode(':', $namespace->nodeName, 2)[1];
            }

            $xpath->registerNamespace($name, $namespace->nodeValue);
        }
    }
}