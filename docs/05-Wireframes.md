# MaxPost Website Wireframes

**Version:** 1.0 Alpha  
**Status:** Implementation blueprint

These wireframes define information order and responsive behavior. Visual styling comes from the Design System.

## Grid

```text
Desktop: 1320 px container, 12 columns, 24 px gutters
Tablet: 8 columns, 24 px gutters
Mobile: 4 columns, 16 px side padding
```

## Homepage — desktop

```text
┌─────────────────────────────────────────────────────────────────────┐
│ LOGO      Software Categories Updates Guides About      Search  ◐  │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  Free Windows utilities          ┌───────────────────────────────┐  │
│  built for real productivity.    │                               │  │
│                                  │  MP application screenshot    │  │
│  Short product promise.          │                               │  │
│                                  └───────────────────────────────┘  │
│  [ Explore Software ] [Updates]                                     │
│  ✓ Free  ✓ Lightweight  ✓ Safe  ✓ Multilingual                     │
│                                                                     │
├─────────────────────────────────────────────────────────────────────┤
│ Featured Software                                      View all →   │
│ ┌──────────────────┐ ┌──────────────────┐ ┌──────────────────┐      │
│ │ screenshot       │ │ screenshot       │ │ screenshot       │      │
│ │ icon + title     │ │ icon + title     │ │ icon + title     │      │
│ │ description      │ │ description      │ │ description      │      │
│ │ [Download] More  │ │ [Download] More  │ │ [Download] More  │      │
│ └──────────────────┘ └──────────────────┘ └──────────────────┘      │
├─────────────────────────────────────────────────────────────────────┤
│ Browse by Category                                                  │
│ [Files] [Archives] [Images] [Text] [Audio] [System] [Network] [...] │
├─────────────────────────────────────────────────────────────────────┤
│ What's New                                  Support MaxPost          │
│ ┌───────────────────────────────────────┐  ┌─────────────────────┐   │
│ │ title + summary      large screenshot │  │ short support text  │   │
│ │ highlights          [Learn more]      │  │ [Support]           │   │
│ └───────────────────────────────────────┘  └─────────────────────┘   │
├─────────────────────────────────────────────────────────────────────┤
│ All Software      [Search................] [Category] [Sort]         │
│ ┌─────────────────────────────────────────────────────────────────┐ │
│ │ icon  title + description   category   version/date  [Download]│ │
│ │ icon  title + description   category   version/date  [Download]│ │
│ │ icon  title + description   category   version/date  [Download]│ │
│ └─────────────────────────────────────────────────────────────────┘ │
├─────────────────────────────────────────────────────────────────────┤
│ FOOTER: identity | Software | Resources | Company | Legal           │
└─────────────────────────────────────────────────────────────────────┘
```

## Homepage — mobile

```text
┌──────────────────────────────┐
│ LOGO              Search  ☰  │
├──────────────────────────────┤
│ Headline                     │
│ Short description            │
│ [ Explore Software ]         │
│ [ Latest Updates ]           │
│ badges                       │
│ application screenshot       │
├──────────────────────────────┤
│ Featured Software            │
│ [software card]              │
│ [software card]              │
│ [software card]              │
├──────────────────────────────┤
│ Categories                   │
│ [two-column compact grid]    │
├──────────────────────────────┤
│ What's New                   │
│ [image]                      │
│ title + summary + action     │
├──────────────────────────────┤
│ All Software                 │
│ [search] [filters button]    │
│ [software result]            │
│ [software result]            │
├──────────────────────────────┤
│ Support                      │
├──────────────────────────────┤
│ Footer                       │
└──────────────────────────────┘
```

## Software page — desktop

```text
┌─────────────────────────────────────────────────────────────────────┐
│ HEADER                                                              │
├─────────────────────────────────────────────────────────────────────┤
│ icon  MP Folder Creator              [ Download ]                    │
│       Create hundreds of folders.    Version · Size · Windows       │
├─────────────────────────────────────────────────────────────────────┤
│ ┌─────────────────────────────────────────────────────────────────┐ │
│ │                    primary screenshot                           │ │
│ └─────────────────────────────────────────────────────────────────┘ │
│ [thumbnail] [thumbnail] [thumbnail]                                 │
├─────────────────────────────────────────────────────────────────────┤
│ Overview                         Product facts                       │
│ description                      OS / Languages / License / Hash     │
├─────────────────────────────────────────────────────────────────────┤
│ Key Features: two-column list                                       │
├─────────────────────────────────────────────────────────────────────┤
│ How to Use: Step 1 → Step 2 → Step 3                                │
├─────────────────────────────────────────────────────────────────────┤
│ Requirements                                                        │
├─────────────────────────────────────────────────────────────────────┤
│ FAQ accordions                                                       │
├─────────────────────────────────────────────────────────────────────┤
│ Changelog                                                            │
├─────────────────────────────────────────────────────────────────────┤
│ Related Software: exactly three cards                               │
├─────────────────────────────────────────────────────────────────────┤
│ Support + FOOTER                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

## Archive / category page

```text
Header
Title + short description
Search + filters + sort
Results summary
3-column grid or compact list
Pagination / Load more
Footer
```

## Search overlay

```text
┌───────────────────────────────────────────────┐
│ Search software...                        ✕   │
├───────────────────────────────────────────────┤
│ Suggested categories                          │
│ Recent or matching results                    │
│ ↑↓ navigate · Enter open · Esc close          │
└───────────────────────────────────────────────┘
```

## Empty state

```text
[icon]
No software matches your search.
Try another keyword or clear the filters.
[Clear filters]
```

## Error pages

### 404

```text
Page not found
The address may be incorrect or the page may have moved.
[Go to homepage] [Browse software]
```

### 500 / unavailable

```text
Something went wrong
The page could not be loaded. Try again later.
[Try again]
```

## Validation checklist

- one clear primary action per first viewport;
- no horizontal scrolling at 360 px;
- keyboard-accessible navigation and gallery;
- images reserve dimensions before loading;
- headings preserve logical hierarchy;
- mobile download action remains easy to reach;
- no fabricated ratings, counts or testimonials.