# API for Granite Bagas Portfolio Website

<h1 align="center">
  <br>
  <a href="http://granitebps.com"><img src="https://s3.ap-southeast-1.amazonaws.com/portfolio-granitebps.com/galeries/1600601933_gbps.png" width="100" /></a>
  <br>
  Granite Bagas
  <br>
</h1>

[![Actions Status](https://github.com/granitebps/portfolio/workflows/CI/badge.svg)](https://github.com/granitebps/portfolio/actions)
[![Actions Status](https://github.com/granitebps/portfolio/workflows/CD/badge.svg)](https://github.com/granitebps/portfolio/actions)
![License](https://img.shields.io/github/license/granitebps/portfolio)

## Link

-   [Website](https://granitebps.com)
-   [API](https://api.granitebps.com)

## Runtime and dependencies

This API uses Laravel 13 and PHP 8.3. The lockfile was updated to Laravel 13.39.0 on October 29, 2026, following the [official Laravel upgrade guide](https://laravel.com/docs/13.x/upgrade).

Composer targets PHP 8.3.0 through `config.platform.php`, so dependency updates remain compatible with the server's PHP 8.3 branch even when run on a newer local PHP version. Stable package releases are required. Install the committed lockfile on servers; run dependency updates locally.

With Herd, confirm the site's PHP executable before installing dependencies:

```sh
herd which-php
herd php -v
herd composer install
herd composer check-platform-reqs
```

The site should use PHP 8.3. Herd and server PHP versions were left unchanged. See [Herd's PHP version documentation](https://herd.laravel.com/docs/macos/technology/php-versions).

## Verification

Run checks with isolated test settings, especially when `.env` contains production service addresses. The existing PHPUnit configuration uses SQLite in memory and separate test cache files. These overrides also keep static analysis away from production database, cache, logging, and mail services:

```sh
(
    export APP_ENV=testing APP_DEBUG=false LOG_CHANNEL=stderr
    export DB_CONNECTION=sqlite DB_DATABASE=:memory:
    export CACHE_DRIVER=array SESSION_DRIVER=array QUEUE_CONNECTION=sync
    export MAIL_DRIVER=array FILESYSTEM_DRIVER=public SENTRY_LARAVEL_DSN=null
    export APP_CONFIG_CACHE=bootstrap/cache/config.phpunit.php
    export APP_SERVICES_CACHE=bootstrap/cache/services.phpunit.php
    export APP_PACKAGES_CACHE=bootstrap/cache/packages.phpunit.php
    export APP_ROUTES_CACHE=bootstrap/cache/routes.phpunit.php
    export APP_EVENTS_CACHE=bootstrap/cache/events.phpunit.php

    herd php vendor/bin/pest
    herd php vendor/bin/phpstan analyse --memory-limit=512M
)

herd composer validate --strict
herd composer audit --locked
```

After this update, the existing suite passes all 54 tests (245 assertions). Larastan still reports the same 108 pre-existing errors as before the update; there are no new diagnostics. The default local 128 MB memory limit is insufficient for this analysis, hence the command-specific 512 MB limit.

## Deployment prerequisites

Keep both command-line PHP and PHP-FPM on PHP 8.3, and check the [Laravel server requirements](https://laravel.com/docs/13.x/deployment#server-requirements). This application's image processing also needs GD, backups need ZIP, and Horizon needs PCNTL and POSIX. Install the database extension for the configured database.

During deployment, run `composer install --no-dev --prefer-dist --optimize-autoloader` using PHP 8.3, followed by `composer check-platform-reqs --no-dev` to validate the actual server extensions rather than the emulated Composer platform. Reload PHP-FPM and restart long-running Horizon workers after the release. Existing deployment scripts still target PHP 8.3.

The update was verified locally with SQLite and isolated service settings. Production MySQL, Redis, object storage, mail, and backup integrations require environment-specific verification; no deployment was performed.
