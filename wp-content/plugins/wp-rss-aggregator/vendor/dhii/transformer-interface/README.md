# Dhii - Transformer Interface

[![Build Status](https://travis-ci.org/Dhii/transformer-interface.svg?branch=develop)](https://travis-ci.org/Dhii/transformer-interface)
[![Code Climate](https://codeclimate.com/github/Dhii/transformer-interface/badges/gpa.svg)](https://codeclimate.com/github/Dhii/transformer-interface)
[![Test Coverage](https://codeclimate.com/github/Dhii/transformer-interface/badges/coverage.svg)](https://codeclimate.com/github/Dhii/transformer-interface/coverage)
[![Latest Stable Version](https://poser.pugx.org/dhii/transformer-interface/version)](https://packagist.org/packages/dhii/transformer-interface)
[![Latest Unstable Version](https://poser.pugx.org/dhii/transformer-interface/v/unstable)](https://packagist.org/packages/dhii/transformer-interface)
[![This package complies with Dhii standards](https://img.shields.io/badge/Dhii-Compliant-green.svg?style=flat-square)][Dhii]

## Details
Interfaces for data transformation

### Interfaces
- [`TransformerInterface`] - Something that can transform a value to another value.
- [`TransformerFactoryInterface`] - Something that can create a transformer.
- [`TransformerAwareInterface`] - Something that exposes a transformer.
- [`TransformerExceptionInterface`] - Represents a problem related to a transformer.
- [`CouldNotTransformExceptionInterface`] - Represents a problem with the transformation process.


[Dhii]:                                                         https://github.com/Dhii/dhii

[`TransformerInterface`]:                                       src/TransformerInterface.php
[`TransformerFactoryInterface`]:                                src/TransformerFactoryInterface.php
[`TransformerAwareInterface`]:                                  src/TransformerAwareInterface.php
[`TransformerExceptionInterface`]:                              src/Exception/TransformerExceptionInterface.php
[`CouldNotTransformExceptionInterface`]:                        src/Exception/CouldNotTransformExceptionInterface.php
