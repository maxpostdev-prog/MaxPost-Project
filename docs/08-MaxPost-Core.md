# MaxPost Core Plugin Specification

**Repository:** `maxpost-core`  
**Version:** 1.0 Alpha

## Responsibility

`maxpost-core` owns permanent platform data and business logic independently of the active WordPress theme.

It is responsible for:

- custom post types;
- taxonomies;
- canonical metadata;
- WordPress administration interfaces;
- media selection and validation;
- REST API;
- software updates and What's New content;
- promotions and recommendation targeting;
- caching;
- migrations;
- capabilities and security;
- future download analytics and licensing.

## Proposed structure

```text
maxpost-core/
├── maxpost-core.php
├── uninstall.php
├── README.md
├── CHANGELOG.md
├── includes/
│   ├── class-plugin.php
│   ├── class-activator.php
│   ├── class-deactivator.php
│   ├── class-capabilities.php
│   ├── class-post-types.php
│   ├── class-taxonomies.php
│   ├── class-meta.php
│   ├── class-media.php
│   ├── class-cache.php
│   ├── class-migrations.php
│   └── helpers.php
├── admin/
│   ├── class-admin.php
│   ├── class-meta-boxes.php
│   ├── class-settings.php
│   ├── class-columns.php
│   ├── assets/
│   │   ├── admin.css
│   │   └── media-admin.js
│   └── views/
├── api/
│   ├── class-software-controller.php
│   ├── class-updates-controller.php
│   ├── class-promotions-controller.php
│   ├── class-config-controller.php
│   └── class-events-controller.php
├── languages/
└── tests/
```

## Content model

### Custom post types

Required for the first full platform release:

- `software`;
- `software_update`;
- `guide`;
- `promotion`.

### Taxonomies

- `software_category`;
- `operating_system`;
- `software_language`;
- `software_type`.

Future optional taxonomies include `license_type` and `software_tag`.

## Canonical software metadata

```text
_maxpost_version
_maxpost_file_size
_maxpost_download_url
_maxpost_portable_download_url
_maxpost_icon_id
_maxpost_card_image_id
_maxpost_screenshot_ids
_maxpost_supported_windows
_maxpost_supported_languages
_maxpost_system_requirements
_maxpost_release_date
_maxpost_updated_date
_maxpost_changelog
_maxpost_sha256
_maxpost_featured
_maxpost_free_or_paid
_maxpost_status
_maxpost_related_software_ids
```

These keys are stable contracts. Themes should normally consume them through helper functions rather than repeat direct `get_post_meta()` calls.

## Media model

- Icon: one validated WordPress attachment ID.
- Card image: one validated image attachment ID.
- Screenshots: ordered array of unique validated image attachment IDs.

Saving requirements:

1. verify nonce;
2. verify capability;
3. ignore autosaves and revisions;
4. normalize with `absint`;
5. remove zero and duplicate values;
6. verify `wp_attachment_is_image()`;
7. update or delete metadata consistently;
8. preserve valid existing values when unrelated fields are saved.

## Administration interface

The Software editor provides:

- version;
- file size;
- download and portable URLs;
- Windows compatibility;
- languages;
- icon;
- card image;
- screenshot gallery;
- release and updated dates;
- SHA-256;
- featured flag;
- status;
- related software.

Media controls use the WordPress Media Library, store attachment IDs and show removable previews.

## Public helper API

Stable theme helpers:

```php
maxpost_get_software( int $post_id ): array
maxpost_get_featured_software( array $args = [] ): array
maxpost_get_related_software( int $post_id, int $limit = 3 ): array
maxpost_get_latest_update(): ?array
maxpost_get_software_categories(): array
maxpost_get_software_image_id( int $post_id, string $context ): int
```

Breaking changes require a major version increase.

## REST API

Namespace:

```text
maxpost/v1
```

Required endpoints:

```text
GET  /software
GET  /software/{slug}
GET  /software/featured
GET  /categories
GET  /updates
GET  /promotions
GET  /app-config
POST /events
```

Public responses contain no administrative or secret data.

API rules:

- stable field names;
- explicit schemas;
- language parameter support;
- enabled-state and date-window filtering;
- predictable WordPress error objects;
- cache headers where suitable;
- server-side cache with deterministic invalidation;
- rate limiting for write endpoints.

## Desktop app configuration

`app-config` may provide:

- API version;
- latest and minimum supported application versions;
- update page URL;
- one What's New item;
- recommended software;
- optional promotion;
- feature flags;
- request timeout;
- cache lifetime.

Remote configuration must never contain executable code, arbitrary HTML or secrets.

## Promotions

Promotion fields:

```text
title
description
image_id
target_url
button_text
campaign_type
placement
language
start_date
end_date
enabled
priority
target_software
target_category
tracking_id
```

Allowed campaign types:

- internal recommendation;
- announcement;
- donation;
- sponsor;
- affiliate.

Sponsor and affiliate content must be clearly labelled. Applications open target URLs only after an explicit user click.

## Caching

Cache software lists, featured items, categories, updates, promotions and app configuration.

Cache keys include relevant language, filters and API version. Content saves invalidate only affected groups where practical.

Never flush the entire object cache from normal plugin operations.

## Activation and migrations

Activation sequence:

1. register content types;
2. add capabilities;
3. create default options;
4. run numbered idempotent migrations;
5. flush rewrite rules once.

Example migrations:

```text
001_initial_schema
002_add_card_image
003_add_event_storage
```

Do not flush rewrite rules on ordinary requests.

## Deactivation and uninstall

Deactivation preserves content and settings.

Uninstall does not delete user data by default. Permanent deletion requires an explicit administrator option and a clear warning.

## Security

Required:

- nonces for privileged writes;
- capability checks;
- sanitization before storage;
- contextual escaping at output boundaries;
- REST permission callbacks;
- strict URL validation;
- MIME and file-type validation;
- no executable uploads;
- no sensitive fields in public API responses;
- rate limiting and abuse protection for event ingestion.

## Testing

Minimum automated or repeatable coverage:

- post type and taxonomy registration;
- metadata validation;
- repeated-save behavior;
- image attachment validation;
- REST schemas;
- date and language filtering;
- cache invalidation;
- capability checks;
- activation migrations;
- plugin operation with a different theme.

## Release package

The ZIP contains one root folder:

```text
maxpost-core/
```

Every release requires:

- semantic version update;
- changelog;
- PHP syntax validation;
- automated tests;
- REST schema review;
- migration review;
- security review;
- clean installation and upgrade tests.