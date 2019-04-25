# Dhii - Output - Renderer - Interface
[![Build Status](https://travis-ci.org/Dhii/output-renderer-interface.svg?branch=master)](https://travis-ci.org/Dhii/output-renderer-interface)
[![Code Climate](https://codeclimate.com/github/Dhii/output-renderer-interface/badges/gpa.svg)](https://codeclimate.com/github/Dhii/output-renderer-interface)
[![Test Coverage](https://codeclimate.com/github/Dhii/output-renderer-interface/badges/coverage.svg)](https://codeclimate.com/github/Dhii/output-renderer-interface/coverage)
[![Latest Stable Version](https://poser.pugx.org/dhii/output-renderer-interface/version)](https://packagist.org/packages/dhii/output-renderer-interface)
[![This package complies with Dhii standards](https://img.shields.io/badge/Dhii-Compliant-green.svg?style=flat-square)][Dhii]

Interfaces for rendering interoperability.

## Details
Like other members of the `Dhii\Output` namespace, interfaces in this package
are related to producing output, handling related errors, and providing
convenience around output functionality. Particularly, interfaces in this
package are at the core of output generation, defining a standard API for
anything that can render output.

Therefore, output renderers MUST implement `RendererInterface`. If
`RendererInterface#render()` is unable to produce output,
a `CouldNotRenderExceptionInterface` MUST be thrown.

### Interfaces
- [`RendererInterface`] - Represents a renderer, i.e. something that can produce output.
- [`TemplateInterface`] - A renderer that uses context to render.
- [`TemplateFactoryInterface`] - A factory of templates.
- [`BlockInterface`] - A renderer that has access to the render context, and is also [stringable].
- [`BlockFactoryInterface`] - A factory of blocks.
- [`RendererAwareInterface`] - Something that exposes a renderer.
- [`ContextAwareInterface`] - Something that can have a rendering context retrieved.
- [`BlockAwareInterface`] - Something that can have a block retrieved.
- [`TemplateAwareInterface`] - Something that can have a template retrieved.
- [`RendererExceptionInterface`] - An exception that occurs in relation to a renderer, and is aware of it.
- [`CouldNotRenderExceptionInterface`] - A specialized renderer exception that signals problems during rendering.
- [`TemplateRenderExceptionInterface`] - A specialized "could-not-render" exception that is aware
of the rendering context.



[Dhii]:                                 https://github.com/Dhii/dhii
[stringable]:                           https://github.com/Dhii/stringable-interface

[`RendererInterface`]:                  src/RendererInterface.php
[`TemplateInterface`]:                  src/TemplateInterface.php
[`TemplateFactoryInterface`]:           src/TemplateFactoryInterface.php
[`BlockInterface`]:                     src/BlockInterface.php
[`BlockFactoryInterface`]:              src/BlockFactoryInterface.php
[`RendererAwareInterface`]:             src/RendererAwareInterface.php
[`ContextAwareInterface`]:              src/ContextAwareInterface.php
[`BlockAwareInterface`]:                src/BlockAwareInterface.php
[`TemplateAwareInterface`]:             src/TemplateAwareInterface.php
[`RendererExceptionInterface`]:         src/Exception/RendererExceptionInterface.php
[`CouldNotRenderExceptionInterface`]:   src/Exception/CouldNotRenderExceptionInterface.php
[`TemplateRenderExceptionInterface`]:   src/Exception/ContextRenderExceptionInterface.php
