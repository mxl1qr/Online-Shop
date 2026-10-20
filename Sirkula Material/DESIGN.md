---
name: Modern Technical Professional
colors:
  surface: '#f7faf9'
  surface-dim: '#d7dbda'
  surface-bright: '#f7faf9'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f1f4f3'
  surface-container: '#ebeeed'
  surface-container-high: '#e6e9e8'
  surface-container-highest: '#e0e3e2'
  on-surface: '#181c1c'
  on-surface-variant: '#43474e'
  inverse-surface: '#2d3131'
  inverse-on-surface: '#eef1f0'
  outline: '#73777f'
  outline-variant: '#c3c6cf'
  surface-tint: '#446085'
  primary: '#000f23'
  on-primary: '#ffffff'
  primary-container: '#002547'
  on-primary-container: '#718db5'
  inverse-primary: '#acc8f3'
  secondary: '#006c47'
  on-secondary: '#ffffff'
  secondary-container: '#85f9bf'
  on-secondary-container: '#00734c'
  tertiary: '#735c00'
  on-tertiary: '#ffffff'
  tertiary-container: '#c9a840'
  on-tertiary-container: '#4f3e00'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#d3e4ff'
  primary-fixed-dim: '#acc8f3'
  on-primary-fixed: '#001c38'
  on-primary-fixed-variant: '#2b486c'
  secondary-fixed: '#85f9bf'
  secondary-fixed-dim: '#68dca4'
  on-secondary-fixed: '#002113'
  on-secondary-fixed-variant: '#005235'
  tertiary-fixed: '#ffe088'
  tertiary-fixed-dim: '#e6c358'
  on-tertiary-fixed: '#241a00'
  on-tertiary-fixed-variant: '#574500'
  background: '#f7faf9'
  on-background: '#181c1c'
  surface-variant: '#e0e3e2'
typography:
  headline-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 32px
    fontWeight: '700'
    lineHeight: '1.2'
  body-md:
    fontFamily: Montserrat
    fontSize: 16px
    fontWeight: '400'
    lineHeight: '1.5'
  label-sm:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '500'
    lineHeight: '1.0'
rounded:
  sm: 0.5rem
  DEFAULT: 1rem
  md: 1.5rem
  lg: 2rem
  xl: 3rem
  full: 9999px
spacing:
  base: 8px
  xs: 4px
  sm: 8px
  md: 16px
  lg: 24px
  xl: 32px
  gutter: 16px
  margin: 24px
---

# Design System: Modern Technical Professional

## Brand & Style
The brand identity is rooted in reliability and high-performance precision. It transitions from a warm, high-contrast palette to a sophisticated, corporate-modern aesthetic. The personality is professional and balanced, utilizing a mixture of tech-focused typography and a cool, calming color palette to evoke feelings of trust, efficiency, and clarity. The style is inspired by modern productivity interfaces, prioritizing functional elegance over unnecessary decoration.

## Colors
Our palette is led by a deep, authoritative Primary Navy (#002547) which provides a strong foundation for professional applications. This is accented by a refined Secondary Emerald (#009463) for highlights and call-to-actions, offering a more grounded and mature professional feel. The Tertiary Gold (#ffda6c) is used sparingly for alerts or to draw attention to critical data points with a softer, sophisticated glow. The Neutral background (#f4f7f6) is a very soft, cool-toned grey that reduces eye strain and provides a clean canvas for the high-contrast primary elements.

## Typography
The system uses a sophisticated trio of typefaces. **Plus Jakarta Sans** is used for headlines to provide a modern, geometric look that remains highly legible. **Montserrat** is the primary typeface for body text, offering a clean and familiar reading experience. **Inter** is utilized for labels and technical data, providing a clean, modern, and highly legible aesthetic that maintains clarity even at small scales.

## Layout & Spacing
The layout follows a precise 8px grid system. This rhythmic approach ensures vertical and horizontal consistency across all views. We utilize a fluid grid system for main content areas with 16px gutters and 24px margins, allowing the interface to scale gracefully across different screen sizes while maintaining a professional density of information.

## Elevation & Depth
Depth is conveyed through tonal layers and subtle ambient shadows. We avoid heavy shadows in favor of surface-container tiers, where content sits on elevated backgrounds that use slightly different shades of the neutral palette. When shadows are necessary for focus (such as in modals or dropdowns), they are extra-diffused with low opacity to maintain the clean, corporate aesthetic.

## Shapes
The design system employs a "Pill-shaped" or highly rounded language to significantly soften the technical nature of the interface. Standard components like buttons and input fields use a 1rem (16px) corner radius. Larger containers or cards utilize a more pronounced 2rem (32px) radius to create distinct visual containment and an approachable, contemporary feel.

## Components
- **Buttons:** Primary buttons use the Navy background with Emerald or White text; they feature a 1rem corner radius.
- **Input Fields:** Styled with the Neutral color as a background and a subtle border that highlights in Emerald on focus, utilizing a 1rem radius.
- **Cards:** Use a white surface with a 2rem corner radius and a very light ambient shadow to separate them from the Neutral background.
- **Chips:** Utilize Inter for the label and a soft version of the Primary or Secondary color for the background.
- **Checkboxes & Radios:** Should use the Primary Navy when active, providing a high-contrast state change.