# Dhii - Output - Renderer - Abstract

[![Build Status](https://travis-ci.org/Dhii/output-renderer-abstract.svg?branch=develop)](https://travis-ci.org/Dhii/output-renderer-abstract)
[![Code Climate](https://codeclimate.com/github/Dhii/output-renderer-abstract/badges/gpa.svg)](https://codeclimate.com/github/Dhii/output-renderer-abstract)
[![Test Coverage](https://codeclimate.com/github/Dhii/output-renderer-abstract/badges/coverage.svg)](https://codeclimate.com/github/Dhii/output-renderer-abstract/coverage)
[![Latest Stable Version](https://poser.pugx.org/dhii/output-renderer-abstract/version)](https://packagist.org/packages/dhii/output-renderer-abstract)
[![This package complies with Dhii standards](https://img.shields.io/badge/Dhii-Compliant-green.svg?style=flat-square)][Dhii]

Common abstract functionality for output renderers.

## Details
This package provides abstract functionality for the most common implementations
of output renderers. The classes are meant to implement functionality for
interfaces in [`dhii/output-renderer-interface`], although of course they can
be used for other purposes. This is a good place to start if you are creating
your own renderer of a new kind. If you are looking for a little more
specialized yet common functionality that extends this, see
[`dhii/output-renderer-base`].

### Classes
- [`StringableRenderCatcherTrait`] - Intended for [`BlockInterface`], which is usually
something that contains all data needed to produce output. Takes care of what
happens when it is cast to string.
- [`RenderCapableTemplateBlockTrait`] - Renders a template using an internal context and template.
- [`BlockAwareTrait`] - Internal functionality for storing and retrieving a
block. Does minimal validation. Can be used to back up [`BlockAwareInterface`].
- [`ContextAwareTrait`] - Internal functionality for storing and retrieving a
context in the form of a [`ContainerInterface`]. Can be used to back up [`ContextAwareInterface`].
- [`RenderTemplateCapableTrait`] - Standardizes the process of rendering a
template. Intended to complement [`RenderCapableTemplateBlockTrait`].
- [`RendererAwareTrait`] - Internal functionality for storing and retrieving a
renderer. Does minimal validation. Can be used to back up
[`RendererAwareInterface`].
- [`TemplateAwareTrait`] - Internal functionality for storing and retrieving a
template. Does minimal validation. Can be used to back up
[`TemplateAwareInterface`].
- [`CaptureOutputCapableTrait`] - Functionality for capturing the output of a callback.


[Dhii]:                                         https://github.com/Dhii/dhii
[`dhii/output-renderer-interface`]:             https://github.com/Dhii/output-renderer-interface
[`dhii/output-renderer-base`]:                  https://github.com/Dhii/output-renderer-base

[`StringableRenderCatcherTrait`]:       src/StringableRenderCatcherTrait.php
[`RenderCapableTemplateBlockTrait`]:    src/RenderCapableTemplateBlockTrait.php
[`BlockAwareTrait`]:                    src/BlockAwareTrait.php
[`ContextAwareTrait`]:                  src/ContextAwareTrait.php
[`RenderTemplateCapableTrait`]:         src/RenderTemplateCapableTrait.php
[`RendererAwareTrait`]:                 src/RendererAwareTrait.php
[`TemplateAwareTrait`]:                 src/TemplateAwareTrait.php
[`CaptureOutputCapableTrait`]:          src/CaptureOutputCapableTrait.php

[`BlockInterface`]:                 https://github.com/Dhii/output-renderer-interface/blob/v0.2/src/BlockInterface.php
[`TemplateInterface`]:              https://github.com/Dhii/output-renderer-interface/blob/v0.2/src/TemplateInterface.php
[`BlockAwareInterface`]:            https://github.com/Dhii/output-renderer-interface/blob/v0.2/src/BlockAwareInterface.php
[`ContextAwareInterface`]:          https://github.com/Dhii/output-renderer-interface/blob/v0.2/src/ContextAwareInterface.php
[`RendererAwareInterface`]:         https://github.com/Dhii/output-renderer-interface/blob/v0.2/src/RendererAwareInterface.php
[`TemplateAwareInterface`]:         https://github.com/Dhii/output-renderer-interface/blob/v0.2/src/TemplateAwareInterface.php

[`ContainerInterface`]:             https://github.com/php-fig/container/blob/1.0.0/src/ContainerInterface.php
