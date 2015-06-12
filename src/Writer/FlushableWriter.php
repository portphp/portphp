<?php

namespace Port\Writer;

use Port\Writer;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz>
 */
interface FlushableWriter extends Writer
{
    /**
     * Flush the output buffer
     */
    public function flush();
}
