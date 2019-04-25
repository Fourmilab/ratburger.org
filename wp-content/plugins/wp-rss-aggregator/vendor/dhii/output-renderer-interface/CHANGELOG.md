# Change log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [[*next-version*]] - YYYY-MM-DD

## [0.3-alpha2] - 2018-04-05
### Added
- `TemplateFactoryInterface`.
- `BlockFactoryInterface`.

## [0.3-alpha1] - 2018-04-05
### Changed
- Now also allowing `ArrayAccess` and `stdClass` types as contexts.

## [0.2] - 2017-10-04
Some re-ordering and re-naming mostly.

### Added
- `TemplateAwareInterface`, which can return the new `TemplateInterface`.

### Changed
- `ContextRendererInterface` is now `TemplateInterface`; corresponding exception
also updated from `ContextRenderExceptionInterface` to `TemplateRenderExceptionInterface`.

## [0.1] - 2017-09-08
Initial release.
