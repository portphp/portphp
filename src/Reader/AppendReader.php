<?php

namespace Port\Reader;

use AppendIterator;
use Iterator;
use Port\Reader;

/**
 * Read data from multiple Readers in one workflow.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
final class AppendReader extends AppendIterator implements Reader
{
    /**
     * @param Reader[] $readers
     */
    public function __construct(array $readers = [])
    {
        parent::__construct();

        foreach ($readers as $reader) {
            $this->addReader($reader);
        }
    }

    /**
     * Safety check method to make sure a Reader is passed.
     *
     * @param Reader $reader
     */
    public function addReader(Reader $reader)
    {
        parent::append($reader);
    }

    /**
     * {@inheritdoc}
     */
    public function append(Iterator $iterator)
    {
        $this->addReader($iterator);
    }

    /**
     * {@inheritdoc}
     */
    public function getFields()
    {
        return [];
    }
}
