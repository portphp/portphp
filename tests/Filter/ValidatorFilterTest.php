<?php

namespace Port\Tests\Filter;

use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as Mock;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Constraints;
use Port\Filter\ValidatorFilter;
use Port\Exception\ValidationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidatorFilterTest extends TestCase
{
    /** @var ValidatorFilter */
    private $filter;

    /** @var Mock|ValidatorInterface */
    private $validator;

    protected function setUp(): void
    {
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->filter = new ValidatorFilter($this->validator);
    }

    public function testFilterWithValid()
    {
        $item = array('foo' => 'bar');

        $list = new ConstraintViolationList();

        $this->validator->expects($this->once())
            ->method('validate')
            ->will($this->returnValue($list));

        $this->assertTrue(call_user_func($this->filter, $item));
    }

    public function testFilterWithInvalidItem()
    {
        $item = array('foo' => 'bar');

        $violation = $this->createMock(ConstraintViolationInterface::class);
        $list = new ConstraintViolationList(array($violation));

        $this->validator->expects($this->once())
            ->method('validate')
            ->will($this->returnValue($list));

        $this->assertFalse(call_user_func($this->filter, $item));

        $this->assertEquals(array(1 => $list), $this->filter->getViolations());
    }

    public function testStopOnFirstError()
    {
        $this->filter->throwExceptions();

        $item = array('foo' => 'bar');

        $violation = $this->createMock(ConstraintViolationInterface::class);
        $violation->expects($this->any())->method('getMessage')->willReturn('Message');
        $list = new ConstraintViolationList(array($violation));

        $this->validator->expects($this->once())
            ->method('validate')
            ->will($this->returnValue($list));

        try {
            call_user_func($this->filter, $item);
            $this->fail('ValidationException should be thrown');
        } catch (ValidationException $e) {
            $this->assertSame(1, $e->getLineNumber());
            $this->assertEquals($list, $e->getViolations());
            $this->assertEquals('Line 1: Message', $e->getMessage());
        }
    }

    public function testFilterNonStrict()
    {
        $this->filter->setStrict(false);
        $this->validator->expects($this->once())
            ->method('validate')
            ->will($this->returnValue( new ConstraintViolationList()));

        $item = array('foo' => true, 'bar' => true);

        $this->filter->add('foo', new Constraints\IsTrue());
        $this->assertTrue(call_user_func($this->filter, $item));
    }

    public function testFilterLineNumbers()
    {
        $this->filter->throwExceptions();

        $item = array('foo' => 'bar');

        $violation = $this->createMock(ConstraintViolationInterface::class);
        $list = new ConstraintViolationList(array($violation));

        $this->validator->expects($this->exactly(2))
            ->method('validate')
            ->will($this->returnValue($list));

        try {
            $this->assertTrue(call_user_func($this->filter, $item));
            $this->fail('ValidationException should be thrown (1)');
        } catch (ValidationException $e) {
            $this->assertSame(1, $e->getLineNumber());
            $this->assertEquals($list, $e->getViolations());
        }

        try {
            $this->assertTrue(call_user_func($this->filter, $item));
            $this->fail('ValidationException should be thrown (2)');
        } catch (ValidationException $e) {
            $this->assertSame(2, $e->getLineNumber());
            $this->assertEquals($list, $e->getViolations());
        }
    }
}
