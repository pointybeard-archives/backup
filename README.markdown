# Backup

- Version: 0.1alpha
- Date: 2nd May 2015
- [Github repository](https://github.com/pointybeard/backup/releases/tag/1.0alpha)

## Overview

Create archives of files & MYSQL databases suitable for backing up

## Server requirements

- PHP 5.6.6 or above
- Composer installed

## Installation

Out of the box, `dist/backup.phar` can be run.

To build your own phar from source, use `php -f build.php`. Be sure to run `composer install` from inside the `src/` folder first.

## Configuration

Look at `.backup_config.sample` for an example config.

## Usage

Use `dist/backup.phar` with command `php -f dist/backup.phar -- run --help`



## TODO

- The `prune` command has not been implemented yet.