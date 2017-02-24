<?php

namespace Stoatally\Dom\NodeTypes;

use DomXPath;

interface Document extends LibxmlNode, ImportableNode, Node, ParentNode, QueryableNode
{
    public function createAttribute($name): Attribute;

    public function createElement($name): Element;

    public function createDocumentFragment(): Fragment;

    public function createTextNode($text): Text;

    public function getDocumentElement(): Element;

    public function saveHtml(): string;

    public function getXPath(): DomXPath;

    public function setXPath(DomXPath $xpath);
}