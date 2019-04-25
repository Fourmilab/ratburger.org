<?php

namespace Dhii\Output;

use Dhii\Factory\FactoryInterface;

/**
 * Something that can create blocks.
 *
 * @since [*next-version*]
 */
interface BlockFactoryInterface extends FactoryInterface
{
    /**
     * The make config key that holds the content of the block.
     *
     * @since [*next-version*]
     */
    const K_CONTENT = 'content';

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     *
     * @return BlockInterface The new block
     */
    public function make($config = null);
}
