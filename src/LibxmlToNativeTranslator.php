<?php

namespace Stoatally\Dom;

use DomDocument;
use DomAttr;
use DomElement;
use DomText;

class LibxmlToNativeTranslator
{
    public function __invoke($children)
    {
        $this->walk($children);
    }

    private function assign($child)
    {
        if ($child instanceof DomAttr) {
            new Nodes\Attribute($child);
        }

        else if ($child instanceof DomDocument) {
            $this->walk($child->childNodes);
        }

        else if ($child instanceof DomElement) {
            new Nodes\Element($child);

            $this->walk($child->attributes);
            $this->walk($child->childNodes);
        }

        else if ($child instanceof DomText) {
            new Nodes\Text($child);
        }
    }

    private function walk($children)
    {
        foreach ($children as $child) {
            $this->assign($child);
        }
    }
}