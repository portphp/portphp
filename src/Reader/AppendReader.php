<?php

declare(strict_types=1);

namespace Port\Reader;

use Port\Reader;

/**
 * Read data from multiple Readers in one workflow.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
final class AppendReader implements Reader, CountableReader
{
    private $readers = [];

    /**
     * @param Reader[] $readers
     */
    public function __construct(array $readers = [])
    {
        foreach ($readers as $reader) {
            $this->addReader($reader);
        }
    }

    public function getItems(): \Generator
    {
        foreach ($this->readers as $reader) {
            yield from $reader->getItems();
        }
    }

    public function count()
    {
        $cnt = 0;

        foreach ($this->readers as $reader) {
            foreach ($reader->getItems() as $_) {
                $cnt++;
            }
        }

        return $cnt;
    }

    /**
     * Safety check method to make sure a Reader is passed.
     *
     * @param Reader $reader
     */
    public function addReader(Reader $reader)
    {
        $this->readers[] = $reader;
    }
}
