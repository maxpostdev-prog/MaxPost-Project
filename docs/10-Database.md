# MaxPost Data Model

**Document:** `10-Database.md`  
**Version:** 1.0 Alpha  
**Status:** Living Specification

## Purpose

This document defines how MaxPost stores permanent platform data. WordPress remains the primary content store during the initial architecture phase.

## Principles

1. Use native WordPress storage when practical.
2. Avoid custom tables until query volume or data shape justifies them.
3. Keep canonical field names stable.
4. Never couple permanent data to the active theme.
5. All schema changes require migrations.

## WordPress entities

### Custom Post Types

```text
software
software_update
guide
promotion
```

### Taxonomies

```text
software_category
operating_system
software_language
software_type
```

## Canonical software meta

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

## Field types

| Field | Storage format |
|---|---|
| Version | string |
| File size | string or normalized bytes in future |
| Download URL | sanitized URL |
| Attachment references | integer IDs |
| Screenshot collection | array of unique integer IDs |
| Dates | UTC ISO 8601 or WordPress GMT date |
| Featured | boolean represented consistently |
| Related software | array of post IDs |

## Media integrity

Attachment IDs must be validated with WordPress APIs.

Requirements:

- `absint()` every ID
- remove zero values
- remove duplicates
- verify `wp_attachment_is_image()` for visual media
- tolerate deleted attachments without fatal errors

## Relationships

Use taxonomy terms for broad classification and post IDs for explicit relationships.

Examples:

- Software → Category: taxonomy
- Software → Supported OS: taxonomy
- Software → Related software: post ID list
- Update → Software: explicit post ID
- Promotion → Target software/category: post and term references

## Custom tables

Custom tables are not required for MVP content.

They may be introduced later for high-volume append-only data:

```text
wp_maxpost_events
wp_maxpost_downloads
wp_maxpost_releases
```

A custom table requires:

- written justification
- indexed query plan
- activation migration
- upgrade migration
- uninstall policy
- privacy retention policy

## Suggested event table

```sql
CREATE TABLE wp_maxpost_events (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    event_name VARCHAR(64) NOT NULL,
    software_id BIGINT UNSIGNED NULL,
    campaign_id VARCHAR(100) NULL,
    language VARCHAR(10) NULL,
    created_at DATETIME NOT NULL,
    metadata_json LONGTEXT NULL,
    PRIMARY KEY (id),
    KEY event_name_created (event_name, created_at),
    KEY software_created (software_id, created_at)
);
```

Do not store unnecessary personal identifiers.

## Migrations

Migration identifiers:

```text
001_initial_schema
002_add_card_image
003_add_event_storage
```

Each migration must be:

- idempotent
- ordered
- logged
- testable
- safe to rerun

Store the installed schema version in one WordPress option.

## Deletion policy

Deactivating the plugin never deletes content.

Uninstalling does not delete data unless the administrator explicitly enabled permanent removal.

## Backup and portability

All core content must be exportable through standard WordPress tools or a documented MaxPost export format.

No essential product data may exist only in transient cache entries.

## Indexing rules

Before adding indexes:

1. Identify a real query.
2. Measure current performance.
3. Verify selectivity.
4. Document write-cost impact.

## Data integrity checklist

- [ ] Canonical keys used everywhere
- [ ] Attachment IDs validated
- [ ] Dates stored consistently
- [ ] No theme-owned permanent data
- [ ] Migrations are idempotent
- [ ] Deactivation preserves data
- [ ] Export path documented
