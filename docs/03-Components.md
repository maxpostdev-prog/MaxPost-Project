# MaxPost Component Library

**Version:** 1.0 Alpha

## Rule

Every public interface is assembled from documented components. Do not create a local variant when an existing component can be extended safely.

## Component anatomy

Each component specification must define:

- purpose;
- content model;
- variants;
- sizes;
- states;
- responsive behavior;
- accessibility;
- implementation notes.

## MPButton

Variants: `primary`, `secondary`, `ghost`, `danger`.

Default height: 52 px website, 40 px desktop application.  
Radius: 12 px website, 8 px desktop.  
Text uses sentence case. Never show two visually equal primary actions in one group.

States: default, hover, active, focus-visible, disabled, loading.

## MPHeader

Desktop height: 80 px. Sticky after initial render. Contains logo, primary navigation, search, language and theme controls.

Maximum primary links: six. No account or shopping-cart controls unless the platform later introduces a real user account system.

## MPHero

Two-column desktop layout:

- left: headline, description, two actions, trust badges;
- right: real MaxPost application screenshot or mockup.

Mobile stacks content with text first and application preview second.

## MPSoftwareCard

Content order:

1. screenshot;
2. icon and title;
3. short description;
4. metadata;
5. actions.

Preferred image ratio: 16:10. The screenshot is the primary visual. Fallback order: dedicated card image, first screenshot, featured image, branded placeholder.

Actions: one `Download` button and one `Learn more` link or secondary button.

## MPCategoryCard

Contains icon, category title, optional one-line description and software count. The entire card is one link. Avoid nested interactive elements.

## MPUpdateCard

Used for `What's New`. Contains one image, title, summary, three to five highlights and one action. Only one update may be featured on the homepage.

## MPPromotionCard

Used for internal recommendations, sponsors, affiliates or support messages. It must be visibly distinguishable from the primary product action and clearly labelled when sponsored or affiliate-funded.

No automatic redirect. No fake download button.

## MPSearch

Desktop width: up to 420 px. Mobile width: 100%.

Search begins after two characters, uses a 200–300 ms debounce and provides a clear button. Results update without a full-page reload. Keyboard navigation is required.

## MPFilterBar

Contains search, category filter, platform filter and sort. Do not use a large permanent sidebar for the MVP.

## MPGallery

Supports:

- responsive images;
- thumbnail selection;
- lightbox;
- keyboard arrows;
- Escape to close;
- swipe on touch devices;
- focus restoration after closing.

## MPBadge

Variants: neutral, info, success, warning, error and brand. Badges are labels, not buttons. Do not use fabricated ratings or trust claims.

## MPAlert

Variants: information, success, warning and error. Alerts must include icon, title where needed and actionable recovery text.

## MPToast

For non-blocking confirmations only. Default duration: four seconds. Errors requiring a decision belong in an alert or dialog.

## MPSkeleton

Skeletons must reserve the exact approximate dimensions of final content. Avoid full-page spinners.

## MPEmptyState

Contains icon, clear title, explanation and optional next action.

Good:

> No software matches your search. Try another keyword or clear the filters.

## MPDialog

Maximum normal width: 640 px. Requires focus trap, labelled title, Escape behavior and explicit destructive confirmation where applicable.

## MPFooter

Maximum four navigation columns: Software, Resources, Company and Legal. Include product identity, copyright and privacy links.

## Naming

CSS uses BEM-style names:

```text
mp-software-card
mp-software-card__media
mp-software-card__title
mp-software-card__actions
mp-software-card--featured
```

Programmatic components use the `MP` prefix where the platform permits it.

## Lifecycle

```text
Design → Specification → Implementation → Accessibility review → Testing → Release
```

Undocumented production components are technical debt and should be removed or documented.