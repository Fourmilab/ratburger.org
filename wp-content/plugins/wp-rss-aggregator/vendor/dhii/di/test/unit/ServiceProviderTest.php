<?php

namespace Dhii\Di\Test;

use Dhii\Di\ContainerInterface;
use Dhii\Di\ServiceProvider;
use Xpmock\TestCase;

/**
 * Tests {@see Dhii\Di\ServiceProvider}.
 *
 * @since [*next-version*]
 */
class ServiceProviderTest extends TestCase
{
    /**
     * Creates an instance of the test subject.
     *
     * @param array $definitions Array of service definitions. Default: array
     *
     * @return ServiceProvider The created instance.
     */
    public function createInstance(array $definitions = array())
    {
        return $this->mock('Dhii\\Di\\ServiceProvider')
            ->new($definitions);
    }

    /**
     * Creates a container.
     *
     * @since [*next-version*]
     *
     * @return ContainerInterface
     */
    public function createContainer(ServiceProvider $serviceProvider = null)
    {
        $mock = $this->mock('Dhii\\Di\\Container')
                ->new($serviceProvider);

        return $mock;
    }

    /**
     * Creates a mock object to act as a service.
     *
     * @since [*next-version*]
     *
     * @param array $methods Optional array of callables.
     *
     * @return \stdClass
     */
    public function createService(array $methods = array())
    {
        return $this->mock('stdClass', $methods)->new();
    }

    /**
     * Create a service definition that returns a simple value.
     *
     * @since [*next-version*]
     *
     * @param mixed $value The value that the service definition will return.
     *
     * @return callable A service definition that will return the given value.
     */
    public function createDefinition($value)
    {
        return function(ContainerInterface $container, $previous = null) use ($value) {
            return $value;
        };
    }

    /**
     * Tests the constructor without any arguments.
     *
     * No services are expected to be returned when {@see ServiceProvider::getServices} is called.
     *
     * @since [*next-version*]
     */
    public function testConstructorNoArgs()
    {
        $subject = $this->createInstance();

        $this->assertEmpty($subject->this()->serviceDefinitions);
    }

    /**
     * Tests the constructor with some arguments.
     *
     * The argument services are expected to be included in the returned array when
     * {@see ServiceProvider::getServices} is called.
     *
     * @since [*next-version*]
     */
    public function testConstructorSomeArgs()
    {
        $definitions = array(
            'one' => $this->createDefinition($this->createService()),
            'two' => $this->createDefinition($this->createService())
        );
        $subject = $this->createInstance($definitions);

        $this->assertEquals($definitions, $subject->this()->serviceDefinitions);
    }

    /**
     * Tests the service getter method when no services exist in the provider to ensure that
     * no services are returned.
     *
     * @since [*next-version*]
     */
    public function testGetServicesEmpty()
    {
        $subject = $this->createInstance();

        $this->assertEmpty($subject->getServices());
    }

    /**
     * Tests the service getter method with some pre-set service definitions to ensure that the
     * same services are returned.
     *
     * @since [*next-version*]
     */
    public function testGetServicesSomeServices()
    {
        $definitions = array(
            'one' => $this->createDefinition($this->createService()),
            'two' => $this->createDefinition($this->createService())
        );
        $subject = $this->createInstance($definitions);

        $this->assertEquals($definitions, $subject->getServices());
    }

    /**
     * Registers the provider to a container to ensure that the services are correctly registered
     * to that container.
     *
     * @since [*next-version*]
     */
    public function testContainerRegister()
    {
        $definitions = array(
            'one' => $this->createDefinition($this->createService()),
            'two' => $this->createDefinition($this->createService())
        );
        $subject = $this->createInstance($definitions);

        $container = $this->createContainer();
        $container->register($subject);

        $this->assertEquals($definitions['one'], $container->this()->serviceDefinitions['one']);
        $this->assertEquals($definitions['two'], $container->this()->serviceDefinitions['two']);
    }
}
