<?php

namespace Stoatally\Dom\NodeTypes;

trait ChildNodeTrait {
    public function after($value): ChildNode
    {
        $node = $this->import($value);

        if (isset($this->getLibxml()->nextSibling)) {
            $node->setLibxml(
                $this->getParent()->getLibxml()->insertBefore(
                    $node->getLibxml(), $this->getLibxml()->nextSibling
                )
            );

            return $node;
        }

        return $this->getParent()->append($node);
    }

    public function before($value): ChildNode
    {
        $node = $this->import($value);

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

    public function replace($value): ChildNode
    {
        $node = $this->import($value);

        $node->setLibxml(
            $this->getParent()->getLibxml()->replaceChild(
                $node->getLibxml(), $this->getLibxml()
            )
        );

        return $node;
    }

    public function wrap($value): ChildNode
    {
        $parent = $this->before($value);
        $parent->append($this);

        return $parent;
    }
}