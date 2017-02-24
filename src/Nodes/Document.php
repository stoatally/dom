<?php

namespace Stoatally\Dom\Nodes;

use DomDocument;
use DomNode;
use DomXPath;
use Stoatally\Dom\NodeTypes;

class Document implements NodeTypes\Document
{
    use NodeTypes\LibxmlNodeTrait;
    use NodeTypes\NodeTrait;
    use NodeTypes\QueryableNodeTrait;

    private $xpath;

    public function __construct(DomDocument $libxml)
    {
        $this->setLibxml($libxml);
    }

    public function createAttribute($name): NodeTypes\Attribute
    {
        return new Attribute($this->getLibxml()->createAttribute($name));
    }

    public function createElement($name): NodeTypes\Element
    {
        return new Element($this->getLibxml()->createElement($name));
    }

    public function createDocumentFragment(): NodeTypes\Fragment
    {
        return new Fragment($this->getLibxml()->createDocumentFragment());
    }

    public function createTextNode($text): NodeTypes\Text
    {
        return new Text($this->getLibxml()->createTextNode($text));
    }

    public function getDocument(): NodeTypes\Document
    {
        return $this;
    }

    public function getDocumentElement(): NodeTypes\Element
    {
        return $this->getLibxml()->documentElement->native;
    }

    public function getImportableNode(): NodeTypes\Node
    {
        $fragment = $this->createDocumentFragment();

        foreach ($this->getChildren() as $node) {
            $fragment->append(clone $node);
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