<?php

namespace Port\Tests\Writer;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject as Mock;
use Port\Writer;
use Port\Writer\BatchWriter;

class BatchWriterTest extends TestCase
{
    /**
     * @var Mock|Writer
     */
    private $delegate;

    protected function setUp(): void
    {
        $this->delegate = $this->createMock('Port\Writer');
    }

    public function testWriteItem()
    {
        $writer = new BatchWriter($this->delegate);

        $this
            ->delegate
            ->expects($this->once())
            ->method('prepare');

        $this
            ->delegate
            ->expects($this->never())
            ->method('writeItem');

        $writer->prepare();
        $writer->writeItem(['Test']);
    }

    public function testFlush()
    {
        $writer = new BatchWriter($this->delegate);

        $this
            ->delegate
            ->expects($this->exactly(20))
            ->method('writeItem');

        $writer->prepare();

        for ($i = 0; $i < 20; $i++) {
            $writer->writeItem(['Test']);
        }
    }

    public function testMultipleBatches()
    {
        $writer = new BatchWriter($this->delegate, 1);

        $this
            ->delegate
            ->expects($this->exactly(3))
            ->method('writeItem');

        $writer->prepare();

        for ($i = 0; $i < 3; $i++) {
            $writer->writeItem(['Test']);
        }
    }
}
