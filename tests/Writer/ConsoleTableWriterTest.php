<?php

namespace Port\Tests\Writer;

use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Helper\Table;

use Port\ItemConverter\MappingItemConverter;
use Port\Writer\ConsoleTableWriter;

/**
 *  @author Igor Mukhin <igor.mukhin@gmail.com>
 */
class ConsoleTableWriterTest extends \PHPUnit_Framework_TestCase
{
    public function testRightColumnsHeadersNamesAfterItemConverter()
    {
        $data = array(
            array(
                'firstname'  => 'John',
                'lastname' => 'Doe'
            ),
            array(
                'firstname'  => 'Ivan',
                'lastname' => 'Sidorov'
            )
        );

        $output = new BufferedOutput();

        $table = $this->getMockBuilder('Symfony\Component\Console\Helper\Table')
            ->disableOriginalConstructor()
            ->getMock();

        $table->expects($this->at(2))
            ->method('addRow');

        $writer = new ConsoleTableWriter($output, $table);

        $writer->prepare();

        foreach ($data as $item) {
            $writer->writeItem($item);
        }

        $writer->finish();
    }
}
