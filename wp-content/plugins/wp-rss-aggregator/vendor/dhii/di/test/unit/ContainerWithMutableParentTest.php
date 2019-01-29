<?php

namespace Dhii\Di\Test;

use Dhii\Di\Container;
use Dhii\Di\ContainerWithMutableParent;
use Interop\Container\ContainerInterface;
use Interop\Container\ServiceProvider as ServiceProviderInterface;
use Xpmock\TestCase;

/**
 * Tests {@see ContainerWithMutableParent}.
 *
 * @since [*next-version*]
 */
class ContainerWithMutableParentTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\\Di\\ContainerWithMutableParent';

    /**
     * Creates an instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @param BaseServiceProviderInterface $provider Service definitions to add to this container.
     * @param BaseContainerInterface       $parent   The container, which is to become this container's parent.
     *
     * @return ContainerWithMutableParent
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
     * Tests the parent container setter method to ensure that the parent container is correctly
     * assigned internally.
     *
     * @since [*next-version*]
     */
    public function testSetParentContainer()
    {
        $subject = $this->createInstance();
        $parent  = new Container();

        $subject->setParentContainer($parent);

        $this->assertEquals($parent, $subject->this()->parentContainer);
    }
}
