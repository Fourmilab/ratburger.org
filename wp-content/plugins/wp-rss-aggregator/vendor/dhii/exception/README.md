# Dhii - Exception

[![Build Status](https://travis-ci.org/Dhii/exception.svg?branch=develop)](https://travis-ci.org/Dhii/exception)
[![Code Climate](https://codeclimate.com/github/Dhii/exception/badges/gpa.svg)](https://codeclimate.com/github/Dhii/exception)
[![Test Coverage](https://codeclimate.com/github/Dhii/exception/badges/coverage.svg)](https://codeclimate.com/github/Dhii/exception/coverage)
[![Latest Stable Version](https://poser.pugx.org/dhii/exception/version)](https://packagist.org/packages/dhii/exception)
[![This package complies with Dhii standards](https://img.shields.io/badge/Dhii-Compliant-green.svg?style=flat-square)][Dhii]

Standards-compliant exception classes.

## Details
This package contains concrete implementations of classes that implement
interfaces in [`dhii/exception-interface`]. This provides developers with
ready-made, standards-compliant classes that can be safely instantiated and
`throw`n to signal the various errors. The concrete exceptions will usually
have a corresponding factory trait, and the factory methods of those traits
are the recommended way of creating new exception instances (after service
definition, of course).

Implementations in this package also have the following features aimed
to become more standards-compliant:

- A [stringable] is accepted everywhere, where a string can be passed.
- All parameters can be passed `null` to signal default value (which may be
not `null`).

Consumers, i.e. code that attempts to `catch`, should not depend on these
classes. Instead, consumers should depend on the interfaces of
[`dhii/exception-interface`][].


[Dhii]:                                             https://github.com/Dhii/dhii
[stringable]:                                       https://github.com/Dhii/stringable-interface

[`dhii/exception-interface`]:                       https://github.com/Dhii/exception-interface
