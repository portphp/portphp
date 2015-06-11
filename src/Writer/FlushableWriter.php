<?php

namespace Port\Writer;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz>
 */
interface FlushableWriter
{
    /**
     * Flush the output buffer
     */
    public function flush();
}
