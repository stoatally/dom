<?php

namespace Stoatally\Dom;

use DomNode;

interface SiblingNode {
    public function after($value): DomNode;

    public function before($value): DomNode;

    public function replace($value): DomNode;

    public function wrap($value): DomNode;
}