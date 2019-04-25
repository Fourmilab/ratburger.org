<?php

namespace Dhii\Output;

use Dhii\Factory\FactoryInterface;

/**
 * Something that can create templates.
 *
 * @since [*next-version*]
 */
interface TemplateFactoryInterface extends FactoryInterface
{
    /**
     * The make config key that holds the template, which the new instance will represent.
     *
     * @since [*next-version*]
     */
    const K_TEMPLATE = 'template';

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     *
     * @return TemplateInterface The new template.
     */
    public function make($config = null);
}
