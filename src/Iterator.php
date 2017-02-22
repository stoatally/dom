<?php

namespace Stoatally\DocumentObjectModel;

use ArrayAccess;
use Countable;
use Traversable;

interface Iterator extends ArrayAccess, Countable, Node, ImportableNode
{
}