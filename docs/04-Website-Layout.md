# MaxPost Website Layout Specification

**Version:** 1.0 Alpha

## Purpose

The website is a software catalogue and product showroom. It is not a traditional blog, online store or generic WordPress template.

Every page must help a visitor:

1. understand the product;
2. trust MaxPost;
3. download software;
4. discover relevant tools.

## Global structure

```text
Header
Main content
Support or final CTA
Footer
```

No sidebars. No floating advertising widgets. No multiple competing download buttons.

## Header

Desktop height: 80 px. Sticky with an opaque or blurred surface after scrolling.

Content:

- MP logo and MaxPost wordmark;
- Software;
- Categories;
- Updates;
- Guides;
- About;
- search;
- language;
- theme toggle.

Mobile uses a compact header and accessible menu dialog.

## Homepage

Required order:

1. Hero
2. Featured Software
3. Browse by Category
4. What's New
5. All Software
6. Support MaxPost
7. Footer

### Hero

Desktop uses a 50/50 or 45/55 grid. Left side contains a two-line maximum headline, short paragraph, primary and secondary action, and trust badges. Right side contains a real MaxPost application preview.

Target height: 720–900 px depending on viewport, not a rigid full screen.

Primary action: `Explore Software`.  
Secondary action: `Latest Updates`.

### Featured Software

Three cards on desktop, two on tablet, one on mobile. No autoplay carousel. Cards use real product screenshots.

### Categories

Compact grid of task-based categories. Desktop may show six to nine cards in one or two rows.

### What's New

One featured update only. Use a large image and concise release highlights. Do not turn this section into a generic post feed.

### All Software

For the first release, use a compact searchable list or grid. Search and filters remain visible above results. Do not display ratings or download counts until real data exists.

## Software archive

Contains:

- page heading and summary;
- search;
- category filter;
- operating-system filter;
- free/paid filter prepared for future use;
- sort by name and update date;
- software results;
- empty state;
- pagination or explicit load more.

Desktop: three-card grid or dense list.  
Tablet: two cards.  
Mobile: one card.

## Individual software page

Required sequence:

1. Product hero
2. Primary download action
3. Product metadata
4. Screenshot gallery
5. Overview
6. Key features
7. How to use
8. System requirements
9. Supported languages
10. FAQ
11. Changelog
12. Related software
13. Support block

The first viewport must answer:

- What is this?
- What does it do?
- Does it support my system?
- Where do I download it?

## Category page

Contains category title, short task-oriented description, search/filter controls and software results. Avoid SEO filler paragraphs above the tools.

## Updates page

Contains one optional featured update followed by chronological update cards. Each update links to the affected software page and its changelog.

## Guides

Guides use a readable content width near 760–860 px, table of contents, step headings, screenshots, FAQ and a relevant software recommendation.

## Download flow

```text
Download button → file response → browser download
```

No countdown, forced registration, misleading mirror buttons or intermediate advertising pages.

## Responsive priorities

Mobile product page priority:

1. title and purpose;
2. download;
3. compatibility and version;
4. screenshot;
5. features;
6. remaining documentation.

## Performance budget

Targets:

- LCP under 2.5 s;
- CLS under 0.1;
- INP under 200 ms;
- responsive images;
- lazy loading below the fold;
- minimal JavaScript;
- no blocking third-party fonts.

## Content rules

- Headline: maximum two lines where practical.
- Product summary: usually under 160 characters.
- Features: maximum eight primary bullets.
- One primary CTA per visual group.
- Never invent testimonials, download counts or ratings.

## WordPress rule

The public interface must not resemble a default WordPress blog. WordPress is the CMS; the MaxPost design system controls the experience.