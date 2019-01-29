<?php

namespace Dhii\Di\FuncTest\Exception;

use Dhii\Di\Exception\NotFoundException;
use Xpmock\TestCase;

/**
 * Tests {@see Dhii\Di\Exception\NotFoundException}.
 *
 * @since 0.1
 */
class NotFoundExceptionTest extends TestCase
{
    /**
     * The name of the test subject.
     *
     * @since 0.1
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\\Di\\Exception\\NotFoundException';

    /**
     * Creates a new instance of the test subject.
     *
     * @since 0.1
     *
     * @return NotFoundException
     */
    public function createInstance($message, $code = 0, \Exception $previous = null)
    {
        return new NotFoundException($message, $code, $previous);
    }

    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since 0.1
     */
    public function testCanBeCreated()
    {
        $subject = $this->createInstance('');

        $this->assertInstanceOf(static::TEST_SUBJECT_CLASSNAME, $subject);
        $this->assertInstanceOf('Interop\\Container\\Exception\\NotFoundException', $subject);
        $this->assertInstanceOf('\Exception', $subject);
    }

    /**
     * Tests the constructor to ensure that the arguments given get set correctly.
     *
     * @since 0.1
     */
    public function testConstructor()
    {
        $subject = $this->createInstance('test message!', 5, new \Exception('previous exception'));

        $this->assertEquals('test message!', $subject->getMessage());
        $this->assertEquals(5, $subject->getCode());
        $this->assertEquals(new \Exception('previous exception'), $subject->getPrevious());
    }
}
