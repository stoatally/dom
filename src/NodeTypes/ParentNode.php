<?php

namespace Stoatally\Dom\NodeTypes;

interface ParentNode
{
    public function getChildren(): Iterator;

    public function setContent($value): Node;

    public function getContent(): ?string;

    public function setRawContent(string $xmlOrHtml): Node;

    public function getRawContent(): string;

    public function appendChild($value): Node;

    public function prependChild($value): Node;
}