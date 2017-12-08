<?php

namespace Port;

/**
 * Iterator that reads data to be imported
 *
 * @author David de Boer <david@ddeboer.nl>
 */
interface Reader extends \Iterator
{
    /**
     * Get column headers
     *
     * @return array
     */
    public function getColumnHeaders();
}
