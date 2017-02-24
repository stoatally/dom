<?php

namespace Stoatally\Dom;

use DomDocument;
use DomAttr;
use DomElement;
use DomText;

class LibxmlToNativeTranslator
{
    public function __invoke(DomDocument $document)
    {
        $this->walk($document);
    }

    private function walk($parent)
    {
        if (isset($parent->attributes)) {
            foreach ($parent->attributes as $attr) {
                new Nodes\Attribute($attr);
            }
        }

        foreach ($parent->childNodes as $child) {
            if ($child instanceof DomElement) {
                new Nodes\Element($child);

                $this->walk($child);
            }

            else if ($child instanceof DomText) {
                new Nodes\Text($child);
            }
        }
    }
}