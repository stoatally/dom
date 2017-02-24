<?php

namespace Stoatally\Dom;

use DomDocument;
use DomAttr;
use DomElement;
use DomText;

class DocumentFactory
{
    private $xpathFactory;

    public function __construct(XPathFactory $xpathFactory = null)
    {
        $this->xpathFactory = (
            isset($xpathFactory)
                ? $xpathFactory
                : new XPathFactory()
        );
    }

    public function create(): NodeTypes\Document
    {
        $libxml = new DomDocument();
        $document = new Nodes\Document($libxml);

        $this->importDefaultEntities($libxml);
        $this->setDocumentDefaults($libxml);

        return $document;
    }

    public function createFromUri($file): NodeTypes\Document
    {
        if (is_file($file) === false) {
            throw new Exceptions\FileNotFoundException(sprintf(
                'File "%s" does not exist.',
                $file
            ));
        }

        return $this->createFromString(file_get_contents($file));
    }

    public function createFromString(string $xmlOrHtml): NodeTypes\Document
    {
        $document = $this->create();
        $fragment = $document->createDocumentFragment();
        $fragment->appendXml($xmlOrHtml);
        $document->appendChild($fragment);

        $this->setXPathInstance($document);
        $this->translateLibxmlToNative($document);

        return $document;
    }

    private function importDefaultEntities(DomDocument $document)
    {
        // Specify the path to the entities.dtd:
        $document->loadXML('<!DOCTYPE html SYSTEM "' . __DIR__ . '/../data/entities.dtd' . '"><html />');

        // Force entities to be loaded:
        $document->resolveExternals = true;
        $document->validate();

        // Remove the old DTD (as it has references to files on disk):
        $document->removeChild($document->firstChild);
        $document->removeChild($document->firstChild);
    }

    private function setDocumentDefaults(DomDocument $document)
    {
        // Sane error handling:
        $document->recover = true;
        $document->strictErrorChecking = false;
        $document->substituteEntities = true;

        // Set encoding and XML version:
        $document->encoding = 'UTF-8';
        $document->xmlVersion = '1.0';
    }

    private function setXPathInstance(NodeTypes\Document $document)
    {
        $xpath = $this->xpathFactory->createFromDocument($document);
        $document->setXPath($xpath);
    }

    private function translateLibxmlToNative(NodeTypes\Document $document)
    {
        $translator = new LibxmlToNativeTranslator();
        $translator($document->getLibxml()->childNodes);
    }
}