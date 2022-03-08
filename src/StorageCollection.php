<?php

namespace Xuedi\PhpSysMon;

use ArrayIterator;
use IteratorIterator;

class StorageCollection extends IteratorIterator
{
    public function __construct(Storage ...$storage)
    {
        parent::__construct(new ArrayIterator($storage));
    }

    public function current(): Storage
    {
        return parent::current();
    }

    public function add(Storage $storage): void
    {
        $this->getInnerIterator()->append($storage);
    }
}
