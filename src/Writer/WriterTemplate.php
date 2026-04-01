<?php

namespace Port\Writer;

/**
 * This template can be overridden in concrete implementations
 *
 * @author David de Boer <david@ddeboer.nl>
 */
trait WriterTemplate
{
    /**
     * {@inheritdoc}
     */
    public function prepare(): void
    {

    }

    /**
     * {@inheritdoc}
     */
    public function finish(): void
    {

    }
}
