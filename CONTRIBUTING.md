# Contributing

Thanks for helping improve API Transform.

## Local Setup

```bash
composer install
composer test
```

To test compatibility with a specific Laravel generation, update dependencies
with the matching Orchestra Testbench version:

```bash
composer update --with "orchestra/testbench:^8.0" --prefer-dist --no-interaction
composer test
```

Common compatibility targets:

| Laravel | Orchestra Testbench |
| --- | --- |
| 10.x | ^8.0 |
| 11.x | ^9.0 |
| 12.x | ^10.0 |

## Pull Request Checklist

- Add or update tests for behavior changes.
- Keep public API changes backwards compatible when possible.
- Update README examples when usage changes.
- Run `composer validate --strict`.
- Run `composer test`.

## Issue Reports

When reporting a bug, include:

- Package version.
- PHP version.
- Laravel version.
- Minimal transform class or response payload that reproduces the issue.
- Expected and actual output.

## Release Notes

User-facing changes should be reflected in `CHANGELOG.md` before a tagged
release.
