<?php

namespace Stoatally\DocumentObjectModel;

class DocumentFactory
{
    public function create()
    {
        $document = new Document();

        $this->importHtmlEntities($document);
        $this->setDocumentDefaults($document);
        $this->setCustomNodeClasses($document);

        return $document;
    }

    public function createFromString(string $html)
    {
        $document = $this->create();
        $fragment = $document->createDocumentFragment();
        $fragment->appendXml($html);
        $document->appendChild($fragment);

        return $document;
    }

    private function importHtmlEntities(Document $document)
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

    private function setDocumentDefaults(Document $document)
    {
        // Sane error handling:
        $document->recover = true;
        $document->strictErrorChecking = false;
        $document->substituteEntities = true;

        // Set encoding and XML version:
        $document->encoding = 'UTF-8';
        $document->xmlVersion = '1.0';
    }

    private function setCustomNodeClasses(Document $document)
    {
        $document->registerNodeClass('DOMAttr', __NAMESPACE__ . '\Attribute');
        $document->registerNodeClass('DOMElement', __NAMESPACE__ . '\Element');
    }
}