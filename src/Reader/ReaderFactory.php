<?php

namespace Port\Reader;

use Port\Reader;
use SplFileObject;

/**
 * Factory interface to create file based readers
 *
 * @author Lukas Kahwe Smith <smith@pooteeweet.org>
 */
interface ReaderFactory
{
    /**
     * @param SplFileObject $file
     *
     * @return Reader
     */
    public function getReader(SplFileObject $file);
}
