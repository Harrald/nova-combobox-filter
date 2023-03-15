# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.7] - 2023-03-15

### Fixed
- resetting all the nova filters broke this component 

### Changed
- switched from yarn to npm

## [1.0.6] - 2022-05-09

### Changed
- Changed readme img urls from relative to absolute

## [1.0.5] - 2022-05-09

### Changed
- Require `laravel/nova` from `require` instead of `require-dev`

## [1.0.4] - 2022-04-29

### Changed
- Use headlessui 1.6 instead of the insides build
- Improved usage of filter and improved readme.md

## [1.0.3] - 2022-04-29

### Fixed
- Make Nova 'reset filters' work
- Use correct 'null' value for empty state (filters were incorrectly being displayed als having a selected value)

## [1.0.2] - 2022-04-11

### Added
- Add `license.md`
- Add this `changelog.md`
- Add packagist shields to `readme.md`
- Denote Laravel Nova dependency in `composer.json`

### Removed
- Removed unused code

## [1.0.1] - 2022-04-11

### Changed
- Make checkmark svg different color then background

## [1.0.0] - 2022-04-11

Initial release.
Combobox/multiselect filter for Nova 4. Based on [Headless UI Combobox component](https://headlessui.dev/vue/combobox)
