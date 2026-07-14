# MaxPost REST API

**Document:** `09-REST-API.md`  
**Version:** 1.0 Alpha  
**Status:** Living Specification

## Purpose

The MaxPost REST API connects the WordPress platform, desktop applications and future clients. The API must remain predictable, cacheable and backward compatible.

## Namespace

```text
/wp-json/maxpost/v1
```

## Core endpoints

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

## Response envelope

Successful collection response:

```json
{
  "api_version": "1",
  "data": [],
  "meta": {
    "page": 1,
    "per_page": 20,
    "total": 0,
    "total_pages": 0
  }
}
```

Error response:

```json
{
  "code": "maxpost_not_found",
  "message": "The requested software was not found.",
  "data": {
    "status": 404
  }
}
```

## Software schema

```json
{
  "id": 21,
  "slug": "folder-creator",
  "name": "MP Folder Creator",
  "description": "Create hundreds of folders using templates.",
  "version": "1.0.0",
  "file_size": "8.4 MB",
  "download_url": "https://maxpost.dev/downloads/mp-folder-creator.exe",
  "page_url": "https://maxpost.dev/software/folder-creator/",
  "icon_url": "https://maxpost.dev/uploads/folder-creator-icon.png",
  "card_image_url": "https://maxpost.dev/uploads/folder-creator-card.webp",
  "screenshots": [],
  "category": "files",
  "operating_systems": ["Windows 10", "Windows 11"],
  "languages": ["en", "uk", "ru", "es", "pt"],
  "featured": true,
  "updated_at": "2026-07-14T00:00:00Z"
}
```

## Query parameters

Supported on collections:

- `lang`
- `category`
- `featured`
- `search`
- `page`
- `per_page`
- `order`
- `orderby`

Unknown parameters must be ignored or rejected consistently.

## Language

English is the fallback language. The API accepts ISO-like language codes:

```text
en, uk, ru, es, pt
```

Responses must never mix languages within one object unless explicitly documented.

## Caching

Public GET endpoints must support server-side caching.

Cache keys must include:

- API version
- language
- endpoint
- normalized filters
- pagination

Recommended headers:

```text
Cache-Control
ETag
Last-Modified
```

## Promotions

Promotion responses must respect:

- enabled state
- start date
- end date
- language
- placement
- priority
- target software
- target category

Sponsored or affiliate content must be clearly labeled.

## App configuration

`GET /app-config` may return:

- latest version
- minimum supported version
- update URL
- What’s New item
- recommended software
- feature flags
- promotion
- timeout
- cache lifetime

It must never expose secrets, credentials or private WordPress data.

## Event endpoint

`POST /events` is reserved for minimal anonymous product events such as:

- download click
- recommendation click
- update opened
- language selected

Requirements:

- no fingerprinting
- no raw IP storage unless required for security
- strict allowlist of event names
- payload size limit
- rate limiting
- origin validation where appropriate

## Versioning

Breaking changes require a new namespace:

```text
maxpost/v2
```

Existing v1 clients must continue to work during a documented migration period.

## Security

Every route requires an explicit `permission_callback`.

Public read routes may use:

```php
'permission_callback' => '__return_true'
```

Write routes require validation, rate limiting and narrowly scoped permissions.

## Validation checklist

- [ ] Stable field names
- [ ] Public data only
- [ ] Predictable HTTP status codes
- [ ] Language fallback tested
- [ ] Date filtering tested
- [ ] Cache invalidation tested
- [ ] No secrets in responses
- [ ] Schema documented before release
