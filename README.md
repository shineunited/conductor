# shineunited/conductor

[![License](https://img.shields.io/packagist/l/shineunited/conductor)](https://github.com/shineunited/conductor/blob/main/LICENSE)
[![Latest Version](https://img.shields.io/packagist/v/shineunited/conductor?label=latest)](https://packagist.org/packages/shineunited/conductor/)
[![PHP Version](https://img.shields.io/packagist/dependency-v/shineunited/conductor/php?label=php)](https://www.php.net/releases/index.php)
[![Main Status](https://img.shields.io/github/workflow/status/shineunited/conductor/Build/main?label=main)](https://github.com/shineunited/conductor/actions/workflows/build.yml?query=branch%3Amain)
[![Release Status](https://img.shields.io/github/workflow/status/shineunited/conductor/Build/release?label=release)](https://github.com/shineunited/conductor/actions/workflows/build.yml?query=branch%3Arelease)
[![Develop Status](https://img.shields.io/github/workflow/status/shineunited/conductor/Build/develop?label=develop)](https://github.com/shineunited/conductor/actions/workflows/build.yml?query=branch%3Adevelop)

## Description

A framework for more complete installation management with composer. Provides mechanisms for validated configuration, installer management and static file generation. To be used by other composer plugins to build detailed installers for specific package types.


## Installation

to add conductor, the recommended method is via composer.
```sh
$ composer require shineunited/conductor
```


### Configuration

Installation configuration is managed by parameters defined in the 'extra' section of the project's composer.json file. Individual parameters can be defined with default values and validation rules to create normalized project build configurations.


### Installer Management

Conductor uses a simplified installer type that can be set to handle specific package types and provide installation paths based on configuration variables.


### Static File Generation

Using blueprints and generators static files can be defined that are generated during install/update to ensure those files are kept up-to-date with upstream changes.
