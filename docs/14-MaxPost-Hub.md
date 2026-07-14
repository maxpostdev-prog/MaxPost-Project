# MaxPost Hub

MaxPost Hub is the central control plane for all MaxPost desktop applications. It is delivered by `maxpost-core` through WordPress and provides remote configuration without requiring an application release.

## Responsibilities

- application update checks;
- What's New cards;
- recommendations for other MaxPost tools;
- notifications and maintenance messages;
- feature flags;
- aggregate event counters;
- localized interface messages.

## Public API

### Configuration

```http
GET /wp-json/maxpost/v1/hub?app=mp-folder-creator&version=1.0.0&channel=stable&locale=en-US
```

Response sections:

- `cards` — active recommendations, support cards and partner cards;
- `whats_new` — cards whose type is `news`;
- `notifications` — cards whose type is `notification`;
- `feature_flags` — remotely managed booleans and configuration values;
- `messages` — localized remote strings;
- `update` — latest matching application package;
- `cache_ttl` — recommended client cache duration.

Desktop applications should cache the last valid response locally and keep using it when the network is unavailable. A malformed or unavailable Hub response must never prevent the primary utility from starting.

### Aggregate event

```http
POST /wp-json/maxpost/v1/hub/event
Content-Type: application/json

{
  "app": "mp-folder-creator",
  "event": "card_click",
  "item": "mp-image-converter"
}
```

Supported events:

- `app_open`;
- `download_click`;
- `card_click`;
- `update_seen`;
- `update_installed`.

The MVP stores aggregate monthly counters. It does not require accounts and does not intentionally store file names, document content or user identities.

## WordPress administration

After activating MaxPost Core 0.2.0, WordPress contains a **MaxPost Hub** menu:

- **Dashboard** — aggregate event counters and shortcuts;
- **Hub cards** — recommendations, news, support, partner and notification cards;
- **App updates** — update manifests and download packages;
- **Configuration** — JSON feature flags and localized messages.

### Hub card targeting

Each card can define:

- type;
- title, subtitle and body;
- image;
- button label and destination URL;
- priority;
- one or more application IDs;
- locale;
- start and end date;
- enabled state.

Blank targeting values mean that the card may be delivered to every application or locale.

## Application integration rules

1. Request Hub configuration at startup only when the cached response is expired.
2. Use a six-hour default refresh interval.
3. Apply a short HTTP timeout.
4. Never block the main application workflow while Hub data loads.
5. Verify SHA-256 before installing an update.
6. Open promotion URLs in the system browser.
7. Treat feature flags as optional defaults, not as a dependency for core functions.
8. Do not transmit file names, folder paths or user-created content.

## Example feature flags

```json
{
  "folder_creator.csv_import": true,
  "folder_creator.templates": false,
  "global.support_card": true
}
```

## Example localized messages

```json
{
  "default": {
    "support.title": "Support MaxPost",
    "support.button": "Learn more"
  },
  "uk-UA": {
    "support.title": "Підтримати MaxPost"
  },
  "ru-RU": {
    "support.title": "Поддержать MaxPost"
  }
}
```

## Future hardening

Before high-volume public deployment:

- move analytics to a dedicated append-only table or external analytics service;
- add per-IP and per-application rate limiting to the event endpoint;
- sign update manifests;
- add schema validation and revision history for configuration;
- add role-specific capabilities for Hub editors;
- add CDN caching and ETag support.
