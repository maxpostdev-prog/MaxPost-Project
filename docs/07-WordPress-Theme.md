# MaxPost WordPress Theme Specification

**Repository:** `maxpost-theme`  
**Version:** 1.0 Alpha

## Responsibility

The theme owns presentation only:

- templates;
- layout;
- design tokens;
- CSS and front-end JavaScript;
- responsive behavior;
- accessibility of rendered pages;
- visual fallbacks.

Permanent data and business logic belong in `maxpost-core`.

## Prohibited responsibilities

The theme must not own:

- permanent custom post types or taxonomies;
- canonical metadata definitions;
- REST business logic;
- update services for desktop applications;
- promotion storage;
- analytics persistence;
- licensing;
- custom database tables;
- migrations.

Changing the active theme must not make MaxPost content disappear from the WordPress administration area.

## Proposed structure

```text
maxpost-theme/
├── style.css
├── functions.php
├── theme.json
├── screenshot.png
├── README.md
├── CHANGELOG.md
├── assets/
│   ├── css/
│   │   ├── tokens.css
│   │   ├── base.css
│   │   ├── components.css
│   │   ├── layouts.css
│   │   └── utilities.css
│   ├── js/
│   │   ├── navigation.js
│   │   ├── search.js
│   │   ├── gallery.js
│   │   └── filters.js
│   ├── icons/
│   └── images/
├── inc/
│   ├── setup.php
│   ├── assets.php
│   ├── template-tags.php
│   ├── accessibility.php
│   └── compatibility.php
├── template-parts/
│   ├── site-header.php
│   ├── site-footer.php
│   ├── hero.php
│   ├── software-card.php
│   ├── category-card.php
│   ├── update-card.php
│   ├── promotion-card.php
│   └── empty-state.php
├── front-page.php
├── archive-software.php
├── single-software.php
├── taxonomy-software_category.php
├── search.php
├── page.php
├── index.php
├── 404.php
└── languages/
```

## Plugin dependency

The theme integrates with `maxpost-core` through stable helper functions and WordPress APIs.

If the plugin is inactive, the theme must not crash. It should:

1. show an administrator notice;
2. hide software-dependent homepage sections;
3. preserve ordinary pages and posts;
4. guard optional calls with `function_exists()`.

Example:

```php
$featured = function_exists( 'maxpost_get_featured_software' )
    ? maxpost_get_featured_software()
    : [];
```

## Required templates

### Homepage

1. Header
2. Hero
3. Featured software
4. Categories
5. What's New
6. All software
7. Support
8. Footer

### Software archive

- heading and summary;
- search and filters;
- results grid/list;
- pagination or load more;
- empty state.

### Software page

- product hero;
- download action;
- metadata;
- gallery;
- overview;
- features;
- instructions;
- requirements;
- FAQ;
- changelog;
- related software;
- support.

## Image contract

The theme receives WordPress attachment IDs from `maxpost-core`.

Card-image priority:

1. dedicated card image;
2. first screenshot;
3. featured image;
4. branded local placeholder.

Never write an attachment ID directly into `src`. Use `wp_get_attachment_image()` or `wp_get_attachment_image_url()`.

## CSS architecture

All visual values use tokens with the `--mp-` prefix. Components use BEM-style classes.

```text
mp-software-card
mp-software-card__media
mp-software-card__body
mp-software-card__actions
mp-software-card--featured
```

Avoid inline styles generated from arbitrary metadata. Sanitize any value that must become a style or class.

## JavaScript

Use progressive enhancement and vanilla JavaScript for the MVP.

JavaScript may enhance:

- mobile navigation;
- instant search;
- filters;
- gallery and lightbox;
- dismissible notices.

The site must retain basic navigation and content access when JavaScript fails.

## Accessibility

Required:

- skip link;
- semantic landmarks;
- correct heading hierarchy;
- keyboard menu and search;
- visible focus;
- accessible lightbox;
- reduced-motion support;
- descriptive image alt text;
- minimum mobile target size of 44 × 44 px.

## Performance

- no page builders;
- no jQuery dependency unless unavoidable;
- load scripts only where needed;
- responsive WordPress images;
- native lazy loading below the fold;
- reserve image dimensions;
- locally host fonts or use the system stack;
- target Lighthouse performance score above 90 under realistic conditions.

## Security

The theme escapes all output and treats plugin data as untrusted at the rendering boundary.

Use the appropriate WordPress escaping function:

- `esc_html()`;
- `esc_attr()`;
- `esc_url()`;
- `wp_kses_post()` only for intentionally allowed rich content.

The theme should not process privileged writes.

## Localization

All user-visible strings use WordPress translation functions. Do not concatenate sentence fragments that become difficult to translate.

## Release package

The installation ZIP contains one root folder:

```text
maxpost-theme/
```

Each release requires:

- semantic version update in `style.css`;
- changelog update;
- PHP syntax validation;
- JavaScript syntax validation;
- responsive review;
- accessibility smoke test;
- installation test on a clean WordPress instance.