<?php

namespace Stoatally\Dom\NodeTypes;

use DomDocument;
use DomNode;
use DomXPath;
use Stoatally\Dom\Nodes;

trait DocumentTrait
{
    private $xpath;

    public function createAttribute($name): Attribute
    {
        return new Nodes\Attribute($this->getLibxml()->createAttribute($name));
    }

    public function createElement($name): Element
    {
        return new Nodes\Element($this->getLibxml()->createElement($name));
    }

    public function createDocumentFragment(): Fragment
    {
        return new Nodes\Fragment($this->getLibxml()->createDocumentFragment());
    }

    public function createTextNode($text): Text
    {
        return new Nodes\Text($this->getLibxml()->createTextNode($text));
    }

    private function getDocumentElement(): Element
    {
        return $this->getLibxml()->documentElement->native;
    }

    public function getImportableNode(): Node
    {
        $fragment = $this->createDocumentFragment();

        foreach ($this->getChildren() as $node) {
            $fragment->appendChild(clone $node);
        }

        return $fragment;
    }

    public function saveHtml(): string
    {
        return $this->getLibxml()->saveHtml();
    }

    public function getXPath(): DomXPath
    {
        return $this->xpath;
    }

    public function setXPath(DomXPath $xpath)
    {
        $this->xpath = $xpath;
    }
}