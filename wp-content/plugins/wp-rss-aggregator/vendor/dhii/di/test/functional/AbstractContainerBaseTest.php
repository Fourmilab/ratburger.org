<?php

namespace Dhii\Di\FuncTest;

use \Dhii\Di\AbstractContainerBase;
use \Xpmock\TestCase;

/**
 * Tests {@see Dhii\Di\AbstractContainerBase}.
 *
 * @since 0.1
 */
class AbstractContainerBaseTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since 0.1
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\\Di\\AbstractContainerBase';

    /**
     * Creates a new instance of the test subject.
     *
     * @since 0.1
     *
     * @return AbstractContainerBase
     */
    public function createInstance(array $definitions = array())
    {
        $mock = $this->mock(static::TEST_SUBJECT_CLASSNAME)
            ->new();

        $mock->this()->serviceDefinitions = $definitions;

        return $mock;
    }

    /**
     * Create a service definition that returns a simple value.
     *
     * @param mixed $value The value that the service definition will return.
     * @return callable A service definition that will return the given value.
     */
    public function createDefinition($value)
    {
        return function($container = null, $previous = null) use ($value) {
            return $value;
        };
    }

    /**
     * Tests the service definition checker method to ensure that it correctly determines if a
     * service ID exists in the container or not.
     *
     * @since 0.1
     */
    public function testHas()
    {
        $subject = $this->createInstance(array(
            'test'   => $this->createDefinition('test value'),
            'random' => $this->createDefinition(123.456),
        ));

        $this->assertTrue($subject->has('test'));
        $this->assertTrue($subject->has('random'));
        $this->assertFalse($subject->has('foobar'));
        $this->assertFalse($subject->has('some_key'));
    }

    /**
     * Tests the service definitions getter method to ensure that it correctly returns a valid
     * service instance.
     *
     * This test also verifies whether the same instance of a service is returned by multiple
     * calls with the same service ID.
     *
     * @since 0.1
     */
    public function testGet()
    {
        $definitions = array(
            'test'   => $this->createDefinition('test value'),
            'exception'   => function() {
                return new \Exception('');
            }
        );
        $subject = $this->createInstance($definitions);

        $this->assertEquals($definitions['test'](), $subject->get('test'));
        $this->assertTrue($subject->get('exception') === $subject->get('exception'));
    }

    /**
     * Tests the factory method to ensure that a new instance is returned with each call.
     *
     * @since 0.1
     */
    public function testMake()
    {
        $definitions = array(
            'exception'   => function() {
                return new \Exception('');
            }
        );
        $subject = $this->createInstance($definitions);

        $this->assertFalse($subject->make('exception') === $subject->make('exception'));
    }

    /**
     * Tests the creation of a NotFoundException to ensure that the correct type is instantiated.
     *
     * @since 0.1
     */
    public function testCreateNotFoundException()
    {
        $subject   = $this->createInstance();
        $exception = $subject->this()->_createNotFoundException('');

        $this->assertInstanceOf('Dhii\\Di\\Exception\\NotFoundException', $exception);
        $this->assertInstanceOf('Interop\\Container\\Exception\\NotFoundException', $exception);
    }

    /**
     * Tests the creation of a ContainerException to ensure that the correct type is instantiated.
     *
     * @since 0.1
     */
    public function testCreateContainerException()
    {
        $subject   = $this->createInstance();
        $exception = $subject->this()->_createContainerException('');

        $this->assertInstanceOf('Dhii\\Di\\Exception\\ContainerException', $exception);
        $this->assertInstanceOf('Interop\\Container\\Exception\\ContainerException', $exception);
    }
}
