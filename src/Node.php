<?php

namespace Stoatally\DocumentObjectModel;

use DomDocument;
use DomNode;

interface Node {
    public function import($value): DomNode;

    public function getDocument(): DomDocument;

    public function after($value): DomNode;

    public function before($value): DomNode;

    public function append($value): DomNode;

    public function prepend($value): DomNode;

    public function replace($value): DomNode;

    public function set($value): DomNode;

    public function get(): ?string;
}