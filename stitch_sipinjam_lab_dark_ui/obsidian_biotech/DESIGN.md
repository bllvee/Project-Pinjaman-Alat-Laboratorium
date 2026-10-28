---
name: Obsidian BioTech
colors:
  surface: '#131313'
  surface-dim: '#131313'
  surface-bright: '#3a3939'
  surface-container-lowest: '#0e0e0e'
  surface-container-low: '#1c1b1b'
  surface-container: '#201f1f'
  surface-container-high: '#2a2a2a'
  surface-container-highest: '#353534'
  on-surface: '#e5e2e1'
  on-surface-variant: '#bbcabf'
  inverse-surface: '#e5e2e1'
  inverse-on-surface: '#313030'
  outline: '#86948a'
  outline-variant: '#3c4a42'
  surface-tint: '#4edea3'
  primary: '#4edea3'
  on-primary: '#003824'
  primary-container: '#10b981'
  on-primary-container: '#00422b'
  inverse-primary: '#006c49'
  secondary: '#c6c6c7'
  on-secondary: '#2f3131'
  secondary-container: '#454747'
  on-secondary-container: '#b4b5b5'
  tertiary: '#ffb95f'
  on-tertiary: '#472a00'
  tertiary-container: '#e29100'
  on-tertiary-container: '#523200'
  error: '#ffb4ab'
  on-error: '#690005'
  error-container: '#93000a'
  on-error-container: '#ffdad6'
  primary-fixed: '#6ffbbe'
  primary-fixed-dim: '#4edea3'
  on-primary-fixed: '#002113'
  on-primary-fixed-variant: '#005236'
  secondary-fixed: '#e2e2e2'
  secondary-fixed-dim: '#c6c6c7'
  on-secondary-fixed: '#1a1c1c'
  on-secondary-fixed-variant: '#454747'
  tertiary-fixed: '#ffddb8'
  tertiary-fixed-dim: '#ffb95f'
  on-tertiary-fixed: '#2a1700'
  on-tertiary-fixed-variant: '#653e00'
  background: '#131313'
  on-background: '#e5e2e1'
  surface-variant: '#353534'
typography:
  headline-xl:
    fontFamily: Plus Jakarta Sans
    fontSize: 40px
    fontWeight: '700'
    lineHeight: 48px
    letterSpacing: -0.02em
  headline-xl-mobile:
    fontFamily: Plus Jakarta Sans
    fontSize: 30px
    fontWeight: '700'
    lineHeight: 38px
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 32px
    fontWeight: '600'
    lineHeight: 40px
    letterSpacing: -0.015em
  headline-lg-mobile:
    fontFamily: Plus Jakarta Sans
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
    letterSpacing: -0.01em
  headline-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 22px
    fontWeight: '600'
    lineHeight: 28px
    letterSpacing: -0.01em
  headline-sm:
    fontFamily: Plus Jakarta Sans
    fontSize: 18px
    fontWeight: '600'
    lineHeight: 24px
  body-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  body-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
  body-sm:
    fontFamily: Plus Jakarta Sans
    fontSize: 12px
    fontWeight: '400'
    lineHeight: 16px
  label-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 14px
    fontWeight: '600'
    lineHeight: 20px
    letterSpacing: 0.01em
  label-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 12px
    fontWeight: '600'
    lineHeight: 16px
    letterSpacing: 0.02em
  label-sm:
    fontFamily: Plus Jakarta Sans
    fontSize: 11px
    fontWeight: '700'
    lineHeight: 14px
    letterSpacing: 0.04em
  code-sm:
    fontFamily: JetBrains Mono
    fontSize: 12px
    fontWeight: '400'
    lineHeight: 16px
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  gutter: 1.25rem
  gutter-desktop: 1.5rem
  margin: 1rem
  margin-tablet: 2rem
  margin-desktop: 2.5rem
  space-xs: 0.25rem
  space-sm: 0.5rem
  space-md: 1rem
  space-lg: 1.5rem
  space-xl: 2rem
---

## Brand & Style

This design system establishes a high-precision, technical aesthetic tailored for scientific inventory and laboratory equipment management. Designed for researchers, lab technicians, and academic coordinators, the UI commands authority, surgical precision, and effortless readability under demanding lab lighting environments.

The visual direction merges **Minimalism** with **Technical High-Contrast**:
- **Atmospheric Depth:** Deep, true-black foundations (`#080808`) eliminate visual clutter and reduce ocular fatigue during extended shifts.
- **Instrument Precision:** Crisp borders and measured spacing communicate calibrated reliability, mirroring physical analytical instrumentation.
- **Intentional Telemetry:** Functional color accents (emerald, amber, rose) serve as critical telemetry markers for equipment availability, maintenance locks, and overdue returns rather than mere decorative elements.

## Colors

The palette leverages a structured luminance ramp engineered strictly for dark-mode utility:

### Core Surfaces
- **Canvas Base (`#080808`):** True-black foundation for main application viewports.
- **Sidebar & Structural Shelves (`#0d0d0d`):** Primary navigation rail and secondary containment docks.
- **Card & Data Surfaces (`#141414`):** Interactive inventory rows, modal cards, and floating containers.
- **Elevated Interactive Surface (`#1c1c1c`):** Hover states, input backgrounds, and nested sub-panels.
- **Structural Stroke (`#262626`):** Low-contrast architectural divider lines and container perimeters.

### Semantic Telemetry
- **Primary Emerald (`#10b981`):** Active status, available items, verified calibration, and successful checkouts.
- **Warning Amber (`#f59e0b`):** Under maintenance, pending return verification, and expiring reservations.
- **Critical Rose (`#f43f5e`):** Broken hardware, hazardous containment locks, overdue items, and destructive actions.
- **Action Secondary (`#ffffff`):** Stark white high-priority primary actions and stark headers against dark fields.
- **Muted Contrast (`#a1a1aa` / `#71717a`):** Secondary specs, serial metadata, and disabled indicators.

## Typography

The type hierarchy uses **Plus Jakarta Sans** for clean geometric legibility paired with generous counter spaces that remain sharp on high-density OLED and matte LCD monitors. 

- **Headlines:** Set with tightened letter-spacing (`-0.01em` to `-0.02em`) to maintain punchy, architectural density.
- **Labels & Badges:** Utilize bold weights (`600` and `700`) paired with subtle positive tracking for instantaneous telemetry recognition.
- **Monospace Inclusion:** Serial numbers, asset tags, barcode IDs, and hardware calibration timestamps inherit a specialized `code-sm` token using `JetBrains Mono` for rapid visual alignment.

## Elevation & Depth

Visual hierarchy does not use diffuse multi-layered drop shadows. Instead, it relies on **Tonal Stratification and Low-Contrast Outlines**:

- **Ground Level (Canvas):** `#080808` base plane.
- **Level 1 (Panels & Row Cards):** `#141414` fill with a crisp `1px` continuous border in `#262626`.
- **Level 2 (Hover & In-Focus Items):** Fills transition to `#1a1a1a`, with borders shifting to `#333333`.
- **Level 3 (Modals, Slide-overs, & Popovers):** `#181818` fill framed by `#2d2d2d` with a deep ambient occlusion shadow (`0 20px 40px rgba(0, 0, 0, 0.7)`).
- **Glow Accents:** Status indicators employ an ultra-subtle directional glow: e.g., emerald badges utilize an inner border shadow of `0 0 12px rgba(16, 185, 129, 0.15)` to simulate illuminated hardware LEDs.

## Shapes

The design system employs a **dual-shape geometry strategy**:

- **Container Enclosures (`roundedness: 2`):** Cards, standard inputs, modaled viewports, and action sheets adopt an `8px` (`0.5rem`) corner radius. Larger parent cards scale to `12px` or `16px`. This preserves structural order and maximizes screen efficiency.
- **Indicators & Micro-Elements (Pill Shape):** Status badges, availability indicators, counter tags, and avatar frames are strictly pill-shaped (`9999px` border radius). This sharp contrast between rectangular inventory rows and pill badges guides the eye immediately to status cues.

## Components

### Buttons
- **Primary Action:** Stark crisp white background (`#ffffff`), solid black text (`#000000`), font weight `600`, `8px` radius. Hover shifts to `#e4e4e7`. Pressed scales slightly to `0.98`.
- **Secondary / Action Tool:** Transparent background with `#262626` outline, white text (`#ffffff`). Hover triggers `#141414` fill and `#3f3f46` border.
- **Destructive Action:** `#1c1214` background, `#f43f5e` outline border and text. Hover scales to solid `#f43f5e` with white text.

### Clean Row Cards (Equipment List Rows)
- Designed as horizontal data-dense rows set against `#141414` with a crisp `1px` border of `#262626`.
- Hover triggers border brightening to `#3f3f46` and surface transition to `#181818`.
- Layout structure: Left-aligned asset thumbnail + mono serial code; flexible center section displaying equipment name, category, and technical specs; right-aligned pill status badge + quick action menu button.

### Badges & Status Pills
- Compact, fully rounded pill format (`rounded-full`, padding `2px 10px`).
- **Available:** Emerald background tint (`rgba(16, 185, 129, 0.1)`), emerald text (`#10b981`), `1px` border (`rgba(16, 185, 129, 0.25)`). Accompanied by a solid `6px` glowing emerald pulse dot.
- **In Use / Maintenance:** Amber background tint (`rgba(245, 158, 11, 0.1)`), amber text (`#f59e0b`), `1px` border (`rgba(245, 158, 11, 0.25)`).
- **Overdue / Quarantined:** Rose background tint (`rgba(244, 63, 94, 0.1)`), rose text (`#f43f5e`), `1px` border (`rgba(244, 63, 94, 0.25)`).

### Input Fields & Search Bars
- Background: `#0d0d0d`.
- Border: `1px solid #262626`. Radius: `8px`.
- Typography: `#ffffff` body text, placeholder text muted in `#52525b`.
- Focus state: Border illuminates to `#10b981` with a zero-offset ring `0 0 0 1px #10b981`.

### Checkboxes & Selection Targets
- `18px x 18px` square with `4px` radius.
- Unchecked: `#0d0d0d` background, `#262626` border.
- Checked: `#10b981` background, `#000000` checkmark glyph, border color `#10b981`.

### Equipment Return Timeline / Stepper
- Horizontal nodes connected by `2px` lines (`#262626`).
- Active steps highlighted in `#10b981`; overdue or flagged intervals rendered in `#f43f5e`.