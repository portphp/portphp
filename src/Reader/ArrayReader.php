<?php

declare(strict_types=1);

namespace Port\Reader;

use Assert\Assertion;
use Port\Reader;

/**
 * Reads an array
 *
 * @author David de Boer <david@ddeboer.nl>
 */
class ArrayReader implements Reader, CountableReader
{
    private $data;

    public function __construct($data)
    {
        Assertion::isTraversable($data);

        $this->data = $data;
    }

    public function getItems(): \Generator
    {
        foreach ($this->data as $key => $value) {
            yield $key => $value;
        }
    }

    public function count()
    {
        $cnt = 0;

        foreach ($this->data as $_) {
            $cnt++;
        }

        return $cnt;
    }
}
