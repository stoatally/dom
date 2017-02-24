<?php

namespace Stoatally\Dom\NodeTypes;

use DomNode;
use Stoatally\Dom\Nodes;

trait ParentNodeTrait
{
    public function getChildren(): Iterator
    {
        $results = [];

        foreach ($this->getLibxml()->childNodes as $child) {
            $results[] = $child->native;
        }

        return new Nodes\Iterator($this->getDocument(), $results);
    }

    public function setContent($value): Node
    {
        $this->getLibxml()->nodeValue = null;
        $this->appendChild($this->importNode($value));

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->getLibxml()->nodeValue;
    }

    public function setRawContent(string $xmlOrHtml): Node
    {
        $fragment = $this->getDocument()->createDocumentFragment();
        $fragment->appendXml($xmlOrHtml);

        $this->getLibxml()->nodeValue = null;
        $this->appendChild($fragment);

        return $this;
    }

    public function getRawContent(): string
    {
        $xmlOrHtml = null;

        foreach ($this->getLibxml()->childNodes as $child) {
            $xmlOrHtml .= $this->getDocument()->getLibxml()->saveXML($child);
        }

        return $xmlOrHtml;
    }

    public function appendChild($value): Node
    {
        $node = $this->importNode($value);

        $node->setLibxml(
            $this->getLibxml()->appendChild($node->getLibxml())
        );

        return $node;
    }

    public function prependChild($value): Node
    {
        $node = $this->importNode($value);

        if ($this->getLibxml()->firstChild) {
            $node->setLibxml(
                $this->getLibxml()->insertBefore(
                    $node->getLibxml(), $this->getLibxml()->firstChild
                )
            );

            return $node;
        }

        return $this->appendChild($node);
    }
}