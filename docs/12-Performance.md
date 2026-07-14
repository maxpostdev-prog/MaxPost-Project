# MaxPost Performance

**Document:** `12-Performance.md`  
**Version:** 1.0 Alpha  
**Status:** Living Specification

## Purpose

Performance is a product feature. MaxPost must feel fast on the website, in WordPress administration, through the REST API and inside desktop utilities.

## Website targets

Target Core Web Vitals:

```text
LCP < 2.5 s
CLS < 0.1
INP < 200 ms
```

Additional targets:

- Lighthouse Performance: 90+
- Initial JavaScript: minimal and route-specific
- No avoidable render-blocking third-party resources
- Stable image dimensions

## Asset strategy

- Use responsive WordPress images.
- Prefer WebP or AVIF where supported.
- Lazy-load below-the-fold media.
- Do not lazy-load the primary LCP image.
- Minify production CSS and JavaScript.
- Avoid duplicate icon and font libraries.
- Use locally hosted Inter or a system stack.

## JavaScript policy

Use JavaScript only when behavior requires it.

Allowed examples:

- live catalogue filtering
- navigation menu
- screenshot lightbox
- progressive enhancement

Avoid large frameworks for static WordPress templates.

## CSS policy

- Use design tokens.
- Split by foundation, components and layouts.
- Remove unused styles from production.
- Avoid deeply nested selectors.
- Keep critical first-screen styles compact.

## REST API targets

Recommended server response targets:

```text
Cached public GET: < 150 ms
Uncached simple GET: < 500 ms
```

Collection endpoints must use pagination and field allowlists.

Do not return full post objects when clients require a small schema.

## Caching layers

Potential layers:

1. Browser cache
2. CDN or reverse proxy
3. WordPress page cache
4. Object cache
5. Transients for computed API responses
6. Client-side desktop cache

Every cached object needs a documented invalidation trigger.

## Cache invalidation

Invalidate relevant caches when:

- software is published or updated
- category relationships change
- an update is published
- promotion status or dates change
- global app configuration changes

Do not flush every cache for unrelated edits.

## Database performance

- Avoid unbounded queries.
- Use pagination.
- Avoid repeated post-meta queries inside loops.
- Prime caches or use plugin helper objects.
- Introduce custom indexes only after measurement.
- Avoid `flush_rewrite_rules()` on normal requests.

## Image performance

Every rendered image must have known dimensions or aspect ratio.

Software card images use a consistent ratio:

```text
16:10
```

Provide appropriate `srcset` and `sizes` through WordPress image functions.

## Desktop application targets

Initial goals:

```text
Cold start: approximately 1 second where practical
Idle CPU: approximately 0%
Idle memory: under 100 MB for simple utilities
UI response: no blocking operations on main thread
```

Long operations must:

- run asynchronously
- show progress after a short delay
- support cancellation where practical
- avoid freezing the window

## Network behavior

Desktop applications must not block startup waiting for the API.

Recommended flow:

1. Open application immediately.
2. Load cached recommendations or news.
3. Request fresh data in background.
4. Use a short timeout.
5. Preserve last valid cache on failure.
6. Hide optional remote sections if no data exists.

## Performance budgets

Every new feature must document its cost:

- JavaScript bytes
- CSS bytes
- requests
- database queries
- API response size
- startup impact
- memory impact

## Measurement

Use measurement before optimization.

Website tools may include:

- Lighthouse
- WebPageTest
- Query Monitor in development
- browser performance tools

Desktop measurement may include:

- startup timing
- memory profiling
- UI-thread monitoring
- large-file test fixtures

## Regression policy

A change that materially worsens a defined performance target requires explicit justification and a follow-up plan.

## Checklist

- [ ] LCP image prioritized
- [ ] Layout dimensions reserved
- [ ] Scripts loaded only where needed
- [ ] API collections paginated
- [ ] Cache invalidation defined
- [ ] No repeated meta-query loops
- [ ] Desktop network calls are asynchronous
- [ ] Performance measured before release
