<?php

declare(strict_types=1);

namespace Port;

/**
 * Iterator that reads data to be imported
 *
 * @author David de Boer <david@ddeboer.nl>
 */
interface Reader
{
    public function getItems(): \Generator;
}
