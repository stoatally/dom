<?php

namespace Stoatally\DocumentObjectModel;

trait ImportableNodeTestTrait
{
    private $document;

    public function __construct($document)
    {
        $this->document = $document;
    }
}