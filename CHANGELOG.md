# CHANGELOG

## [2.0.0] - 2026-11-23

### Changed

* **[BC break]** Requires PHP >= 8.5

### Fixed

* Skip the `ReflectionProperty::setAccessible()` call on PHP >= 8.1 (no-op since 8.1, deprecated in 8.5).
