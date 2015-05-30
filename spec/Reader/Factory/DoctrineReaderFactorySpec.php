<?php

namespace spec\Port\Reader\Factory;

use Doctrine\Common\Persistence\ObjectManager;
use PhpSpec\ObjectBehavior;

class DoctrineReaderFactorySpec extends ObjectBehavior
{
    function let(ObjectManager $objectManager)
    {
        $this->beConstructedWith($objectManager);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Port\Reader\Factory\DoctrineReaderFactory');
    }

    function it_creates_a_reader()
    {
        $this->getReader('Entity')->shouldHaveType('Port\Reader\DoctrineReader');
    }
}
