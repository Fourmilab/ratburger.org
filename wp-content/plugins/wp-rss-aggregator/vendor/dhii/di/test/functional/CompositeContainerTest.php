<?php

namespace Dhii\Di\FuncTest;

use Dhii\Di\CompositeContainer;
use Dhii\Di\Exception\NotFoundException;
use Dhii\Di\Exception\ContainerException;
use Dhii\Di\ParentAwareContainerInterface;
use Dhii\Di\ServiceProvider as ServiceProvider2;
use Interop\Container\ContainerInterface;
use Interop\Container\ServiceProvider;
use Xpmock\TestCase;

/**
 * Tests {@see \Dhii\Di\CompositeContainer} and related classes.
 *
 * @since 0.1
 */
class CompositeContainerTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since 0.1
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\\Di\\CompositeContainer';

    /**
     * Creates a new instance of the test subject.
     *
     * @since 0.1
     *
     * @param ContainerInterface $parent The container, which is to become this container's parent.
     *
     * @return CompositeContainer
     */
    public function createInstance(ContainerInterface $parent = null)
    {
        $mock = $this->mock(static::TEST_SUBJECT_CLASSNAME)
                ->new($parent);

        return $mock;
    }

    /**
     * Creates a new service provider instance.
     *
     * @since 0.1
     *
     * @param array $definitions An array of service definitions.
     *
     * @return ServiceProvider2
     */
    public function createServiceProvider($definitions)
    {
        return new ServiceProvider2($definitions);
    }

    /**
     * Creates a new container instance.
     *
     * @since 0.1
     *
     * @param ServiceProvider    $definitions The service provider.
     * @param ContainerInterface $parent      The container instance which is the be the parent container.
     * @param bool               $isMutable   If true, the container will have its parent container be mutable; immutable if false.
     *
     * @return ParentAwareContainerInterface
     */
    public function createContainer(ServiceProvider $definitions, ContainerInterface $parent = null, $isMutable = true)
    {
        $className = $isMutable
                ? 'Dhii\\Di\\ContainerWithMutableParent'
                : 'Dhii\\Di\\ContainerWithImmutableParent';
        $mock = $this->mock($className)
                ->new($definitions, $parent);

        return $mock;
    }

    /**
     * Creates an instance of the most basic interop container.
     *
     * @since 0.1.1
     *
     * @return ContainerInterface The new instance.
     */
    public function createBaseContainer()
    {
        $instance = $this->mock('Interop\\Container\\ContainerInterface')
                ->get()
                ->has()
                ->new();

        return $instance;
    }

    /**
     * Create a service definition that returns a simple value.
     *
     * @since 0.1
     *
     * @param mixed $value The value that the service definition will return.
     *
     * @return callable A service definition that will return the given value.
     */
    public function createDefinition($value)
    {
        return function (ContainerInterface $container, $previous = null) use ($value) {
            return $value;
        };
    }

    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since 0.1.1
     */
    public function testCanBeCreated()
    {
        $subject = $this->createInstance();
        $this->assertInstanceOf('Dhii\\Di\\CompositeContainerInterface', $subject, 'A valid instance of the test subject could not be created');
        $this->assertInstanceOf('Dhii\\Di\\WritableCompositeContainerInterface', $subject, 'Test subject does not implement required interface');
        $this->assertInstanceOf('Dhii\\Di\\ParentAwareContainerInterface', $subject, 'Test subject does not implement required interface');
        $this->assertInstanceOf('Interop\\Container\\ContainerInterface', $subject, 'Test subject does not implement required interface');
    }

    /**
     * Tests whether an interop container can get added to the composite container.
     *
     * @since 0.1.1
     */
    public function testAdd()
    {
        $subject = $this->createInstance();
        $child = $this->createBaseContainer();

        $subject->add($child);
        $this->assertContains($child, $subject->getContainers(), 'Added container is not in the resulting container set');
    }

    /**
     * Tests that the constructor accepts correct args.
     *
     * @since 0.1.1
     */
    public function testConstructor()
    {
        $class = static::TEST_SUBJECT_CLASSNAME;
        $parent = $this->createBaseContainer();
        $subject = $this->createInstance($parent);

        $this->assertSame($parent, $subject->getParentContainer(), 'Parent set in constructor could not be retrieved');
    }

    /**
     * Tests whether services of child containers can be correctly retrieved from parent.
     * No relationships between services.
     *
     * @since 0.1
     */
    public function testOneLevelRetrieval()
    {
        $rootContainer = $this->createInstance();
        $childContainer1 = $this->createContainer(
            $this->createServiceProvider(array(
                'service1' => $this->createDefinition('service-1'),
                'service2' => $this->createDefinition('service-2'),
            )), // Servide definitions
            $rootContainer, // Parent
            false // Immutable
        );
        $childContainer2 = $this->createContainer(
            $this->createServiceProvider(array(
                'service3' => $this->createDefinition('service-3'),
            )), // Servide definitions
            $rootContainer, // Parent
            false // Immutable
        );
        $rootContainer->add($childContainer1);
        $rootContainer->add($childContainer2);

        $expected = array(
            'service1' => 'service-1',
            'service2' => 'service-2',
            'service3' => 'service-3',
        );

        $actual = array();
        foreach ($expected as $_key => $_value) {
            $actual[$_key] = $rootContainer->get($_key);
        }

        $this->assertEquals($expected, $actual, 'The container structure did not resolve services correctly');
    }

    /**
     * Tests whether services of child containers can be correctly retrieved from parent.
     * Some services have at most one relationship with a service in another container.
     *
     * @since 0.1
     */
    public function testTwoLevelRetrieval()
    {
        $me = $this;
        $rootContainer = $this->createInstance();

        $childContainer1 = $this->createContainer(
            $this->createServiceProvider(array(
                'service1' => function (ContainerInterface $container) use ($me) {
                    $me->assertInstanceOf('Dhii\Di\CompositeContainerInterface', $container, 'Container must be composite in order to retrieve definition from another container');

                    return implode('->', array('service-1', $container->get('service3')));
                },
                'service2' => $this->createDefinition('service-2'),
            )), // Servide definitions
            $rootContainer, // Parent
            false // Immutable
        );
        $this->assertSame($rootContainer, $childContainer1->getParentContainer(), 'Parent container could not be retrieved');

        $childContainer2 = $this->createContainer(
            $this->createServiceProvider(array(
                'service3' => $this->createDefinition('service-3'),
            )), // Servide definitions
            $rootContainer, // Parent
            false // Immutable
        );
        $this->assertSame($rootContainer, $childContainer2->getParentContainer(), 'Parent container could not be retrieved');

        $rootContainer->add($childContainer1);
        $rootContainer->add($childContainer2);
        $this->assertEquals(2, count($rootContainer->getContainers()), 'Incorrect number of child containers');

        $expected = array(
            'service1' => 'service-1->service-3',
            'service2' => 'service-2',
            'service3' => 'service-3',
        );

        $actual = array();
        foreach ($expected as $_key => $_value) {
            $actual[$_key] = $rootContainer->get($_key);
        }

        $this->assertEquals($expected, $actual, 'The container structure did not resolve services correctly');
    }

    /**
     * Tests whether services of child containers can be correctly retrieved from parent.
     * Some services have relationships with with other services, in different containers.
     * Some of the services are composite containers, which have their own services.
     * Those services have services from other containers referencing them.
     *
     * @since 0.1
     */
    public function testThreeLevelComplexRetrieval()
    {
        $me = $this;
        $rootContainer = $this->createInstance();
        $childContainer1 = $this->createContainer(
            $this->createServiceProvider(array(
                'service1' => $this->createDefinition('service-1'),
                'service2' => function (ContainerInterface $c) use ($me) {
                    $me->assertInstanceOf('Dhii\Di\CompositeContainerInterface', $c, 'Container must be composite in order to retrieve definition from another container');

                    return implode('->', array('service-2', $c->get('service3')));
                },
                'service7' => function (ContainerInterface $c) use ($me) {
                    $me->assertInstanceOf('Dhii\Di\CompositeContainerInterface', $c, 'Container must be composite in order to retrieve definition from another container');

                    return implode('->', array('service-7', $c->get('service4')));
                },
            )), // Servide definitions
            $rootContainer, // Parent
            false // Immutable
        );
        $this->assertSame($rootContainer, $childContainer1->getParentContainer(), 'Parent container could not be retrieved');

        $childContainer2 = $this->createContainer(
            $this->createServiceProvider(array(
                'service3' => $this->createDefinition('service-3'),
                'service4' => function (ContainerInterface $c) use ($me) {
                    $me->assertInstanceOf('Dhii\Di\CompositeContainerInterface', $c, 'Container must be composite in order to retrieve definition from another container');

                    return implode('->', array('service-4', $c->get('service5')));
                },
            )), // Servide definitions
            $rootContainer, // Parent
            false // Immutable
        );
        $this->assertSame($rootContainer, $childContainer2->getParentContainer(), 'Parent container could not be retrieved');

        // This one is a nested composite container
        $childContainer3 = $this->createInstance($rootContainer);
        $childContainer3->add($this->createContainer(
            $this->createServiceProvider(array(
                'service5' => function (ContainerInterface $c) use ($me) {
                    $me->assertInstanceOf('Dhii\Di\CompositeContainerInterface', $c, 'Container must be composite in order to retrieve definition from another container');

                    return implode('->', array('service-5', $c->get('service8')));
                },
                'service6' => $this->createDefinition('service-6'),
            )),
            $rootContainer,
            false
        ));
        $childContainer3->add($this->createContainer(
            $this->createServiceProvider(array(
                'service8' => function (ContainerInterface $c) use ($me) {
                    $me->assertInstanceOf('Dhii\Di\CompositeContainerInterface', $c, 'Container must be composite in order to retrieve definition from another container');

                    return implode('->', array('service-8', $c->get('service1')));
                },
                'service9' => function (ContainerInterface $c) use ($me) {
                    $me->assertInstanceOf('Dhii\Di\CompositeContainerInterface', $c, 'Container must be composite in order to retrieve definition from another container');

                    return implode('->', array('service-9', $c->get('service6')));
                },
            )),
            $rootContainer,
            false
        ));

        $rootContainer->add($childContainer1);
        $rootContainer->add($childContainer2);
        $rootContainer->add($childContainer3);

        $expected = array(
            'service1' => 'service-1',
            'service2' => 'service-2->service-3',
            'service3' => 'service-3',
            'service4' => 'service-4->service-5->service-8->service-1',
            'service5' => 'service-5->service-8->service-1',
            'service6' => 'service-6',
            'service7' => 'service-7->service-4->service-5->service-8->service-1',
            'service8' => 'service-8->service-1',
            'service9' => 'service-9->service-6',
        );

        $actual = array();
        foreach ($expected as $_key => $_value) {
            $actual[$_key] = $rootContainer->get($_key);
        }

        $this->assertEquals($expected, $actual, 'The container structure did not resolve services correctly');
    }

    /**
     * Tests the method that creates a {@see NotFoundException}.
     *
     * @since 0.1.1
     */
    public function testCreateNotFoundException()
    {
        $subject = $this->createInstance();

        $previous = new \Exception('test message', 3, null);
        $exception = $subject->this()->_createNotFoundException('test message', 3, $previous);

        $this->assertInstanceOf('Dhii\\Di\\Exception\\NotFoundException', $exception);
        $this->assertInstanceOf('Dhii\\Di\\ExceptionInterface', $exception);
        $this->assertInstanceOf('Interop\\Container\\Exception\\NotFoundException', $exception);

        $this->assertEquals('test message', $exception->getMessage());
        $this->assertEquals(3, $exception->getCode());
        $this->assertEquals($previous, $exception->getPrevious());
    }

    /**
     * Tests the method that creates a {@see ContainerException}.
     *
     * @since 0.1.1
     */
    public function testContainerException()
    {
        $subject = $this->createInstance();

        $previous = new \Exception('test message', 3, null);
        $exception = $subject->this()->_createContainerException('test message', 3, $previous);

        $this->assertInstanceOf('Dhii\\Di\\Exception\\ContainerException', $exception);
        $this->assertInstanceOf('Dhii\\Di\\ExceptionInterface', $exception);
        $this->assertInstanceOf('Interop\\Container\\Exception\\ContainerException', $exception);

        $this->assertEquals('test message', $exception->getMessage());
        $this->assertEquals(3, $exception->getCode());
        $this->assertEquals($previous, $exception->getPrevious());
    }
}
