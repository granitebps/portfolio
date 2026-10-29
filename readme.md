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

## Private R2 backups

Public media continues to use the existing `s3` disk and `AWS_*` settings. Backups, cleanup, and backup monitoring use the separate `s3-backup` disk and `BACKUP_AWS_*` settings. Supply dedicated backup credentials before deploying. Failed backup disk writes throw exceptions.

Before deploying this configuration:

1. Create a private R2 bucket, for example `s3-backup`. Leave its public development URL (`r2.dev`) disabled and do not attach a public custom domain. R2 bucket settings enforce privacy; Laravel's `visibility` setting does not implement object ACLs on R2. See [Cloudflare's public-access documentation](https://developers.cloudflare.com/r2/buckets/public-buckets/).
2. Create an R2 **Object Read & Write** token scoped only to that bucket. Backup retention cleanup needs object deletion permission as well as listing and writing. Keep the existing public-media token separate. See [R2 API tokens](https://developers.cloudflare.com/r2/api/tokens/).
3. Add the following settings to the deployment environment or its private environment-file source. Replace the example values with the new bucket's credentials and your account's S3 API endpoint. The bucket name can differ from the Laravel disk name.

   ```dotenv
   BACKUP_AWS_ACCESS_KEY_ID=<backup-access-key-id>
   BACKUP_AWS_SECRET_ACCESS_KEY=<backup-secret-access-key>
   BACKUP_AWS_DEFAULT_REGION=auto
   BACKUP_AWS_BUCKET=s3-backup
   BACKUP_AWS_ENDPOINT=https://<account-id>.r2.cloudflarestorage.com
   ```

4. Rebuild configuration with `php artisan config:cache` using PHP 8.3 after supplying these values. The existing Jenkins deployment obtains `.env` from its private source; update that source before deploying so it includes the backup settings. This repository's actual `.env` has not been modified.

The scheduled backup remains database-only, every Monday at 08:00 in the application's configured timezone. Retention settings are unchanged. Backup monitoring now checks `s3-backup` and allows eight days between successful backups, providing one day's grace beyond the weekly interval. `backup:monitor` is not currently scheduled by the application.

Verify the configured environment before relying on the new destination:

- Run `php artisan backup:run --only-db` and confirm a new archive appears only in the private bucket. This reads the configured database and can send backup notifications and the configured success heartbeat; run it intentionally in the target environment.
- Check that anonymous requests cannot download an object from the private bucket, while existing public media URLs still work.
- Download an archive with authorized credentials and restore it into a disposable database to verify recoverability.
- Run `php artisan backup:monitor` to check the new destination; it may send configured notifications.

Historical backups remain in their original bucket. Copy and verify any archives you want to retain before separately authorizing retirement of public copies and any cached copies. Adding the private destination does not protect existing public backup objects. See [Spatie's backup disk documentation](https://spatie.be/docs/laravel-backup/v10/installation-and-setup).
