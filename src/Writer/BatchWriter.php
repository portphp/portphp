<?php

namespace Port\Writer;

use Port\Writer;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz>
 */
class BatchWriter implements Writer
{
    /**
     * @var Writer
     */
    private $delegate;

    /**
     * @var integer
     */
    private $size;

    /**
     * @var \SplQueue
     */
    private $queue;

    /**
     * @param Writer  $delegate
     * @param integer $size
     */
    public function __construct(Writer $delegate, $size = 20)
    {
        $this->delegate = $delegate;
        $this->size = $size;
    }

    /**
     * {@inheritdoc}
     */
    public function prepare()
    {
        $this->delegate->prepare();

        $this->queue = new \SplQueue();
    }

    /**
     * {@inheritdoc}
     */
    public function writeItem(array $item)
    {
        $this->queue->push($item);

        if (count($this->queue) >= $this->size) {
            $this->flush();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function finish()
    {
        $this->flush();

        $this->delegate->finish();
    }

    /**
     * Flush the internal buffer to the delegated writer
     */
    private function flush()
    {
        foreach ($this->queue as $item) {
            $this->delegate->writeItem($item);
        }

        if ($this->delegate instanceof FlushableWriter) {
            $this->delegate->flush();
        }
    }
}
