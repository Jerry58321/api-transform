# Security Policy

## Supported Versions

Security fixes are prioritized for the latest tagged release line.

| Version | Supported |
| --- | --- |
| 3.x | Yes |
| 2.x | Best effort |
| 1.x | No |

## Reporting a Vulnerability

Please do not open a public issue with exploit details.

To report a vulnerability, email the maintainer associated with this package or
open a minimal GitHub issue asking for a private coordination channel. Include:

- Affected package version.
- Laravel and PHP versions.
- A short description of the impact.
- Reproduction steps or a proof of concept, if available.

The maintainer will acknowledge confirmed reports as soon as possible, assess
the supported version range, and publish a fix or mitigation note when
appropriate.

## Scope

API Transform is a response transformation helper. Security review focuses on:

- Unsafe response data exposure.
- Unexpected transform composition behavior.
- Dependency compatibility issues that may affect supported Laravel versions.
- Release regressions that change API response output unexpectedly.
