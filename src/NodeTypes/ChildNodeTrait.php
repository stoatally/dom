<?php

namespace Stoatally\Dom\NodeTypes;

trait ChildNodeTrait {
    public function appendSibling($value): ChildNode
    {
        $node = $this->importNode($value);

        if (isset($this->getLibxml()->nextSibling)) {
            $node->setLibxml(
                $this->getParent()->getLibxml()->insertBefore(
                    $node->getLibxml(), $this->getLibxml()->nextSibling
                )
            );

            return $node;
        }

        return $this->getParent()->appendChild($node);
    }

    public function prependSibling($value): ChildNode
    {
        $node = $this->importNode($value);

        $node->setLibxml(
            $this->getParent()->getLibxml()->insertBefore(
                $node->getLibxml(), $this->getLibxml()
            )
        );

        return $node;
    }

    public function remove(): ChildNode
    {
        $this->setLibxml(
            $this->getParent()->getLibxml()->removeChild(
                $this->getLibxml()
            )
        );

        return $this;
    }

    public function replaceWith($value): ChildNode
    {
        $node = $this->importNode($value);

        $node->setLibxml(
            $this->getParent()->getLibxml()->replaceChild(
                $node->getLibxml(), $this->getLibxml()
            )
        );

        return $node;
    }

    public function wrapWith($value): ChildNode
    {
        $parent = $this->prependSibling($value);
        $parent->appendChild($this);

        return $parent;
    }
}