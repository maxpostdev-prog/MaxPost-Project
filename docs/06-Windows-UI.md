# MaxPost Windows UI Standard

**Version:** 1.0 Alpha  
**Status:** Living specification

## Objective

Every MaxPost desktop utility must feel like part of one product family. A user who understands one utility should immediately understand the next.

## Product rule

> One application, one primary task.

If a major feature introduces a second workflow, prefer a separate application.

## Window anatomy

```text
┌──────────────────────────────────────────────┐
│ Title bar                                    │
├──────────────────────────────────────────────┤
│ Optional command bar                         │
├──────────────────────────────────────────────┤
│                                              │
│ Main task workspace                          │
│                                              │
├──────────────────────────────────────────────┤
│ Status / result area                         │
└──────────────────────────────────────────────┘
```

A right-side recommendation panel is optional for larger windows. It must not reduce the usability of the primary task.

## Window sizing

Recommended default: 1100 × 720 px.  
Minimum target: 800 × 520 px.  
Windows must be resizable unless the workflow is a small fixed dialog.

Remember size and position only when the restored window remains visible on an attached display.

## Title bar

Contains:

- application icon;
- application name;
- standard minimize, maximize and close controls;
- optional Settings and About commands.

Prefer standard Windows window controls. Custom title bars require a concrete design benefit and full accessibility testing.

## Main task

Each screen has one dominant action, such as:

- Create folders;
- Convert images;
- Rename files;
- Generate passwords.

The primary button appears after the required inputs, not in an unrelated toolbar.

## Layout

Use an 8 px base grid.

Common spacing:

- 8 px related controls;
- 16 px inside groups;
- 24 px between groups;
- 32 px between major regions.

Labels appear above controls unless a horizontal form clearly improves scanning.

## Typography

Preferred: Segoe UI or the native Windows UI font. Inter may be used for cross-platform consistency if bundled and rendered correctly.

Suggested scale:

- application title: 24 px / semibold;
- section title: 18 px / semibold;
- body: 14 px;
- caption: 12 px;
- primary button: 15–16 px / semibold.

## Controls

### Buttons

Height: 40 px minimum.  
Primary actions use brand blue.  
Secondary actions use neutral surfaces or outlines.  
Danger actions require clear wording and confirmation when destructive.

### Inputs

Height: 40 px minimum. Show visible focus and validation states. Place validation near the affected field.

### Checkboxes and switches

Use checkboxes for independent options. Use switches only for settings that take effect immediately.

### Lists and tables

Large lists must remain responsive through virtualization or incremental rendering. Never block the UI thread while processing files.

## Progress

Operations longer than roughly one second show activity. Operations longer than three seconds should show measurable progress where possible.

Provide:

- current step;
- completed / total count where known;
- cancel action when safe;
- final summary;
- log or details for partial failures.

## Status area

Use concise messages:

> 125 folders created successfully.

For partial failure:

> 122 folders created. 3 items could not be created. View details.

Do not expose raw stack traces to normal users.

## Dialogs

Maximum normal width: 640 px.

Required behavior:

- clear title;
- one primary action;
- Escape closes non-destructive dialogs;
- Enter activates the safe default action;
- focus is trapped and restored;
- destructive actions are explicit.

## Notifications

Use in-app notifications for relevant task results. Avoid routine operating-system notifications unless the user requested background operation.

## Settings

Settings are grouped into:

- General;
- Appearance;
- Updates;
- Privacy;
- Language;
- Advanced, only when necessary.

Defaults must work for most users. Avoid exposing technical settings merely because they exist internally.

## Updates

Update checking is transparent and configurable.

The update screen shows:

- installed version;
- available version;
- short release notes;
- download size where known;
- download/install action;
- option to skip or remind later.

No forced browser opening.

## Recommendations and What's New

Content is fetched asynchronously and cached locally. It must never delay startup.

Rules:

- hide the block when no valid content exists;
- label sponsored or affiliate content;
- open links only after an explicit click;
- allow remote content to fail silently;
- never execute remote HTML or scripts;
- provide a setting to disable online recommendations.

## Offline behavior

The primary utility function must work offline whenever technically possible. Network failure must not block local work.

## Accessibility

Required:

- full keyboard navigation;
- visible focus;
- screen-reader names;
- logical tab order;
- no color-only status;
- scalable text;
- high-contrast compatibility where practical;
- reduced motion.

## Performance targets

Targets, not absolute guarantees:

- perceived cold start near one second;
- idle CPU near zero;
- no blocking network work during startup;
- memory appropriate to the utility, preferably below 150 MB;
- cancellation for long operations.

## Privacy

No hidden telemetry. Any analytics must be documented, minimized and disabled by default unless there is a strong, disclosed reason otherwise.

## Common dialogs

All applications share consistent:

- About;
- Settings;
- Update available;
- Error details;
- Confirmation;
- Support / feedback.

## Release checklist

- primary task works without instructions;
- keyboard-only workflow tested;
- no UI freeze with large input sets;
- offline behavior tested;
- update and recommendation failure tested;
- localization does not truncate controls;
- high-DPI rendering checked;
- no personal test data in screenshots or logs.