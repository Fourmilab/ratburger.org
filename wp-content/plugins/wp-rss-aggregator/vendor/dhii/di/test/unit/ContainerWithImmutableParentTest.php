<?php

namespace Dhii\Di\Test;

use Dhii\Di\Container;
use Dhii\Di\ContainerWithImmutableParent;
use Dhii\Di\ServiceProvider;
use Interop\Container\ContainerInterface;
use Interop\Container\ServiceProvider as ServiceProviderInterface;
use Xpmock\TestCase;

/**
 * Tests {@see ContainerWithImmutableParent}.
 *
 * @since [*next-version*]
 */
class ContainerWithImmutableParentTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\\Di\\ContainerWithImmutableParent';

    /**
     * Creates an instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @param BaseServiceProviderInterface $provider Service definitions to add to this container.
     * @param BaseContainerInterface       $parent   The container, which is to become this container's parent.
     *
     * @return ContainerWithImmutableParent
     */
    public function createInstance(ServiceProviderInterface $provider = null, ContainerInterface $parent = null)
    {
        $mock = $this->mock(static::TEST_SUBJECT_CLASSNAME)
            ->new($provider, $parent);

        return $mock;
    }

    /**
     * Creates a service definition that simply returns a value.
     *
     * @since [*next-version*]
     *
     * @param mixed $value The value that the service definition should return.
     *
     * @return callable
     */
    public function createDefinition($value)
    {
        return function(ContainerInterface $container, $previous = null) use ($value) {
            return $value;
        };
    }

    /**
     * Tests the constructor without arguments.
     *
     * @since [*next-version*]
     */
    public function testConstructorNoArgs()
    {
        $subject = $this->createInstance();

        $this->assertEmpty($subject->this()->serviceDefinitions);
        $this->assertNull($subject->this()->parentContainer);
    }

    /**
     * Tests the constructor with just the service provider argument.
     *
     * @since [*next-version*]
     */
    public function testConstructorServiceProvider()
    {
        $definitions = array(
            'one' => $this->createDefinition(1),
            'two' => $this->createDefinition(2)
        );
        $subject = $this->createInstance(new ServiceProvider($definitions));

        $this->assertEquals($definitions, $subject->this()->serviceDefinitions);
        $this->assertNull($subject->this()->parentContainer);
    }

    /**
     * Tests the constructor without arguments.
     *
     * @since [*next-version*]
     */
    public function testConstructorParentContainer()
    {
        $subject = $this->createInstance(null, new Container());

        $this->assertEmpty($subject->this()->serviceDefinitions);
        $this->assertEquals(new Container(), $subject->this()->parentContainer);
    }

    /**
     * Tests the parent container getter method to validate if the returned instance is correct.
     *
     * @since [*next-version*]
     */
    public function testGetParentContainer()
    {
        $subject = $this->createInstance();
        $parent = new Container();

        $subject->this()->parentContainer = $parent;

        $this->assertTrue($parent === $subject->getParentContainer());
    }

    /**
     * Tests the service definition getter to ensure that it correct returns a service
     * instance.
     *
     * This test also ensures that multiple calls with the same ID return the same instance.
     *
     * @since [*next-version*]
     */
    public function testGet()
    {
        $subject = $this->createInstance(new ServiceProvider(array(
            'one' => $this->createDefinition(1),
            'two' => $this->createDefinition(2)
        )));

        $this->assertEquals(1, $subject->get('one'));

        $this->assertEquals($subject->get('two'), $subject->get('two'));

        $this->setExpectedException('Interop\\Container\\Exception\\NotFoundException');

        $subject->get('three');
    }

    /**
     * Tests the service definition checker method to ensure that it correctly determines if
     * the container has a service or not.
     *
     * @since [*next-version*]
     */
    public function testHas()
    {
        $subject = $this->createInstance(new ServiceProvider(array(
            'one' => $this->createDefinition(1),
            'two' => $this->createDefinition(2)
        )));

        $this->assertTrue($subject->has('one'));
        $this->assertTrue($subject->has('two'));
        $this->assertFalse($subject->has('three'));
    }
}
