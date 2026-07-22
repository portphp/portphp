# Upgrade from 1.x to 2.0

## PHP

- Minimum PHP version is now **8.2** (`^8.2`). PHP 7.4–8.1 are no longer supported.

## Symfony components

- `symfony/property-access` and `symfony/validator` now require:
  `^5.4 || ^6.0 || ^7.0 || ^8.0`
- Symfony 4.x is no longer supported.
- Symfony 6.x is supported again (it was briefly missing on `master` before this release).

## Other

- CI runs on PHP 8.2–8.5 via GitHub Actions.
- Scrutinizer configuration was removed; use GitHub Actions status instead.
