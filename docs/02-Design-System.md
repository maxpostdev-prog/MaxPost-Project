# MaxPost Design System

**Version:** 1.0 Alpha  
**Status:** Living document

## Purpose

This document defines the visual foundations shared by the MaxPost website, WordPress interfaces, desktop applications and documentation.

## Principles

1. Simplicity before decoration.
2. One clear primary action per screen.
3. Consistency is mandatory.
4. Performance is part of design.
5. Accessibility is a release requirement.
6. Motion explains state; it does not entertain.

## Design tokens

### Color

```css
:root {
  --mp-color-background: #08111f;
  --mp-color-surface: #101827;
  --mp-color-card: #172135;
  --mp-color-card-hover: #1b2940;
  --mp-color-border: #243146;
  --mp-color-border-strong: #33435e;
  --mp-color-primary: #2f6bff;
  --mp-color-primary-hover: #4a7cff;
  --mp-color-secondary: #6c5cff;
  --mp-color-text: #f8fafc;
  --mp-color-text-secondary: #b6c2d2;
  --mp-color-muted: #8b96a5;
  --mp-color-success: #18c37d;
  --mp-color-warning: #f5a524;
  --mp-color-error: #e5484d;
  --mp-color-info: #4da3ff;
}
```

### Spacing

Use only the shared scale:

```css
--mp-space-1: 4px;
--mp-space-2: 8px;
--mp-space-3: 12px;
--mp-space-4: 16px;
--mp-space-6: 24px;
--mp-space-8: 32px;
--mp-space-12: 48px;
--mp-space-16: 64px;
--mp-space-24: 96px;
--mp-space-30: 120px;
```

### Radius

```css
--mp-radius-sm: 8px;
--mp-radius-md: 12px;
--mp-radius-lg: 16px;
--mp-radius-xl: 24px;
--mp-radius-round: 999px;
```

### Typography

```css
--mp-font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", sans-serif;
--mp-font-size-xs: 12px;
--mp-font-size-sm: 14px;
--mp-font-size-md: 16px;
--mp-font-size-lg: 18px;
--mp-font-size-xl: 24px;
--mp-font-size-2xl: 32px;
--mp-font-size-3xl: 40px;
--mp-font-size-4xl: 52px;
--mp-font-size-hero: clamp(42px, 5vw, 64px);
```

Body line height is 1.5. Headings use 1.1–1.25.

### Motion

```css
--mp-duration-fast: 120ms;
--mp-duration-normal: 180ms;
--mp-duration-slow: 250ms;
--mp-ease-standard: cubic-bezier(.2, .8, .2, 1);
```

No ordinary interface animation should exceed 300 ms.

## Layout system

Desktop container: 1320 px maximum.  
Desktop grid: 12 columns, 24 px gutters.  
Tablet grid: 8 columns.  
Mobile grid: 4 columns, 16 px side padding.

Breakpoints:

```text
360  small mobile
480  mobile
768  tablet portrait
1024 tablet landscape
1280 laptop
1440 large desktop
```

## Elevation

Use three levels only:

- Level 1: default cards;
- Level 2: hover and dropdowns;
- Level 3: dialogs and lightboxes.

Avoid visible shadows when a border and background distinction are enough.

## Focus

All interactive elements require a visible keyboard focus ring.

```css
outline: 3px solid color-mix(in srgb, var(--mp-color-primary) 70%, white);
outline-offset: 3px;
```

## States

Every interactive component must define:

- default;
- hover;
- active;
- focus-visible;
- disabled;
- loading;
- error, where relevant.

## Dark and light themes

Dark is the primary MaxPost visual identity. Components must still use semantic tokens so a light theme can be introduced without structural rewrites.

## Imagery

Software screenshots use a 16:10 preferred ratio. Cards reserve image dimensions before loading to prevent layout shift.

## Accessibility baseline

- WCAG 2.2 AA target;
- 4.5:1 text contrast where applicable;
- minimum 44 × 44 px mobile targets;
- reduced-motion support;
- no color-only status communication;
- semantic HTML before ARIA.

## Governance

A new token or component must be documented before it is introduced into production code. Local one-off visual values require explicit justification.
