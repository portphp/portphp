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
     * Get the field (column, property) names
     *
     * @return array
     */
    public function getFields();
}
