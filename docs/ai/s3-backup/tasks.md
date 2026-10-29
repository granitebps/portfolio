# Separate private backup storage

Source: User-approved scope: add the `s3-backup` Laravel disk, separate credentials, backup destination and monitoring, weekly health threshold, environment example, and setup documentation.

## Execution guard

Keep the existing public `s3` disk, backup contents, retention, weekly schedule, PHP version, and actual `.env` unchanged. Do not contact production services, create Cloudflare resources, migrate/delete objects, commit, push, or change gallery uploads. Stop if implementation requires materially wider scope. Execute inline; this is a small configuration change. The reviewed configuration change is reversible and requires no additional design gate.

- [x] T001 — Configure and document the private backup disk
  - Depends on: None
  - Parallel: No
  - Scope: config/filesystems.php, config/backup.php, .env.example, readme.md, this record.
  - Implementation: Add an S3 disk named `s3-backup`, using independent BACKUP_AWS_* settings, R2 region auto, private visibility and throwing writes. Route Spatie destinations and monitoring to it. Set maximum age to eight days for weekly backups plus one day's grace. Document private bucket setup, scoped credentials, config refresh, and manual live verification.
  - Verify: PHP syntax; construct the Laravel S3 disk and Spatie backup/monitor destinations with dummy credentials without network requests; check public disk preservation, private credentials and routing; run the existing isolated API suite; inspect the final diff. A new automated test duplicating these configuration values is not needed. Live privacy and backup/restore checks require the configured R2 environment and remain explicitly unverified.
  - Evidence: Both PHP config files pass syntax checks. Fourteen isolated runtime checks pass, including Laravel S3 disk construction with dummy credentials, endpoint and bucket, Spatie backup and monitor destination construction, eight-day threshold, and unchanged public disk, backup source and retention. Existing API suite: 54 passed, 245 assertions. Diff and whitespace review pass. README covers bucket privacy, scoped credentials, private environment source, configuration cache and intentional live verification. No R2 operations were executed; live privacy and backup/restore remain unverified. Generated test caches were moved to macOS Trash.
