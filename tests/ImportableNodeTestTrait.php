<?php

namespace Stoatally\Dom;

trait ImportableNodeTestTrait
{
    private $document;

    public function __construct($document)
    {
        $this->document = $document;
    }
}