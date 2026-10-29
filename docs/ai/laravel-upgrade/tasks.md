# Laravel update on PHP 8.3

Source: User-approved Laravel and compatible dependency update; retain PHP 8.3 locally and on the server.

## Execution guard

Preserve API behavior and existing application structure. Do not change PHP versions, deploy, commit, or access production services. Stop if completion requires a material scope expansion. Execute inline because dependency selection and compatibility diagnosis require controller judgment.

- [x] T001 — Establish the locked-dependency baseline
  - Depends on: None
  - Parallel: No
  - Scope: Local dependency installation and isolated verification.
  - Implementation: Install the existing lock on PHP 8.3; run checks with SQLite memory database, array cache/session/mail, local storage, and external telemetry disabled.
  - Verify: Record application boot, existing API test suite, and Larastan results before updates.
  - Evidence: PHP 8.3.31, Laravel 13.12.0 boots and routes resolve; 54 tests pass (245 assertions). Larastan reports 108 pre-existing errors with a 512 MB command memory limit; default 128 MB exhausts memory.
- [x] T002 — Update compatible dependencies and resolve upgrade regressions
  - Depends on: T001
  - Parallel: No
  - Scope: composer.json, composer.lock, and application/configuration/tests only as needed for upgrade compatibility.
  - Implementation: Follow Laravel 13 official upgrade guidance; resolve current stable Laravel 13 dependencies on PHP 8.3 without unrelated package major migrations or application restructuring.
  - Verify: Composer validation, platform requirements, security audit, application route discovery, existing API tests, and Larastan; compare failures with baseline.
  - Evidence: Laravel 13.39.0 installed on PHP 8.3.31; 105 packages updated or added. Composer strict validation and actual platform checks pass; audit reports no advisories. All 54 tests pass (245 assertions). Larastan retains exactly the same 108 diagnostics. All 81 routes remain; the sole route-list difference is Laravel adding QUERY to the existing catch-all route's supported HTTP methods. No application compatibility edits were necessary. Existing cache class allowlist and PHP session serialization remain compatible with the official upgrade guide.
- [x] T003 — Document and review the verified update
  - Depends on: T002
  - Parallel: No
  - Scope: readme.md and this execution record.
  - Implementation: Document PHP 8.3 requirements, Herd commands, safe test setup, and deployment prerequisites without running deployment scripts.
  - Verify: Inspect complete diff for approved scope and report actual checks and remaining limitations.
  - Evidence: README documents the PHP 8.3 Composer target, Herd commands, isolated verification, and deployment requirements with official references. Production dependency installation dry-run succeeds; all locked versions are stable. Final diff contains only composer.json, composer.lock, readme.md, and this record; whitespace checks pass. Generated test cache files were moved to macOS Trash. Herd still selects php83; no PHP version, application code, deployment scripts, or production services were changed. MySQL/Redis/storage/mail/backup integrations remain unverified in their deployment environment.
