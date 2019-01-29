<?php

namespace Dhii\Di\Test;

use Dhii\Di\Container;
use Interop\Container\ServiceProvider as ServiceProviderInterface;
use Interop\Container\ContainerInterface;
use Xpmock\TestCase;

/**
 * Tests {@see Dhii\Di\Container}.
 *
 * @since [*next-version*]
 */
class ContainerTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\\Di\\Container';

    /**
     * Creates an instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @param BaseServiceProviderInterface $provider Service definitions to add to this container.
     * @param BaseContainerInterface       $parent   The container, which is to become this container's parent.
     *
     * @return Container
     */
    public function createInstance(ServiceProviderInterface $provider = null)
    {
        $mock = $this->mock(static::TEST_SUBJECT_CLASSNAME)
            ->new($provider);

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
     * Tests the service definition setter methods with a single service.
     *
     * @since [*next-version*]
     */
    public function testSetSingle()
    {
        $subject  = $this->createInstance();

        $pi       = $this->createDefinition(3.14159265359);
        $tz       = $this->createDefinition(new \DateTimeZone('Europe/Malta'));
        $expected = array(
            'pi' => $pi,
            'tz' => $tz
        );

        $subject->set('pi', $pi)
            ->set('tz', $tz);

        $this->assertEquals($expected, $subject->this()->serviceDefinitions);
    }

    /**
     * Tests the service definition setter methods with a service provider.
     *
     * @since [*next-version*]
     */
    public function testSetProvider()
    {
        $subject  = $this->createInstance();

        $pi       = $this->createDefinition(3.14159265359);
        $tz       = $this->createDefinition(new \DateTimeZone('Europe/Malta'));
        $provider = new \Dhii\Di\ServiceProvider(array(
            'pi' => $pi,
            'tz' => $tz
        ));

        $subject->set($provider);

        $this->assertEquals($provider->getServices(), $subject->this()->serviceDefinitions);
    }

    /**
     * Tests the service provider register method to ensure that all service definitions in the
     * provider are registered to the container.
     *
     * @since [*next-version*]
     */
    public function testRegister()
    {
        $subject  = $this->createInstance();

        $pi       = $this->createDefinition(3.14159265359);
        $tz       = $this->createDefinition(new \DateTimeZone('Europe/Malta'));
        $provider = new \Dhii\Di\ServiceProvider(array(
            'pi' => $pi,
            'tz' => $tz
        ));

        $subject->register($provider);

        $this->assertEquals($provider->getServices(), $subject->this()->serviceDefinitions);
    }
}
