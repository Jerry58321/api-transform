# API Transform

[![Tests](https://github.com/Jerry58321/api-transform/actions/workflows/tests.yml/badge.svg)](https://github.com/Jerry58321/api-transform/actions/workflows/tests.yml)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/jerry58321/api-transform.svg)](https://packagist.org/packages/jerry58321/api-transform)
[![Total Downloads](https://img.shields.io/packagist/dt/jerry58321/api-transform.svg)](https://packagist.org/packages/jerry58321/api-transform)
[![License](https://img.shields.io/packagist/l/jerry58321/api-transform.svg)](LICENSE)

API Transform is a Laravel package for building reusable API response
transformers. It helps keep API output definitions close to the feature or
model they represent, while allowing transforms to quote and compose one
another.

## Features

- Define response schemas with small transform classes.
- Reuse model transforms inside feature transforms.
- Return consistent JSON responses from controllers.
- Support paginated resources and response metadata.
- Auto-discover the Laravel service provider through Composer.

## Requirements

- PHP 8.1 or later
- Laravel components 7.x, 8.x, 9.x, 10.x, 11.x, or 12.x

## Installation

```bash
composer require jerry58321/api-transform
```

Publish the package configuration when you need to customize the generated
paths:

```bash
php artisan vendor:publish --provider="jerry58321\ApiTransform\TransformServiceProvider"
```

## Concept

Transforms are usually grouped into two categories:

- `Models`: describe existing table or model schemas and can be reused by other
  transforms.
- `Features`: describe the response shape of each API feature and can compose
  model transforms or other feature transforms.

This keeps controller responses explicit while reducing duplicated response
mapping code.

## Basic Usage

```php
use App\Models\LoginLog;
use App\Transforms\Models\LoginLogTransform;

class IndexController
{
    public function index()
    {
        $loginLog = LoginLog::with('user')->get();

        return LoginLogTransform::response(compact('loginLog'));
    }
}
```

```php
use jerry58321\ApiTransform\Resources;
use jerry58321\ApiTransform\Transform;

class UserTransform extends Transform
{
    public function methodOutputKey(): array
    {
        return [
            'user' => false,
        ];
    }

    public function __user(Resources $resource): array
    {
        return [
            'account' => $resource->account,
            'name' => $resource->name,
        ];
    }
}
```

```php
use jerry58321\ApiTransform\Resources;
use jerry58321\ApiTransform\Transform;

class LoginLogTransform extends Transform
{
    public function methodOutputKey(): array
    {
        return [
            'loginLog' => 'login_log',
        ];
    }

    public function __loginLog(Resources $resources): array
    {
        $user = UserTransform::quote(['user' => $resources->user]);

        return array_merge($user, [
            'ip' => $resources->ip,
            'login_at' => $resources->login_at,
        ]);
    }
}
```

## Testing

```bash
composer install
composer test
```

## Version Support

The current release line supports PHP 8.1+ and Laravel components 7.x through
12.x. Compatibility is tested against the maintained Laravel generations in
GitHub Actions.

## Security

If you discover a security issue, please do not open a public issue with
exploit details. Email the maintainer or open a minimal GitHub issue asking for
a private coordination channel. See [SECURITY.md](SECURITY.md) for the current
support policy.

## Contributing

Issues, compatibility reports, documentation fixes, and pull requests are
welcome. See [CONTRIBUTING.md](CONTRIBUTING.md) for the local setup and pull
request checklist.

## Changelog

Release notes are tracked in [CHANGELOG.md](CHANGELOG.md).

## Maintenance Roadmap

- Keep the package test suite running on supported PHP versions.
- Review Laravel compatibility as new framework versions are released.
- Improve examples for common response patterns such as pagination and metadata.
- Use Codex to assist with issue triage, pull request review, and release
  preparation.

## License

API Transform is open-sourced software licensed under the MIT license.
