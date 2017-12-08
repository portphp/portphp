<?php

namespace Port\Reader;

/**
 * Reads an array
 *
 * @author David de Boer <david@ddeboer.nl>
 */
class ArrayReader extends \ArrayIterator implements CountableReader
{
    /**
     * {@inheritdoc}
     */
    public function getColumnHeaders()
    {
        $current = $this->current();
        if (empty($current)) {
            reutrn array();
        }

        return array_keys($current);
    }
}
