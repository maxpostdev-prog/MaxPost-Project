# MaxPost Coding Standards

**Document:** `13-Coding-Standards.md`  
**Version:** 1.0 Alpha  
**Status:** Living Specification

## Purpose

These standards keep MaxPost code readable, testable and maintainable across WordPress, front-end and desktop projects.

## General rules

- Prefer simple solutions.
- Keep functions focused.
- Avoid hidden side effects.
- Document public interfaces.
- Reject duplicated business logic.
- Use meaningful names.
- Remove dead code.
- Do not commit secrets or generated clutter.

## Repository boundaries

```text
maxpost-theme → presentation
maxpost-core  → WordPress data and business logic
desktop       → desktop application specifications and shared standards
```

Permanent platform logic must not be added to the theme.

## PHP

Target:

```text
PHP 8.1+
WordPress 6.5+
```

Follow WordPress coding standards unless this specification is stricter.

Requirements:

- `declare(strict_types=1)` may be used where compatible with WordPress integration boundaries.
- Prefix global functions, hooks and options with `maxpost_`.
- Prefer namespaced classes for plugin internals.
- Use early returns to reduce nesting.
- Add scalar and return types where WordPress compatibility allows.
- Never suppress errors with `@`.
- Never use direct SQL without `$wpdb->prepare()`.

Example:

```php
function maxpost_get_icon_id( int $post_id ): int {
    return absint( get_post_meta( $post_id, '_maxpost_icon_id', true ) );
}
```

## WordPress security pattern

Every administrative save handler must handle:

- nonce
- permissions
- autosave
- revision
- sanitization
- expected types

## JavaScript

Use modern browser JavaScript without introducing a framework by default.

Rules:

- `const` by default; `let` when reassignment is required.
- No `var`.
- Use modules when build and browser support permit.
- Guard DOM queries.
- Progressive enhancement is mandatory.
- Avoid global variables.
- Network requests require timeout and error handling.

## CSS

Use:

- design tokens
- BEM-style component naming
- mobile-first responsive rules
- logical properties where practical

Example:

```css
.mp-software-card {}
.mp-software-card__media {}
.mp-software-card__title {}
.mp-software-card--featured {}
```

Forbidden:

- arbitrary colors inside components
- `!important` as routine architecture
- IDs for styling
- selectors coupled to WordPress-generated nesting unnecessarily

## HTML and templates

- Use semantic HTML.
- One `h1` per page.
- Buttons perform actions; links navigate.
- All form controls require labels.
- Escape output at the final boundary.
- Avoid business logic in templates.

## Desktop code

The desktop technology stack is not yet fixed. Regardless of framework:

- UI thread must remain responsive.
- File operations must be cancellable where practical.
- Network access must be asynchronous.
- Shared design tokens and terminology must be reused.
- Platform-specific code must be isolated.
- Errors must be converted into user-readable messages.

## Naming

### Products

```text
MP Folder Creator
MP Image Converter
MP Bulk Rename
```

### PHP

```text
MaxPost\Core\SoftwareRepository
maxpost_get_software()
_maxpost_version
```

### JavaScript

```text
softwareCard
loadSoftware
MAX_RESULTS
```

### CSS

```text
mp-button
mp-button__icon
mp-button--primary
```

## Functions and methods

A function should normally:

- perform one task
- have a clear verb-based name
- avoid more than a small number of parameters
- return a predictable type
- not mutate unrelated state

Large methods must be decomposed before new behavior is added.

## Comments

Comments explain why, constraints or non-obvious behavior.

Do not narrate obvious code.

Bad:

```php
// Get the title.
$title = get_the_title();
```

Useful:

```php
// Keep the legacy key readable until migration 004 has completed.
```

## Error handling

- Fail safely.
- Do not expose stack traces to users.
- Return typed or documented error structures.
- Log enough context to diagnose the problem without leaking secrets.
- Optional remote content must never break the main application workflow.

## Testing expectations

Business logic requires tests.

Minimum areas:

- metadata validation
- migration behavior
- REST schema
- permission checks
- caching invalidation
- date and language filtering
- critical UI interactions

## Commit conventions

Use concise imperative messages with a scope where useful:

```text
docs: add REST API specification
feat(core): register software post type
fix(theme): preserve screenshot aspect ratio
chore: update validation workflow
```

Each commit should represent one coherent change.

## Pull requests

Every pull request must explain:

- what changed
- why
- how it was tested
- screenshots for visual changes
- migration or compatibility impact

## Definition of done

A change is complete only when:

- [ ] Implementation is finished
- [ ] Relevant documentation is updated
- [ ] Tests or manual checks pass
- [ ] Security impact is reviewed
- [ ] Accessibility is reviewed for UI work
- [ ] Performance impact is acceptable
- [ ] Changelog is updated when user-visible
