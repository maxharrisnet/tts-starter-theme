# CLAUDE.md — TTS WordPress Starter Theme

This is the single source of truth for all Claude Code sessions on this project.
Read this file **completely** before writing any code. Every architectural decision
is documented here. Do not deviate from these patterns without explicit instruction.

---

## Table of Contents

1. Project Overview
2. Site Profiles
3. Skills Reference
4. Tech Stack & Build Setup
5. Recommended Plugins & Auto-Install
6. File & Folder Structure
7. Build Order
8. helpers.php — Build These First
9. Admin Options Page
10. Custom Post Types
11. Meta Fields by Page Template
12. Template Parts: Key Patterns
13. Tailwind Configuration
14. Accessibility Standards
15. Performance Standards
16. Image Handling
17. Responsive Design
18. Navigation & Menus
19. Header & Footer Layouts
20. Admin UX Standards
21. SEO, AIO & Social Optimization
22. REST API & n8n Integration
23. Shortcodes & Embeds
24. Coming Soon (Splash) & Maintenance Mode
25. Environment Variables``
26. Coding Standards
27. PHP_CodeSniffer Setup
28. What NOT to Do
29. Forking for a Client Site
30. Session Startup Checklist

---

## 1. Project Overview

A single WordPress theme with 8 site profiles, designed to be forked and deployed
for client sites within one day of receiving their content and specs. Classic editor,
hand-coded template parts, no page builder, no ACF dependency. Tailwind CSS via Vite
build step. Built for developer-speed setup and consistency — not client layout customization.

**Core philosophy:**
- Clients control **content** (text, images) via custom fields and CPTs
- Developers control **layout**, section order, and structure via hard-coded templates
- Admin Options handles site-wide config (set once by developer, rarely touched)
- Consistency is enforced by design — clients cannot break layouts
- Placeholder text must be clearly marked — never use real-sounding default copy
- Go easy on required fields — soft warnings only, except key CPT fields

---

## 2. Site Profiles

Set in Admin Options → Tab 01 (Identity). Controls section rendering on home template
and which profile-specific fields appear in Tab 06.

| # | Profile | Primary Goal | Key Integration |
|---|---------|-------------|-----------------|
| 01 | Booking | Book time with a person or place | Calendly, Acuity |
| 02 | Local Business | Get people in the door or on the phone | Google Maps |
| 03 | Creative | Impress and get contacted | Instagram, Spotify |
| 04 | Venture | Build credibility, capture early momentum | Mailchimp waitlist |
| 05 | Sales | Generate leads or close deals | HubSpot, Mailchimp |
| 06 | Events | Sell tickets, build audience | Eventbrite, Dice |
| 07 | Directory | One brand, multiple locations | Google Maps multi-pin |
| 08 | Community | Inspire giving and recurring engagement | Donorbox, PayPal |

Profile-aware conditional logic lives **only** in:
- `templates/template-home.php` (section inclusion)
- `template-parts/sections/hero.php` (headline/CTA variants)
- `inc/options/options-fields.php` (Tab 06 dynamic fields)

Do not scatter profile conditionals elsewhere without strong justification.

---

## 3. Skills Reference

Load the appropriate skill at the start of each session type. Skills are located
in the Claude Code skills directory.

| Task | Skill to Load |
|------|--------------|
| UX/UI layout decisions | `ui-ux-pro-max` |
| Design system / token planning | `design-system` |
| Tailwind token setup | `tailwind-design-system` |
| General WordPress development | `wordpress-pro` |
| REST API endpoints | `wp-rest-api` |
| Performance optimization | `wp-performance` |
| CLI, deployment, ops | `wp-wpcli-and-ops` |
| Debugging / troubleshooting | `wp-project-triage` |
| HTML/CSS/JS implementation | `frontend-design:frontend-design` |
| Technical SEO | `searchfit-seo:technical-seo` |
| On-page SEO | `searchfit-seo:on-page-seo` |
| Schema / structured data | `searchfit-seo:schema-markup` |
| AI visibility / llms.txt | `searchfit-seo:ai-visibility` |

**Skill gaps — handle via CLAUDE.md instructions (no dedicated skill):**

- **Accessibility:** Follow WCAG 2.1 AA rules in Section 13 of this file explicitly.
- **PHP/OOP:** Core Claude Code knowledge is sufficient. Follow patterns in this file.
- **Security:** Enforced via WPCS ruleset (Section 26) — nonces, escaping, sanitization.
- **i18n:** Wrap all strings in `__()` / `esc_html__()` with `'tts-theme'` text domain.
- **Theme testing:** Run Theme Check plugin + PHPCS before every client fork delivery.
- **Build tooling:** Vite configuration is specified in Section 4 of this file.

---

## 4. Tech Stack & Build Setup

### Stack
- **PHP:** 8.2+
- **WordPress:** 6.5+ (classic editor, no block theme)
- **CSS:** Tailwind CSS v4 (CSS-first config, no tailwind.config.js)
- **Build:** Vite 5.x
- **JS:** Vanilla ES6+ (no jQuery unless a WP dependency requires it)
- **Linting:** PHP_CodeSniffer with WordPress-VIP ruleset
- **Node:** 20 LTS

### Why Vite over @wordpress/scripts
`@wordpress/scripts` wraps webpack and is designed for block/Gutenberg development.
This is a classic theme. Vite is faster, simpler, and handles Tailwind v4 natively.

### package.json (theme root)

```json
{
  "name": "tts-theme",
  "version": "1.0.0",
  "private": true,
  "scripts": {
    "dev":        "vite",
    "build":      "vite build --mode development",
    "build:prod": "composer lint && vite build --mode production",
    "preview":    "vite preview",
    "lint:js":    "eslint assets/src",
    "lint:php":   "composer lint"
  },
  "devDependencies": {
    "vite": "^5.0.0",
    "@vitejs/plugin-legacy": "^5.0.0",
    "tailwindcss": "^4.0.0",
    "@tailwindcss/vite": "^4.0.0",
    "autoprefixer": "^10.4.0"
  }
}
```

### composer.json scripts section
```json
{
  "require-dev": {
    "squizlabs/php_codesniffer": "^3.9",
    "wp-coding-standards/wpcs": "^3.1",
    "phpcompatibility/php-compatibility": "^9.3"
  },
  "scripts": {
    "lint":      "phpcs",
    "lint-fix":  "phpcbf",
    "lint-file": "phpcs --standard=phpcs.xml",
    "post-install-cmd": [
      "phpcs --config-set installed_paths vendor/wp-coding-standards/wpcs,vendor/phpcompatibility/php-compatibility"
    ]
  }
}
```

### Dev vs production builds
- `npm run dev` → Vite dev server, hot reload, source maps, no PHPCS gate
- `npm run build` → Development build with source maps, no PHPCS gate (for staging review)
- `npm run build:prod` → PHPCS must pass first, then Vite builds minified production output

In `wp-config.php` on LocalWP:
```php
define( 'TTS_DEV', true );
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
```

On production/staging, `TTS_DEV` is either undefined or `false`. Never set `WP_DEBUG` true on production.

### vite.config.js (theme root)

```js
import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';
import legacy from '@vitejs/plugin-legacy';
import path from 'path';

export default defineConfig({
  plugins: [
    tailwindcss(),
    legacy({ targets: ['defaults', 'not IE 11'] }),
  ],
  build: {
    outDir: 'assets/dist',
    rollupOptions: {
      input: {
        main: path.resolve(__dirname, 'assets/src/main.css'),
        app:  path.resolve(__dirname, 'assets/src/app.js'),
        admin: path.resolve(__dirname, 'assets/src/admin.js'),
      },
      output: {
        entryFileNames: '[name].js',
        chunkFileNames: '[name].js',
        assetFileNames: '[name].[ext]',
      },
    },
    manifest: true,
  },
  server: {
    // Vite dev server proxies to local WP install
    proxy: {
      '/': 'http://localhost:8888',
    },
  },
});
```

### Asset source structure

```
assets/
├── src/
│   ├── main.css       # Tailwind entry + global styles
│   ├── app.js         # Front-end JS entry
│   └── admin.js       # Admin UI JS (tab switching, media uploader)
└── dist/              # Compiled output (gitignored, built by Vite)
    ├── main.css
    ├── app.js
    ├── admin.js
    └── manifest.json
```

### Enqueuing compiled assets

Use the Vite manifest to enqueue hashed assets in production.
In development, enqueue from Vite dev server.

```php
// inc/enqueue.php
function tts_enqueue_assets() {
    $manifest_path = get_template_directory() . '/assets/dist/manifest.json';

    if ( defined('TTS_DEV') && TTS_DEV ) {
        // Dev: load from Vite dev server
        wp_enqueue_style( 'tts-main', 'http://localhost:5173/assets/src/main.css' );
        wp_enqueue_script( 'tts-app', 'http://localhost:5173/assets/src/app.js', [], null, true );
    } elseif ( file_exists( $manifest_path ) ) {
        $manifest = json_decode( file_get_contents( $manifest_path ), true );
        $css_file = $manifest['assets/src/main.css']['file'] ?? 'main.css';
        $js_file  = $manifest['assets/src/app.js']['file'] ?? 'app.js';
        wp_enqueue_style( 'tts-main', get_template_directory_uri() . '/assets/dist/' . $css_file, [], null );
        wp_enqueue_script( 'tts-app', get_template_directory_uri() . '/assets/dist/' . $js_file, [], null, true );
    }
}
add_action( 'wp_enqueue_scripts', 'tts_enqueue_assets' );

function tts_enqueue_admin_assets( $hook ) {
    // Only load on our options page and post edit screens
    if ( ! in_array( $hook, [ 'post.php', 'post-new.php', 'settings_page_tts-options' ], true ) ) {
        return;
    }
    wp_enqueue_style( 'tts-admin', get_template_directory_uri() . '/assets/dist/admin.css', [], null );
    wp_enqueue_script( 'tts-admin', get_template_directory_uri() . '/assets/dist/admin.js', [], null, true );
    wp_enqueue_media(); // Media uploader for image fields
}
add_action( 'admin_enqueue_scripts', 'tts_enqueue_admin_assets' );
```

Define `TTS_DEV` in `wp-config.php` on local:
```php
define( 'TTS_DEV', true );
```

---

## 5. Recommended Plugins & Auto-Install

No TGM or third-party plugin activation library. Use a lightweight custom admin
notice in `inc/setup.php` that links to the WP plugin installer. Nothing is
force-installed without user action.

### Plugin reference

| Plugin | Slug | Purpose | When |
|--------|------|---------|------|
| Classic Editor | `classic-editor` | Required — theme is not block-compatible | Always |
| Theme Check | `theme-check` | WP.org standards compliance scan | Pre-delivery QA |
| Query Monitor | `query-monitor` | Slow queries, hook inspection, REST debug | Dev only |
| Accessibility Checker | `accessibility-checker` | WCAG 2.1 AA audit per page | Dev + QA |
| Broken Link Checker | `broken-link-checker` | Dead link scan — run once, then disable | Pre-delivery QA |
| UpdraftPlus | `updraftplus` | Automated backups | Production |
| Wordfence | `wordfence` | Security scanning | Production |
| WP Rocket or Perfmatters | `wp-rocket` / `perfmatters` | Caching + performance | Production |
| Smush or ShortPixel | `wp-smushit` / `shortpixel-image-optimiser` | Image compression | Production |

**Classic Editor is required.** This theme is not block-compatible.
Dev-only plugins must be deactivated before client handoff (see Section 28 checklist).

### Core Web Vitals
Measured via tooling — no plugin needed:
- **PageSpeed Insights** (pagespeed.web.dev) — LCP, CLS, INP
- **Chrome DevTools Lighthouse** — local audit during dev
- **Google Search Console** — real-world CWV data post-launch

Target scores: LCP < 2.5s, CLS < 0.1, INP < 200ms.

### Auto-install notice on theme activation

```php
// inc/setup.php
function tts_plugin_install_notice(): void {
    if ( ! current_user_can( 'install_plugins' ) ) return;

    $required = [
        [ 'name' => 'Classic Editor', 'slug' => 'classic-editor', 'file' => 'classic-editor/classic-editor.php' ],
    ];

    $recommended = [
        [ 'name' => 'Query Monitor',          'slug' => 'query-monitor',          'file' => 'query-monitor/query-monitor.php' ],
        [ 'name' => 'Accessibility Checker',  'slug' => 'accessibility-checker',  'file' => 'accessibility-checker/accessibility-checker.php' ],
        [ 'name' => 'Theme Check',            'slug' => 'theme-check',            'file' => 'theme-check/theme-check.php' ],
        [ 'name' => 'Broken Link Checker',    'slug' => 'broken-link-checker',    'file' => 'broken-link-checker/broken-link-checker.php' ],
    ];

    $missing_required    = array_filter( $required,    fn($p) => ! is_plugin_active( $p['file'] ) && ! file_exists( WP_PLUGIN_DIR . '/' . $p['slug'] ) );
    $missing_recommended = array_filter( $recommended, fn($p) => ! is_plugin_active( $p['file'] ) && ! file_exists( WP_PLUGIN_DIR . '/' . $p['slug'] ) );

    if ( $missing_required ) {
        $links = array_map( fn($p) => '<a href="' . esc_url( admin_url( 'plugin-install.php?s=' . urlencode( $p['name'] ) . '&tab=search&type=term' ) ) . '">' . esc_html( $p['name'] ) . '</a>', $missing_required );
        echo '<div class="notice notice-error"><p><strong>TTS Theme requires:</strong> ' . implode( ', ', $links ) . '</p></div>';
    }

    if ( $missing_recommended ) {
        $links = array_map( fn($p) => '<a href="' . esc_url( admin_url( 'plugin-install.php?s=' . urlencode( $p['name'] ) . '&tab=search&type=term' ) ) . '">' . esc_html( $p['name'] ) . '</a>', $missing_recommended );
        echo '<div class="notice notice-info is-dismissible"><p><strong>TTS Theme recommends:</strong> ' . implode( ', ', $links ) . '</p></div>';
    }
}
add_action( 'admin_notices', 'tts_plugin_install_notice' );
```

---

## 6. File & Folder Structure

```
tts-theme/
│
├── style.css                           # Theme header only — no styles here
├── functions.php                       # require() bootstrap only — no logic
├── index.php                           # WP fallback
├── 404.php
├── search.php
├── single.php                          # Native Posts (Updates) single
├── archive.php                         # Native Posts archive
├── screenshot.png
├── CLAUDE.md                           # This file
├── package.json
├── vite.config.js
├── .env                                # Local env vars (never commit)
├── .env.example                        # Committed template, no real values
├── phpcs.xml                           # PHPCS ruleset
│
├── single-tts_service.php              # CPT singles (WP naming, theme root)
├── single-tts_event.php
├── single-tts_team.php
├── single-tts_gallery.php
├── single-tts_location.php
├── single-tts_press.php
├── single-tts_demo.php
│
├── archive-tts_event.php               # CPT archives (theme root)
├── archive-tts_service.php
├── archive-tts_location.php
├── archive-tts_demo.php
│
├── templates/
│   ├── template-home.php
│   ├── template-about.php
│   ├── template-contact.php
│   ├── template-features.php           # 6 fixed slots, renders only if populated
│   ├── template-donate.php
│   ├── template-portfolio.php
│   ├── template-full-width.php         # Legal, terms, privacy
│   ├── template-minimal.php            # Landing pages
│   ├── template-blank.php              # No header or footer
│   └── template-splash.php             # Coming soon / pre-launch page
│
├── template-parts/
│   ├── global/
│   │   ├── header.php                  # Loads header layout partial by option
│   │   ├── footer.php                  # Loads footer layout partial by option
│   │   ├── header-standard.php         # Header layout: standard
│   │   ├── header-minimal.php          # Header layout: minimal
│   │   ├── footer-standard.php         # Footer layout: standard (medium content)
│   │   ├── footer-minimal.php          # Footer layout: minimal
│   │   ├── nav-primary.php
│   │   ├── nav-footer.php
│   │   ├── banner.php                  # Sticky above-nav announcement bar
│   │   ├── location.php                # Address block + map embed (from Options)
│   │   ├── pagination.php              # Wraps paginate_links()
│   │   ├── skip-nav.php                # Accessibility skip link (loads first in header)
│   │   └── cta-strip.php              # Two-button CTA pattern
│   │
│   ├── sections/
│   │   ├── hero.php
│   │   ├── services.php
│   │   ├── testimonials.php
│   │   ├── team.php
│   │   ├── gallery.php
│   │   ├── faqs.php
│   │   ├── features.php
│   │   ├── stats.php
│   │   ├── hours-location.php
│   │   ├── booking-embed.php
│   │   ├── donate-embed.php
│   │   ├── press-logos.php
│   │   ├── video-demo.php
│   │   ├── events-feed.php
│   │   ├── updates-feed.php
│   │   ├── locations-list.php
│   │   └── embed-block.php             # Flexible shortcode/embed/iframe slot
│   │
│   └── cards/
│       ├── card-service.php
│       ├── card-testimonial.php
│       ├── card-team.php
│       ├── card-gallery-item.php
│       ├── card-event.php
│       ├── card-location.php
│       ├── card-press.php
│       ├── card-demo.php
│       ├── card-update.php
│       └── card-search-result.php
│
├── inc/
│   ├── setup.php
│   ├── enqueue.php
│   ├── helpers.php                     # Build first — used everywhere
│   ├── maintenance.php                 # Maintenance mode toggle logic
│   ├── seo.php                         # Meta tags, OG, schema, llms.txt
│   ├── rest-api.php                    # Custom REST endpoints
│   ├── shortcodes.php                  # Theme shortcodes
│   ├── dashboard.php                   # Custom admin dashboard widgets
│   │
│   ├── options/
│   │   ├── options-page.php
│   │   ├── options-fields.php
│   │   └── options-sanitize.php
│   │
│   ├── meta/
│   │   ├── meta-home.php
│   │   ├── meta-about.php
│   │   ├── meta-contact.php
│   │   ├── meta-features.php
│   │   ├── meta-donate.php
│   │   ├── meta-splash.php
│   │   ├── meta-services.php
│   │   ├── meta-testimonials.php
│   │   ├── meta-team.php
│   │   ├── meta-gallery.php
│   │   ├── meta-faqs.php
│   │   ├── meta-events.php
│   │   ├── meta-locations.php
│   │   ├── meta-press.php
│   │   ├── meta-demo.php
│   │   └── meta-updates.php
│   │
│   └── cpt/
│       ├── cpt-services.php
│       ├── cpt-testimonials.php
│       ├── cpt-team.php
│       ├── cpt-gallery.php
│       ├── cpt-faqs.php
│       ├── cpt-events.php
│       ├── cpt-locations.php
│       ├── cpt-press.php
│       └── cpt-demo.php
│
├── assets/
│   ├── src/
│   │   ├── main.css
│   │   ├── app.js
│   │   └── admin.js
│   ├── dist/                           # Gitignored, Vite output
│   └── images/
│       └── placeholder.png
│
├── tests/
│   └── .gitkeep                        # Scaffold for future PHPUnit tests
│
└── languages/
    └── tts-theme.pot
```

---

## 7. Build Order

Follow this sequence. Do not skip ahead. Each layer depends on the previous.

1. `package.json` + `vite.config.js` + `assets/src/` entry files
2. `inc/helpers.php`
3. `inc/setup.php`
4. `inc/enqueue.php`
5. `inc/cpt/*.php` — all CPT registrations
6. `inc/options/*.php` — admin options page
7. `inc/meta/*.php` — all meta box registrations
8. `inc/seo.php`
9. `inc/maintenance.php`
10. `inc/rest-api.php`
11. `inc/shortcodes.php`
12. `inc/dashboard.php`
13. `assets/src/main.css` — Tailwind config + global tokens
14. `template-parts/global/` — skip-nav first, then header/footer variants, then rest
15. `template-parts/sections/`
16. `template-parts/cards/`
17. `templates/` — page templates
18. `single-*.php` and `archive-*.php`
19. `single.php`, `archive.php`, `search.php`, `404.php`
20. `functions.php` — written last, require() only

---

## 8. helpers.php — Build These First

```php
tts_get_option( string $key, mixed $default = '' ): mixed
// Wrapper for get_option(). Always use this — never call get_option() directly.

tts_get_profile(): string
// Returns active profile slug: 'booking'|'local'|'creative'|'venture'|
// 'sales'|'events'|'directory'|'community'

tts_is_profile( string $profile ): bool
// Returns true if active profile matches. Use for conditional section rendering.

tts_has_option( string $key ): bool
// Returns true if option is set and non-empty. Use before rendering optional sections.

tts_render_cta( string $primary_label = '', string $primary_url = '',
                string $secondary_label = '', string $secondary_url = '' ): void
// Outputs the two-button CTA pattern. Falls back to Admin Options values if empty.
// Always use this — never hard-code button pairs inline.

tts_get_image( string $meta_key, int $post_id = 0, string $size = 'large' ): string
// Retrieves image attachment ID from meta and returns wp_get_attachment_image() output.
// Returns empty string if no image set. Never store or retrieve image URLs.

tts_get_image_option( string $key, string $size = 'large' ): string
// Same as tts_get_image() but reads from Admin Options instead of post meta.

tts_render_section( string $slug ): void
// Wrapper for get_template_part() targeting sections/.

tts_render_card( string $slug ): void
// Wrapper for get_template_part() targeting cards/.

tts_the_url( string $meta_key, int $post_id = 0 ): string
// Retrieves a URL meta field. Handles external URLs, relative paths (/about),
// and anchor links (#section). Sanitizes with esc_url() for http/https,
// passes through relative/anchor values with esc_attr(). Use for all URL fields.

tts_placeholder( string $field_label ): string
// Returns a clearly marked placeholder string for unfilled custom fields.
// Format: '[PLACEHOLDER: Field Label]'
// Use in every template where custom field content is displayed.

tts_has_meta( string $meta_key, int $post_id = 0 ): bool
// Returns true if post meta exists and is non-empty.

tts_social_links(): array
// Returns array of [platform => url] for all non-empty social options.

tts_maintenance_active(): bool
// Returns true if maintenance mode is enabled in Admin Options.
```

---

## 9. Admin Options Page

Registered via `add_options_page()` in `inc/options/options-page.php`.
Options key prefix: `tts_`
Tab switching: vanilla JS in `assets/src/admin.js`

**Banner text in Tab 04 (CTAs):**
`tts_banner_text`, `tts_banner_cta_label`, `tts_banner_cta_url`, `tts_banner_active`

### Tab 01 — Identity
| Field | Key | Type |
|-------|-----|------|
| Site Profile | `tts_site_profile` | dropdown |
| Logo | `tts_logo` | image ID |
| Logo Alt Text | `tts_logo_alt` | text — required when logo is set |
| Brand Color Primary | `tts_color_primary` | hex text |
| Brand Color Secondary | `tts_color_secondary` | hex text |
| Accent Color | `tts_color_accent` | hex text |
| Font Pairing | `tts_font_pairing` | dropdown (editorial / expressive) |
| Header Layout | `tts_header_layout` | dropdown: standard / minimal |
| Footer Layout | `tts_footer_layout` | dropdown: standard / minimal |
| Reduce Motion | `tts_reduce_motion` | checkbox |

### Tab 02 — Business
| Field | Key | Type |
|-------|-----|------|
| Business Name | `tts_business_name` | text |
| Tagline | `tts_tagline` | text |
| Phone | `tts_phone` | text |
| Email | `tts_email` | email |
| Address Line 1 | `tts_address_1` | text |
| Address Line 2 | `tts_address_2` | text |
| City | `tts_city` | text |
| State / Province | `tts_state` | text |
| Postal Code | `tts_postal` | text |
| Country | `tts_country` | text |
| Google Maps Embed URL | `tts_map_embed` | url |
| Hours | `tts_hours` | textarea |

### Tab 03 — Social
| Field | Key | Type |
|-------|-----|------|
| Facebook | `tts_social_facebook` | url |
| Instagram | `tts_social_instagram` | url |
| X / Twitter | `tts_social_x` | url |
| LinkedIn | `tts_social_linkedin` | url |
| YouTube | `tts_social_youtube` | url |
| TikTok | `tts_social_tiktok` | url |
| Spotify | `tts_social_spotify` | url |
| SoundCloud | `tts_social_soundcloud` | url |

All social fields optional. Only render output if non-empty (`tts_has_option()`).

### Tab 04 — CTAs & Banner
| Field | Key | Type |
|-------|-----|------|
| Primary CTA Label | `tts_cta_primary_label` | text |
| Primary CTA URL | `tts_cta_primary_url` | url |
| Secondary CTA Label | `tts_cta_secondary_label` | text |
| Secondary CTA URL | `tts_cta_secondary_url` | url |
| Header CTA Label (override) | `tts_cta_header_label` | text |
| Header CTA URL (override) | `tts_cta_header_url` | url |
| Banner Active | `tts_banner_active` | checkbox |
| Banner Text | `tts_banner_text` | text |
| Banner CTA Label | `tts_banner_cta_label` | text |
| Banner CTA URL | `tts_banner_cta_url` | url |

### Tab 05 — Integrations
| Field | Key | Type |
|-------|-----|------|
| Booking Embed Code | `tts_embed_booking` | textarea |
| Donation Embed Code | `tts_embed_donation` | textarea |
| Google Analytics ID | `tts_ga_id` | text |
| Google Tag Manager ID | `tts_gtm_id` | text |
| Facebook Pixel ID | `tts_pixel_id` | text |
| Custom Header Scripts | `tts_scripts_header` | textarea |
| Custom Footer Scripts | `tts_scripts_footer` | textarea |

GTM ID and GA ID may be stored in options (not sensitive).
API keys must use env vars — never stored in options or post meta.
If both GTM and GA IDs are set, GTM takes precedence — GA injected via GTM only.
See Section 25 (Environment Variables) and GTM implementation in Section 21.

### Tab 06 — Profile Settings
Dynamic fields rendered based on `tts_site_profile` value.

| Profile | Field | Key | Type |
|---------|-------|-----|------|
| All | Maintenance Mode Active | `tts_maintenance_active` | checkbox |
| All | Maintenance Message | `tts_maintenance_message` | textarea |
| All | Archive Headers (per CPT) | `tts_archive_header_*` | text |
| Events | Ticket Platform URL | `tts_ticket_platform` | url |
| Directory | Number of Locations | `tts_location_count` | number |
| Community | Donation Goal Amount | `tts_donation_goal` | text |
| Venture | Waitlist Platform URL | `tts_waitlist_url` | url |
| Creative | Press Kit PDF | `tts_press_kit_pdf` | file ID |
| Booking | Default Booking Platform | `tts_booking_platform` | text |

**Archive headers** — store editable labels for CPT archive page headings:
`tts_archive_header_events`, `tts_archive_header_team`, `tts_archive_header_services`, etc.

---

## 10. Custom Post Types

All CPTs use the `tts_` prefix to avoid plugin conflicts.
Author archives: **disabled** on all CPTs (`has_archive` author archives off).

### Disabled archives (no templates — URLs must not be live)
Turn off in `inc/setup.php`:
- Author archives → `remove_action( 'template_redirect', ... )` + redirect to home
- Date archives → same
- Tag archives for CPTs unless explicitly used
- Any CPT archive without a matching `archive-tts_*.php` template

```php
// Disable author archives
add_filter( 'author_rewrite_rules', '__return_empty_array' );
add_action( 'template_redirect', function() {
    if ( is_author() ) {
        wp_redirect( home_url(), 301 );
        exit;
    }
});
```

### CPT: Required fields before publish
Enforced via `save_post` hook with admin notice — soft warning, not hard block.

| CPT | Required fields |
|-----|----------------|
| tts_event | Title + event_date |
| tts_team | Title + role |
| tts_testimonial | quote + author_name |
| tts_location | Title + address_1 |
| All others | Title only |

### Core CPTs

**tts_service**
Meta fields: `price` (text), `service_image` (image ID), `cta_label` (text), `cta_url` (url)
Native: title, content (description)
Admin label hint: "Used in Services section on homepage and Services archive"

**tts_testimonial**
Meta fields: `quote` (textarea, required), `author_name` (text, required), `author_role` (text), `author_image` (image ID), `rating` (number 1–5), `source` (text)
Admin label hint: "Displayed in Testimonials section"

**tts_team**
Meta fields: `role` (text, required), `team_image` (image ID), `email` (email), `phone` (text), `linkedin` (url), `twitter` (url)
Native: title (name), content (bio)
Admin label hint: "Displayed in Team section and About page"

**tts_gallery**
Meta fields: `gallery_image` (image ID), `caption` (text), `category` (text), `project_link` (url), `project_name` (text)
Admin label hint: "Displayed in Gallery section"

**tts_faq**
Meta fields: `answer` (textarea), `display_order` (number)
Native: title (question)
Admin label hint: "Displayed in FAQ section — ordered by Display Order field"

**tts_demo**
Meta fields: `video_url` (url — YouTube or Vimeo), `thumbnail_override` (image ID), `duration` (text), `cta_label` (text), `cta_url` (url), `video_category` (select: Demo / Testimonial / Tutorial / Reel / Performance)
Native: title, content (description)
Admin label hint: "Displayed in Video/Demo section"

**Updates (native WP Posts — relabeled)**
Extra meta fields: `external_url` (url), `source_outlet` (text)
Use native categories as editorial filter: News / Press / Announcements / Blog
Use native featured image and excerpt (repurposed as standfirst)
Admin label hint: "Displayed in Updates/News feed and blog archive"

### Profile-Specific CPTs

**tts_event** (Events, Community, Booking)
Meta fields: `event_date` (date, required), `event_time` (text), `end_date` (date), `end_time` (text), `location_name` (text), `location_address` (text), `ticket_url` (url), `ticket_price` (text), `event_image` (image ID), `organizer` (text), `embed_block` (textarea — shortcode or embed)
Native: title, content (description)
Admin label hint: "Displayed in Events section and Events archive"

### Event date logic

**Frontend queries — future events by default:**
```php
// helpers.php — reusable event query helper
function tts_get_events( string $view = 'upcoming', int $limit = 6 ): WP_Query {
    $today    = date( 'Y-m-d' );
    $compare  = $view === 'past' ? '<' : '>=';
    $order    = $view === 'past' ? 'DESC' : 'ASC';

    return new WP_Query([
        'post_type'      => 'tts_event',
        'posts_per_page' => $limit,
        'orderby'        => 'meta_value',
        'meta_key'       => 'event_date',
        'order'          => $order,
        'no_found_rows'  => $limit === -1 ? false : true,
        'meta_query'     => [[
            'key'     => 'event_date',
            'value'   => $today,
            'compare' => $compare,
            'type'    => 'DATE',
        ]],
    ]);
}
```

Usage in sections and archives:
```php
// Upcoming events (default)
$events = tts_get_events( 'upcoming', 3 );

// Past events (archive or "past events" section)
$events = tts_get_events( 'past', 12 );
wp_reset_postdata();
```

**Archive routing — `?event_view=past` parameter:**
In `archive-tts_event.php`, check `$_GET['event_view']` to switch between upcoming/past:
```php
$view   = ( isset( $_GET['event_view'] ) && $_GET['event_view'] === 'past' ) ? 'past' : 'upcoming';
$events = tts_get_events( $view );
```
Render tab/toggle UI linking to `?event_view=past` and `?event_view=upcoming`.

**Single event — "This event has passed" notice:**
```php
// single-tts_event.php
$event_date = get_post_meta( get_the_ID(), 'event_date', true );
if ( $event_date && $event_date < date( 'Y-m-d' ) ) {
    echo '<div class="tts-notice tts-notice--past">'
        . esc_html__( 'This event has passed.', 'tts-theme' )
        . '</div>';
}
```

**Admin list view — visual indicator for past events:**
```php
// inc/cpt/cpt-events.php
add_filter( 'post_class', function( $classes, $class, $post_id ) {
    if ( get_post_type( $post_id ) !== 'tts_event' ) return $classes;
    $event_date = get_post_meta( $post_id, 'event_date', true );
    if ( $event_date && $event_date < date( 'Y-m-d' ) ) {
        $classes[] = 'tts-past-event';
    }
    return $classes;
}, 10, 3 );
```
Style `.tts-past-event` rows in `admin.css` with reduced opacity and a "Past" badge
via the `manage_tts_event_posts_custom_column` filter.

**tts_location** (Directory)
Meta fields: `address_1` (text, required), `address_2` (text), `city` (text), `state` (text), `postal` (text), `location_phone` (text), `location_email` (email), `location_hours` (textarea), `map_embed` (url), `location_image` (image ID), `manager_name` (text)
Native: title (location name)
Admin label hint: "Displayed in Directory/Locations section"

**tts_press** (Creative)
Meta fields: `article_url` (url), `publish_date` (date), `outlet_logo` (image ID), `pull_quote` (textarea)
Native: title (outlet name), content (headline)
Admin label hint: "Displayed in Press/As Seen In section"

---

## 11. Meta Fields by Page Template

Meta boxes are scoped to pages using a specific template via the `page_template`
post meta check in `add_meta_boxes`. Always label each field with where it appears.

### Placeholder text rule
Every field that outputs to the frontend must use `tts_placeholder()` when empty:
```php
$headline = get_post_meta( get_the_ID(), 'home_hero_headline', true );
echo esc_html( $headline ?: tts_placeholder( 'Hero Headline' ) );
```
Never use real-sounding default copy. `[PLACEHOLDER: Hero Headline]` is correct.

### template-home.php meta fields
Hero:
- `home_hero_headline` — "Appears as main headline in Hero section"
- `home_hero_subheadline` — "Appears below headline in Hero section"
- `home_hero_bg_image` (image ID) — "Hero background image"
- `home_hero_cta1_label` + `home_hero_cta1_url` — "Primary button in Hero"
- `home_hero_cta2_label` + `home_hero_cta2_url` — "Secondary button in Hero"

Intro block:
- `home_intro_headline` — "Section headline above intro text"
- `home_intro_body` (textarea) — "Body copy in intro/about block"
- `home_intro_image` (image ID) — "Image beside intro text"

Stats strip:
- `home_stat_1_number` + `home_stat_1_label` — "Stat block 1"
- `home_stat_2_number` + `home_stat_2_label` — "Stat block 2"
- `home_stat_3_number` + `home_stat_3_label` — "Stat block 3"

Embed block:
- `home_embed_block` (textarea) — "Flexible embed/shortcode slot below main sections"

### template-about.php
- `about_headline` — "Page headline"
- `about_story` (textarea) — "Main body copy / brand story"
- `about_image` (image ID) — "Primary about image"
- `about_image_secondary` (image ID) — "Secondary/side image (optional)"
- `about_values_headline` — "Headline above values section"
- `about_value_1_title` + `about_value_1_body` — "Value block 1"
- `about_value_2_title` + `about_value_2_body` — "Value block 2"
- `about_value_3_title` + `about_value_3_body` — "Value block 3"

### template-contact.php
- `contact_headline` — "Page headline"
- `contact_subheadline` — "Below headline"
- `contact_form_embed` (textarea) — "Form shortcode or embed code"
- `contact_secondary_headline` — "Headline for secondary contact info block"
- `contact_secondary_body` (textarea) — "Additional contact copy"

### template-features.php
- `features_headline` — "Page headline"
- `features_subheadline` — "Below headline"
- `features_intro` (textarea) — "Intro paragraph above feature blocks"
- Feature slots 1–6 (render only if headline is populated):
  - `feature_N_icon` (image ID) — "Icon/image for feature N"
  - `feature_N_headline` — "Feature N headline"
  - `feature_N_body` (textarea) — "Feature N description"
- `features_cta_label` + `features_cta_url` — "CTA button below features"
- `features_embed_block` (textarea) — "Optional embed/shortcode below CTA"

### template-donate.php
- `donate_headline`, `donate_subheadline`, `donate_body` (textarea)
- `donate_embed` (textarea) — "Donation platform embed code"
- `donate_impact_1_number` + `donate_impact_1_label` — "Impact stat 1"
- `donate_impact_2_number` + `donate_impact_2_label` — "Impact stat 2"
- `donate_impact_3_number` + `donate_impact_3_label` — "Impact stat 3"

### template-splash.php
- `splash_headline` — "Main headline on splash/coming soon page"
- `splash_subheadline` — "Subheadline"
- `splash_body` (textarea) — "Supporting copy"
- `splash_logo_override` (image ID) — "Optional logo override for splash"
- `splash_bg_image` (image ID) — "Background image"
- `splash_cta1_label` + `splash_cta1_url` — "Primary CTA (e.g. notify me)"
- `splash_cta2_label` + `splash_cta2_url` — "Secondary CTA (optional)"
- `splash_embed_block` (textarea) — "Form shortcode, waitlist embed, or custom code"
- `splash_pdf` (file ID) — "Optional PDF download (e.g. press kit, one-pager)"

### template-portfolio.php
- `portfolio_headline`, `portfolio_intro` (textarea), `portfolio_filter_label`

---

## 12. Template Parts: Key Patterns

### 404.php — content spec
Hard-coded — clients do not need to edit this. Change in code if needed.
```php
// 404.php structure
get_template_part( 'template-parts/global/header' );
?>
<main id="main-content" role="main">
    <div class="tts-container tts-container-prose text-center py-24">
        <h1><?php esc_html_e( 'Page Not Found', 'tts-theme' ); ?></h1>
        <p><?php esc_html_e( "Sorry, the page you're looking for doesn't exist or has been moved.", 'tts-theme' ); ?></p>
        <?php
        // Optional: render a nav menu if assigned, otherwise fall back to primary nav
        if ( has_nav_menu( 'primary' ) ) {
            wp_nav_menu([ 'theme_location' => 'primary', 'menu_class' => 'tts-404-nav' ]);
        }
        tts_render_cta(
            __( 'Go to Homepage', 'tts-theme' ),
            home_url( '/' )
        );
        ?>
    </div>
</main>
<?php
get_template_part( 'template-parts/global/footer' );
```

### search.php — scope and empty state
Search queries all public post types **except** `tts_gallery`.
```php
// Exclude gallery from search
add_filter( 'pre_get_posts', function( WP_Query $query ): void {
    if ( ! $query->is_main_query() || ! $query->is_search() ) return;
    $public_types = array_keys( get_post_types([ 'public' => true ]) );
    $query->set( 'post_type', array_diff( $public_types, [ 'tts_gallery', 'attachment' ] ) );
});
```

Empty state — hard-coded, no custom fields needed:
```php
// In search.php when no results
if ( ! have_posts() ) : ?>
    <div class="tts-container tts-container-prose py-16 text-center">
        <h2><?php esc_html_e( 'No results found', 'tts-theme' ); ?></h2>
        <p><?php printf(
            esc_html__( 'Nothing matched your search for "%s". Try a different term or browse below.', 'tts-theme' ),
            get_search_query()
        ); ?></p>
        <?php
        // Link to most relevant CPT archive based on active profile
        $profile   = tts_get_profile();
        $cpt_links = [
            'booking'   => [ 'tts_service',  __( 'Browse Services', 'tts-theme' ) ],
            'events'    => [ 'tts_event',     __( 'Browse Events', 'tts-theme' ) ],
            'directory' => [ 'tts_location',  __( 'Browse Locations', 'tts-theme' ) ],
            'community' => [ 'tts_event',     __( 'Browse Events', 'tts-theme' ) ],
        ];
        if ( isset( $cpt_links[ $profile ] ) ) {
            [$cpt, $label] = $cpt_links[ $profile ];
            $url = get_post_type_archive_link( $cpt );
            if ( $url ) {
                tts_render_cta( $label, $url );
            }
        } else {
            tts_render_cta( __( 'Go to Homepage', 'tts-theme' ), home_url( '/' ) );
        }
        ?>
    </div>
<?php endif;
```

### banner.php — full implementation
```php
<?php
// template-parts/global/banner.php
if ( ! tts_get_option( 'tts_banner_active' ) ) return;
$text      = tts_get_option( 'tts_banner_text' );
if ( ! $text ) return;
$cta_label = tts_get_option( 'tts_banner_cta_label' );
$cta_url   = tts_get_option( 'tts_banner_cta_url' );
?>
<div class="tts-banner" role="banner" aria-label="<?php esc_attr_e( 'Site announcement', 'tts-theme' ); ?>">
    <div class="tts-container flex items-center justify-between gap-4 py-2">
        <p class="m-0"><?php echo wp_kses_post( $text ); ?></p>
        <?php if ( $cta_label && $cta_url ) : ?>
            <a href="<?php echo tts_the_url( '', 0, $cta_url ); ?>"
               class="tts-banner__cta shrink-0">
                <?php echo esc_html( $cta_label ); ?>
            </a>
        <?php endif; ?>
        <button class="tts-banner__close ml-auto shrink-0"
                aria-label="<?php esc_attr_e( 'Dismiss announcement', 'tts-theme' ); ?>">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>
```
Banner dismiss stores state in `sessionStorage` so it stays closed for the session.
Implement dismiss logic in `app.js`.

### Image alt text — attachment meta requirement
When images are attached via the media uploader in any meta box, the alt text field
in the WP media library must be populated. Enforce this with an admin notice:

```php
// inc/setup.php
add_action( 'admin_notices', function(): void {
    $screen = get_current_screen();
    if ( ! in_array( $screen->base, [ 'post', 'post-new' ], true ) ) return;

    // Check all image meta fields on this post for missing alt text
    $post_id    = get_the_ID();
    $image_keys = []; // Populated per post type — see individual meta files
    foreach ( $image_keys as $key ) {
        $img_id = get_post_meta( $post_id, $key, true );
        if ( $img_id && ! get_post_meta( $img_id, '_wp_attachment_image_alt', true ) ) {
            echo '<div class="notice notice-warning"><p>'
                . sprintf(
                    esc_html__( 'Image "%s" is missing alt text. Please add it in the Media Library.', 'tts-theme' ),
                    esc_html( get_the_title( $img_id ) )
                )
                . '</p></div>';
        }
    }
});
```

Each meta file (`meta-team.php`, `meta-services.php`, etc.) defines its own
`$image_keys` array for that post type's image fields.

### Conditional rendering
```php
// Check CPT has published posts before including section
$query = new WP_Query(['post_type' => 'tts_service', 'posts_per_page' => 1]);
if ( $query->have_posts() ) {
    get_template_part( 'template-parts/sections/services' );
}
wp_reset_postdata();

// Check meta field before rendering block
if ( tts_has_meta( 'home_intro_headline' ) ) {
    get_template_part( 'template-parts/sections/intro' );
}
```

### embed-block.php — flexible shortcode/embed slot
Used wherever a flexible content slot is needed (form, video embed, custom shortcode).
Accepts content from a `_embed_block` meta field. Renders via `do_shortcode()`.
Never assume the content type — just execute and output.

```php
$embed = get_post_meta( get_the_ID(), $meta_key, true );
if ( $embed ) {
    echo '<div class="tts-embed-block">';
    echo do_shortcode( wp_kses_post( $embed ) );
    echo '</div>';
}
```

### PDF uploads
Store attachment ID in meta field (e.g. `splash_pdf`, `tts_press_kit_pdf`).
Render as download link using `wp_get_attachment_url( $id )`.
Accept only PDF MIME type in the media uploader via JS filter.

### URL field flexibility
All URL meta fields use `tts_the_url()` helper which handles:
- External URLs: `https://example.com` → sanitized with `esc_url()`
- Relative paths: `/about` or `../contact` → passed through with `esc_attr()`
- Anchor links: `#section-id` → passed through with `esc_attr()`

Never assume a URL field contains a full external URL.

### Image handling
Always store attachment IDs, never URLs.
Always retrieve via `wp_get_attachment_image()` for responsive `srcset` output.
Always include `alt` text: pull from attachment alt field, fall back to post title.

```php
$img_id = get_post_meta( get_the_ID(), 'team_image', true );
if ( $img_id ) {
    echo wp_get_attachment_image( $img_id, 'tts-card', false, [
        'class'   => 'w-full h-auto',
        'loading' => 'lazy',
        'alt'     => get_post_meta( $img_id, '_wp_attachment_image_alt', true )
                     ?: get_the_title(),
    ] );
}
```

Never use `lazy` loading on above-the-fold images (hero). Use `eager` there.

### skip-nav.php
Must be the first element output inside `<body>`, before header markup.
```html
<a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-white focus:text-black">
    Skip to main content
</a>
```

---

## 13. Tailwind Configuration

Using Tailwind v4 with CSS-first configuration. No `tailwind.config.js`.
All design tokens defined in `assets/src/main.css` using `@theme`.

```css
/* assets/src/main.css */
@import "tailwindcss";

@theme {
  /* Colors — overridden at runtime by tts_output_css_vars() in PHP */
  --color-brand-primary:   var(--tts-color-primary,   #1a1a2e);
  --color-brand-secondary: var(--tts-color-secondary, #ffffff);
  --color-brand-accent:    var(--tts-color-accent,    #4ade80);

  /* Typography */
  --font-sans:   var(--tts-font-sans,   'DM Sans', ui-sans-serif, system-ui, sans-serif);
  --font-serif:  var(--tts-font-serif,  'Playfair Display', ui-serif, Georgia, serif);

  /* ─── Container widths (layout constraints — independent of breakpoints) ─── */
  --width-container:       1280px;   /* Standard content sections */
  --width-container-prose: 800px;    /* Hero text, titles, readable copy blocks */
  --width-container-wide:  1440px;   /* Full-bleed sections with inner content */

  /* ─── Breakpoints (viewport thresholds only — never used as widths) ─────── */
  --breakpoint-sm:   480px;    /* Large phones */
  --breakpoint-md:   768px;    /* Tablets */
  --breakpoint-lg:   1024px;   /* Small desktops */
  --breakpoint-xl:   1280px;   /* Full desktop */
}

/* Container widths and breakpoints are intentionally different values and serve
   different purposes. Never use a breakpoint value as a container width or vice versa. */
```

### Brand colors from PHP → CSS → Tailwind

```php
// inc/setup.php or inc/enqueue.php
function tts_output_css_vars(): void {
    $primary   = tts_get_option( 'tts_color_primary' )   ?: '#1a1a2e';
    $secondary = tts_get_option( 'tts_color_secondary' ) ?: '#ffffff';
    $accent    = tts_get_option( 'tts_color_accent' )    ?: '#4ade80';
    printf(
        '<style>:root { --tts-color-primary: %s; --tts-color-secondary: %s; --tts-color-accent: %s; }</style>',
        esc_attr( $primary ),
        esc_attr( $secondary ),
        esc_attr( $accent )
    );
}
add_action( 'wp_head', 'tts_output_css_vars', 1 );
```

### Container pattern
Use consistent container classes everywhere. Define once:
```css
.tts-container       { max-width: var(--width-container); margin-inline: auto; padding-inline: 1.5rem; }
.tts-container-prose { max-width: var(--width-container-prose); margin-inline: auto; padding-inline: 1.5rem; }
.tts-container-wide  { max-width: var(--width-container-wide); margin-inline: auto; padding-inline: 1.5rem; }
```
- Hero text, page titles → `tts-container-prose` (narrow, centered, readable)
- Standard content sections → `tts-container` (1280px, comfortable)
- Full-bleed sections with inner content → `tts-container-wide`
- Never deviate from these three. Never set ad-hoc `max-width` values in templates.

### Typography scale (responsive)
Use `clamp()` for fluid type. Set once in `main.css`, use via Tailwind utilities.
**Minimum font size: 18px (1.125rem) everywhere.** No exceptions.
Use font weight, color, or letter-spacing to create hierarchy — never smaller sizes.

```css
@theme {
  --text-hero:   clamp(2.5rem, 5vw, 4.5rem);
  --text-h1:     clamp(2rem, 4vw, 3.5rem);
  --text-h2:     clamp(1.5rem, 3vw, 2.25rem);
  --text-h3:     clamp(1.25rem, 2vw, 1.75rem);
  --text-body:   clamp(1.125rem, 1.5vw, 1.25rem);  /* min 18px */
  --text-small:  1.125rem;                           /* min 18px — never below */
}
```

---

## 14. Accessibility Standards

Target: **WCAG 2.1 AA** compliance on every template.

### Required on every page
- Skip navigation link (`template-parts/global/skip-nav.php`) — first element in body
- `<main id="main-content">` wrapping all page content
- `lang` attribute on `<html>` element
- Page `<title>` unique per page
- All images: meaningful `alt` text or `alt=""` for decorative images
- Color contrast: minimum 4.5:1 for body text, 3:1 for large text and UI components
- Focus indicators visible on all interactive elements (never `outline: none` without replacement)
- Keyboard navigation: all interactive elements reachable and operable via keyboard

### ARIA landmarks
```html
<header role="banner">
<nav role="navigation" aria-label="Primary navigation">
<main id="main-content" role="main">
<aside role="complementary">
<footer role="contentinfo">
```

### Interactive elements
- All buttons use `<button>` (not `<div>` or `<span>`)
- All links use `<a href>` with meaningful text (not "click here")
- Icon-only buttons: add `aria-label="Description"` and `aria-hidden="true"` on the icon SVG
- Form fields: every `<input>` has an associated `<label>` (not just placeholder)
- Required fields: `aria-required="true"` attribute

### Accordion / toggle patterns (FAQs)
```html
<button aria-expanded="false" aria-controls="faq-1">Question text</button>
<div id="faq-1" hidden>Answer text</div>
```
Toggle `aria-expanded` and `hidden` attribute via JS on click.

### Mobile tap targets
Minimum 44×44px for all tappable elements.

### Reduced motion / motion sensitivity
Respect `prefers-reduced-motion` at the CSS level automatically.
Also provide an explicit toggle in Admin Options Tab 01 (`tts_reduce_motion` checkbox)
that adds a `data-reduce-motion` attribute to `<html>` for JS-driven animations.

```css
/* In main.css — always present, non-negotiable */
@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
    scroll-behavior: auto !important;
  }
}

/* Also target data attribute set by PHP/JS for admin toggle */
[data-reduce-motion="true"] *,
[data-reduce-motion="true"] *::before,
[data-reduce-motion="true"] *::after {
  animation-duration: 0.01ms !important;
  animation-iteration-count: 1 !important;
  transition-duration: 0.01ms !important;
  scroll-behavior: auto !important;
}
```

```php
// Output data attribute on <html> in inc/setup.php
function tts_html_attrs( string $output ): string {
    if ( tts_get_option( 'tts_reduce_motion' ) ) {
        $output .= ' data-reduce-motion="true"';
    }
    return $output;
}
add_filter( 'language_attributes', 'tts_html_attrs' );
```

In JS (`app.js`), also check and apply:
```js
if ( document.documentElement.dataset.reduceMotion === 'true'
  || window.matchMedia('(prefers-reduced-motion: reduce)').matches ) {
  document.documentElement.dataset.reduceMotion = 'true';
}
```

---

## 15. Performance Standards

### Script loading
- All non-critical JS: `defer` or `in_footer = true`
- No render-blocking scripts in `<head>` except critical inline CSS vars
- Conditionally enqueue scripts only on pages that use them:

```php
// Only load booking embed script on pages with booking template
if ( is_page_template( 'templates/template-home.php' ) && tts_is_profile( 'booking' ) ) {
    wp_enqueue_script( 'tts-booking', ... );
}
```

### Asset strategy
- Critical CSS: inlined via `wp_head` for above-the-fold (color vars, font-face)
- Everything else: loaded from compiled Vite dist
- Fonts: preloaded in `<head>` with `rel="preload"` for primary typeface
- No Google Fonts runtime load — use `@font-face` from local files or preconnect

### WordPress query hygiene
- Always use `wp_reset_postdata()` after custom `WP_Query` loops
- Limit `posts_per_page` on feed sections (use 3–6, not -1)
- Use `'no_found_rows' => true` on display-only queries that don't paginate
- Never use `query_posts()`

### Image performance
- Always use `loading="lazy"` except hero/above-fold images (`loading="eager"`)
- Always output via `wp_get_attachment_image()` for native `srcset`
- Register appropriate image sizes per component (see Section 15)

---

## 16. Image Handling

### Font pairings by profile

Two curated pairings, assigned as profile defaults in Tab 01. Overridable per site.
Both self-hosted (woff2, latin subset, variable fonts in `assets/fonts/`).
**Sans-serif only — no serif font may appear in any pairing or fallback stack.**
No fonts below 18px anywhere in the theme.

| Pairing | Heading Font | Body Font | Profiles |
|---------|-------------|-----------|---------|
| A — Editorial | Manrope (700) | Manrope | Booking, Local Business, Sales, Directory |
| B — Expressive (default) | Archivo (850–900, ALL CAPS) | Manrope | Creative, Venture, Events, Community |

Load via `@font-face` with `font-display: swap`. Preload primary heading font in `<head>`.
Define in `@theme` block in `main.css`:
```css
@theme {
  --font-heading-editorial:   'Manrope', ui-sans-serif, system-ui, sans-serif;
  --font-body-editorial:      'Manrope', ui-sans-serif, system-ui, sans-serif;
  --font-heading-expressive:  'Archivo', ui-sans-serif, system-ui, sans-serif;
  --font-body-expressive:     'Manrope', ui-sans-serif, system-ui, sans-serif;
}
```

Active pairing output as CSS vars via PHP (same pattern as brand colors):
```php
function tts_output_font_vars(): void {
    $pairing = tts_get_option( 'tts_font_pairing' ) ?: 'expressive';
    $headings = $pairing === 'expressive'
        ? "'Archivo', ui-sans-serif, system-ui, sans-serif"
        : "'Manrope', ui-sans-serif, system-ui, sans-serif";
    $body = "'Manrope', ui-sans-serif, system-ui, sans-serif";
    printf(
        '<style>:root { --tts-font-heading: %s; --tts-font-body: %s; }</style>',
        $headings,   // fixed literals — never esc_attr() inside <style>
        $body
    );
}
add_action( 'wp_head', 'tts_output_font_vars', 1 );
```

**Minimum font size: 18px.** Never set font-size below 18px anywhere in the theme.
Body text default: 18px (1.125rem). Small/caption text: 18px minimum — use weight or
color to differentiate hierarchy, not smaller sizes.

---

### Registered image sizes
Define in `inc/setup.php` via `add_image_size()`:

| Size name | Width | Height | Crop | Used in |
|-----------|-------|--------|------|---------|
| `tts-hero` | 1920 | 800 | crop | Hero backgrounds |
| `tts-feature` | 1280 | 720 | crop | Feature sections, wide cards |
| `tts-card` | 600 | 400 | crop | Service/team/gallery cards |
| `tts-thumb` | 300 | 300 | crop | Testimonial avatars, small thumbs |
| `tts-logo` | 400 | 200 | false | Logo (no crop, preserve ratio) |
| `tts-og` | 1200 | 630 | crop | Open Graph social share image |

### Social/OG image requirements
- Always register `tts-og` (1200×630) for all featured images
- Output in `<head>` via `inc/seo.php`
- Format: JPEG preferred for photos, PNG for graphics
- Must exist on every page for social sharing (fall back to site logo if no featured image)

### Alt text
- Pull from WP attachment alt field first: `get_post_meta( $id, '_wp_attachment_image_alt', true )`
- Fall back to post title
- Decorative images: empty `alt=""` — never omit the attribute
- Never generate alt text programmatically with fake content

### PDF uploads
- Store attachment ID in meta
- Retrieve URL via `wp_get_attachment_url( $id )`
- Validate MIME type on save: `get_post_mime_type( $id ) === 'application/pdf'`

---

## 17. Responsive Design

### Breakpoints — 4 maximum
| Token | Value | Target |
|-------|-------|--------|
| `sm` | 480px | Large phones |
| `md` | 768px | Tablets |
| `lg` | 1024px | Small desktops |
| `xl` | 1280px | Full desktop |

Never add breakpoints beyond these four. Never use arbitrary pixel values in media queries.

### Column stacking
- Mobile-first: single column by default
- Use Tailwind responsive prefixes: `flex-col md:flex-row`
- Columns always stack vertically on mobile — never horizontal scroll
- 2-column layouts: `flex flex-col md:flex-row`
- 3-column card grids: `flex flex-col sm:flex-row sm:flex-wrap` with `w-full sm:w-1/2 lg:w-1/3`

### Layout approach
- **Flexbox first** — use for all layout patterns
- **CSS Grid only when** content is genuinely two-dimensional (e.g. a masonry-style gallery, a calendar grid)
- Never use grid for simple single-axis layouts

### Container widths
Always use the three container classes defined in Section 12.
Never set ad-hoc `max-width` values in template files.

### Text responsiveness
Use `clamp()` fluid type scale defined in Section 12. Never set fixed `font-size` on headings.

---

## 18. Navigation & Menus

### Registered menus
```php
register_nav_menus([
    'primary'     => __( 'Primary Navigation', 'tts-theme' ),
    'footer'      => __( 'Footer Navigation', 'tts-theme' ),
    'footer-legal'=> __( 'Footer Legal Links', 'tts-theme' ),
]);
```

Three menus only. Do not register additional menus without explicit instruction.

- **Primary** — main header nav
- **Footer** — main footer links (about, services, contact, etc.)
- **Footer Legal** — privacy policy, terms, etc. (minimal, bottom of footer)

### Author archives
Disabled completely. See CPT section.

### Disabled / redirected archives
The following must 404 or redirect to home. Configure in `inc/setup.php`:
- Author archives
- Date archives
- Any CPT archive without a matching `archive-tts_*.php` template
- Tag archives (unless explicitly used)

---

## 19. Header & Footer Layouts

Selected via Admin Options Tab 01 dropdowns.
`header.php` and `footer.php` load the correct layout partial via `tts_get_option()`.

```php
// template-parts/global/header.php
$layout = tts_get_option( 'tts_header_layout' ) ?: 'standard';
get_template_part( 'template-parts/global/header-' . sanitize_key( $layout ) );
```

### header-standard.php
- Logo left, primary nav center or right, CTA button far right
- Above-nav banner slot (loads `banner.php` if active)
- Sticky on scroll

### header-minimal.php
- Logo centered or left, no nav (or condensed nav), single CTA
- For landing pages, splash, or minimal profile sites

### footer-standard.php
- Logo + tagline column, nav columns (from Footer menu), contact info, social icons
- Footer Legal menu at very bottom
- Editable via Footer menu (WP admin > Appearance > Menus) + Admin Options Tab 02

### footer-minimal.php
- Single row: logo, copyright, legal links
- For landing pages or minimal profiles

Footer copy/tagline editable via Admin Options Tab 02 (`tts_tagline`).
Footer copyright line: auto-generates current year + business name from options.
Do not hard-code any text in footer partials.

---

## 20. Admin UX Standards

### Meta box layout
- Use two-column layout for meta boxes wherever possible using CSS grid in `admin.css`
- Wide text fields (textarea) span full width
- Short fields (text, url, number, date) render in two columns side by side
- Image pickers render as a thumbnail button (WP media uploader), not a text field
- Each field has a visible `<label>` and a `<p class="description">` hint indicating
  where it appears on the frontend

### Admin label pattern
Every meta field label must include a frontend location hint:
```
Hero Headline
Where it appears: Large text in the Hero section at the top of the homepage
```

### Post publish requirements
For CPTs with required fields (see Section 9): display a dismissible admin notice
on save if required fields are empty. Use `WP_Error`-style notice — do not block save.

```php
add_action( 'admin_notices', function() {
    if ( get_current_screen()->post_type !== 'tts_event' ) return;
    if ( ! get_post_meta( get_the_ID(), 'event_date', true ) ) {
        echo '<div class="notice notice-warning"><p>
            <strong>Event Date</strong> is recommended before publishing this event.
        </p></div>';
    }
});
```

### Admin dashboard
Customize via `inc/dashboard.php`:
- Remove default WP dashboard widgets (Quick Draft, WP News, etc.)
- Add welcome widget with site profile, quick links to CPTs, and Admin Options
- Add "Content Status" widget showing count of each CPT's published posts
- Add "Quick Setup Checklist" widget that checks key options are filled

```php
function tts_setup_dashboard(): void {
    remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
    remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
    remove_meta_box( 'dashboard_site_health', 'dashboard', 'normal' );
    // Add custom widgets
    wp_add_dashboard_widget( 'tts_welcome', 'Site Overview', 'tts_dashboard_welcome' );
    wp_add_dashboard_widget( 'tts_status', 'Content Status', 'tts_dashboard_status' );
    wp_add_dashboard_widget( 'tts_checklist', 'Setup Checklist', 'tts_dashboard_checklist' );
}
add_action( 'wp_dashboard_setup', 'tts_setup_dashboard' );
```

### Admin menu organization
Group CPTs under a single top-level menu item "Content":
```
Content (top-level)
├── Services
├── Team Members
├── Testimonials
├── Gallery
├── FAQs
├── Demo / Video
├── Events (if profile)
├── Locations (if profile)
└── Press Items (if profile)

Updates → replaces native Posts in menu
Site Settings → Admin Options page
```

Remove from admin menu (not relevant to clients):
- Comments (unless explicitly needed)
- Tools (collapse or hide)
- Unnecessary WP default submenus

---

## 21. SEO, AIO & Social Optimization

All handled in `inc/seo.php`. No SEO plugin dependency.

### Meta tags (output in wp_head)
- `<title>`: page title | site name (unique per page)
- `<meta name="description">`: from post excerpt or custom meta description field
- `<meta name="robots">`: respect WP's built-in `blog_public` setting
- Canonical URL: `<link rel="canonical">`

### Open Graph (social)
```html
<meta property="og:title" content="..." />
<meta property="og:description" content="..." />
<meta property="og:image" content="..." />  <!-- tts-og size: 1200×630 -->
<meta property="og:url" content="..." />
<meta property="og:type" content="website" />
<meta property="og:site_name" content="..." />
```

### Twitter/X Card
```html
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="..." />
<meta name="twitter:description" content="..." />
<meta name="twitter:image" content="..." />
```

### Schema.org structured data (JSON-LD)
Output in `<head>` via `inc/seo.php`. Use `searchfit-seo:schema-markup` skill for specifics.

Base schema on every page:
```json
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "",
  "url": "",
  "logo": "",
  "contactPoint": { "@type": "ContactPoint", "telephone": "", "email": "" },
  "sameAs": ["social URLs array"]
}
```

Profile-specific schema:
- Booking / Local: `LocalBusiness` with `openingHours`, `geo`, `address`
- Events: `Event` on event singles
- Creative: `Person` or `Organization` with `portfolio`
- Community: `NGO` or `Organization`
- Venture/Sales: `Organization` with `description`

### Local SEO (Booking, Local Business, Directory, Events)
- `LocalBusiness` schema with `address`, `geo` (lat/lng fields in Tab 02 or location CPT)
- `openingHoursSpecification` from hours field
- NAP (Name, Address, Phone) consistent across footer, contact page, and schema
- Add lat/lng fields to Admin Options Tab 02 for geo schema

### Google Tag Manager
GTM is the preferred tracking implementation. Direct GA4 script is a fallback only.
If `tts_gtm_id` is set, it takes precedence and GA should be configured inside GTM.

```php
// inc/seo.php
function tts_gtm_head(): void {
    $gtm_id = tts_get_option( 'tts_gtm_id' );
    if ( ! $gtm_id ) return;
    printf(
        "<!-- Google Tag Manager -->\n<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','%s');</script>\n<!-- End Google Tag Manager -->",
        esc_attr( $gtm_id )
    );
}
add_action( 'wp_head', 'tts_gtm_head', 2 );

// Fallback GA4 — only fires when no GTM ID is set
function tts_ga_head(): void {
    if ( tts_has_option( 'tts_gtm_id' ) ) return;
    $ga_id = tts_get_option( 'tts_ga_id' );
    if ( ! $ga_id ) return;
    printf(
        '<script async src="https://www.googletagmanager.com/gtag/js?id=%1$s"></script>
<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag("js",new Date());gtag("config","%1$s");</script>',
        esc_attr( $ga_id )
    );
}
add_action( 'wp_head', 'tts_ga_head', 3 );

// GTM noscript — must fire immediately after <body> opens
function tts_gtm_body(): void {
    $gtm_id = tts_get_option( 'tts_gtm_id' );
    if ( ! $gtm_id ) return;
    printf(
        '<!-- GTM noscript --><noscript><iframe src="https://www.googletagmanager.com/ns.html?id=%s" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>',
        esc_attr( $gtm_id )
    );
}
add_action( 'wp_body_open', 'tts_gtm_body' );
```

`wp_body_open()` must be called at the top of `template-parts/global/header.php`
immediately after `<body>`. This is required for GTM noscript — do not omit it.

### Sitemap
WordPress 5.5+ generates `/wp-sitemap.xml` automatically. Customize inclusions:

```php
// inc/seo.php — exclude CPTs with no public archive or thin content
add_filter( 'wp_sitemaps_post_types', function( array $post_types ): array {
    unset( $post_types['tts_gallery'] );   // No meaningful standalone page
    unset( $post_types['tts_testimonial'] ); // No public single
    unset( $post_types['tts_faq'] );       // No public single
    return $post_types;
});

// Exclude empty or non-public taxonomies from sitemap
add_filter( 'wp_sitemaps_taxonomies', function( array $taxonomies ): array {
    unset( $taxonomies['post_tag'] );
    unset( $taxonomies['post_format'] );
    return $taxonomies;
});

// Disable author/user sitemap
add_filter( 'wp_sitemaps_add_provider', function( $provider, string $name ) {
    return $name === 'users' ? false : $provider;
}, 10, 2 );
```

### AI Optimization (AIO)

**llms.txt** — served at `yourdomain.com/llms.txt` via a rewrite rule:
```
# [Business Name]
[Tagline]

## About
[Brief business description]

## Services
[List of services]

## Contact
[Contact info]
```
Register the route in `inc/seo.php` using `add_rewrite_rule()` + `template_redirect`.

**Additional AIO meta:**
- `<meta name="description">` — clear, factual, entity-rich
- FAQ schema on FAQ sections
- Clear heading hierarchy (one `<h1>` per page, logical `h2`/`h3` structure)
- Structured `<address>` element for contact info

---

## 22. REST API & n8n Integration

Handled in `inc/rest-api.php`.

### Authentication
Use WordPress Application Passwords (WP 5.6+) for n8n authentication.
Store credentials in n8n credential vault — never in theme files.

### Custom endpoints for content injection
Register endpoints for intake form → content push during site setup:

```php
register_rest_route( 'tts/v1', '/intake', [
    'methods'             => 'POST',
    'callback'            => 'tts_handle_intake',
    'permission_callback' => function() {
        return current_user_can( 'edit_posts' );
    },
    'args' => [
        'site_profile' => [ 'type' => 'string', 'sanitize_callback' => 'sanitize_key' ],
        'business_name'=> [ 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field' ],
        // ... other intake fields
    ],
]);
```

### Endpoint map (minimum viable for intake-to-launch)

| Endpoint | Method | Purpose |
|----------|--------|---------|
| `/tts/v1/intake` | POST | Push intake form data to Admin Options |
| `/tts/v1/services` | POST | Create Service CPT entries |
| `/tts/v1/team` | POST | Create Team Member CPT entries |
| `/tts/v1/testimonials` | POST | Create Testimonial CPT entries |
| `/wp/v2/media` | POST | Upload images from intake (native WP endpoint) |

### Disabling unused REST routes
Disable REST API for unauthenticated users if not needed publicly:
```php
add_filter( 'rest_authentication_errors', function( $result ) {
    if ( ! empty( $result ) ) return $result;
    if ( ! is_user_logged_in() ) {
        return new WP_Error( 'rest_not_logged_in', 'Authentication required.', [ 'status' => 401 ] );
    }
    return $result;
});
```
Only apply this if public REST access is not needed (e.g. no public-facing API consumers).

---

## 23. Shortcodes & Embeds

Registered in `inc/shortcodes.php`.

### Embed block pattern
Used in `embed-block.php` partial and any `_embed_block` meta field.
Accept: shortcodes, raw embed URLs (oEmbed), raw HTML iframe codes, or plain text.
Always pass through `do_shortcode()` then `wp_oembed_get()` fallback.

```php
function tts_render_embed( string $content ): string {
    $content = do_shortcode( $content );
    // If content looks like a bare URL, try oEmbed
    if ( filter_var( trim($content), FILTER_VALIDATE_URL ) ) {
        $oembed = wp_oembed_get( trim($content) );
        if ( $oembed ) return $oembed;
    }
    return wp_kses_post( $content );
}
```

### Theme shortcodes to register
```
[tts_cta label="" url="" style="primary|secondary"]
[tts_button label="" url="" style="primary|secondary"]
[tts_pdf_download id="" label="Download PDF"]
```

Do not over-engineer shortcodes. Simple, single-purpose, documented.

---

## 24. Coming Soon (Splash) & Maintenance Mode

### Splash page (template-splash.php)
A standard page template assigned to a specific page in WP admin.
No special routing — just a page set as the front page or linked directly.
Has its own meta fields (Section 10) and no header/footer if desired (can extend template-blank.php pattern).
Includes `splash_embed_block` for waitlist forms or custom embeds.

### Maintenance mode (inc/maintenance.php)
Toggle via Admin Options Tab 06: `tts_maintenance_active` checkbox.
When active, non-admin visitors are redirected to a maintenance page and shown HTTP 503.
Logged-in admins see the site normally.

```php
function tts_maintenance_mode(): void {
    if ( ! tts_maintenance_active() ) return;
    if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) return;
    if ( is_admin() ) return;

    $message = tts_get_option( 'tts_maintenance_message' )
               ?: tts_placeholder( 'Maintenance message' );

    status_header( 503 );
    header( 'Retry-After: 3600' );
    // Render simple inline maintenance template — no theme dependencies
    include get_template_directory() . '/templates/template-maintenance-static.php';
    exit;
}
add_action( 'template_redirect', 'tts_maintenance_mode' );
```

`template-maintenance-static.php` is a standalone HTML file with minimal inline styles.
No dependency on compiled assets or theme functions — it must work even if build is broken.

---

## 25. Environment Variables

Store sensitive values in `wp-config.php` on each environment.
`.env.example` is committed to the repo with all keys and empty values as a reference.
`.env` is never used directly — this is WordPress, not a Node app. All env values
go through `wp-config.php` constants or `putenv()`.

### .env.example (committed — no real values)
```
# Copy values into wp-config.php for each environment. Never commit real values.

TTS_DEV=false
TTS_MAPS_API_KEY=
TTS_BOOKING_API_KEY=
TTS_GTM_ID=
TTS_GA_ID=
TTS_PIXEL_ID=
TTS_N8N_WEBHOOK_SECRET=
TTS_REST_API_ENABLED=true
```

### wp-config-sample.php additions (local / dev)
```php
// ─── TTS Theme constants ───────────────────────────────────────────────────
define( 'TTS_DEV',              true );   // Enables Vite dev server asset loading
define( 'TTS_REST_API_ENABLED', true );   // Enables custom REST endpoints

// Debug (dev only — never true on production)
define( 'WP_DEBUG',         true );
define( 'WP_DEBUG_LOG',     true );
define( 'WP_DEBUG_DISPLAY', false );
define( 'SCRIPT_DEBUG',     true );

// Env vars (dev values — replace per environment)
putenv( 'TTS_MAPS_API_KEY=your-dev-key-here' );
putenv( 'TTS_BOOKING_API_KEY=your-dev-key-here' );
putenv( 'TTS_N8N_WEBHOOK_SECRET=your-dev-secret-here' );
// ──────────────────────────────────────────────────────────────────────────
```

### wp-config additions (production)
```php
define( 'TTS_DEV',              false );
define( 'TTS_REST_API_ENABLED', true );
define( 'WP_DEBUG',             false );
define( 'WP_DEBUG_LOG',         false );
define( 'WP_DEBUG_DISPLAY',     false );

putenv( 'TTS_MAPS_API_KEY=live-key' );
putenv( 'TTS_BOOKING_API_KEY=live-key' );
putenv( 'TTS_N8N_WEBHOOK_SECRET=live-secret' );
```

### Reading env vars in PHP
```php
$maps_key    = getenv( 'TTS_MAPS_API_KEY' );
$n8n_secret  = getenv( 'TTS_N8N_WEBHOOK_SECRET' );
```

Never store API keys in Admin Options or post meta.
Never commit real values to version control.
Always add new env vars to `.env.example` when introducing them.

---

## 26. Coding Standards

- **PHP 8.2+** — use typed parameters and return types
- **WordPress Coding Standards** throughout (enforced by PHPCS — see Section 27)
- **Prefix everything**: functions, hooks, CPT slugs, option keys, meta keys → `tts_`
- **Escape all output**: `esc_html()`, `esc_url()`, `esc_attr()`, `wp_kses_post()`
- **Sanitize all input**: `sanitize_text_field()`, `esc_url_raw()`, `absint()`, `wp_kses_post()`
- **Nonces** on all forms and meta box saves: `wp_nonce_field()` + `check_admin_referer()`
- **Capability checks** on all admin actions: `current_user_can('edit_posts')` minimum
- **Never use** `extract()`, variable variables, or `query_posts()`
- **Always** `wp_reset_postdata()` after custom `WP_Query` loops
- **i18n**: all user-facing strings wrapped in `__()` / `esc_html__()` with `'tts-theme'` domain
- **No inline styles** in templates — use Tailwind utility classes or CSS custom properties
- **No jQuery** unless a WP core dependency requires it — vanilla JS only
- **No hard-coded URLs, phone numbers, addresses, or brand copy** in template files

### Theme versioning
```php
// functions.php — define once, bump on every significant change and every fork
define( 'TTS_THEME_VERSION', '1.0.0' );
define( 'TTS_THEME_DIR',     get_template_directory() );
define( 'TTS_THEME_URI',     get_template_directory_uri() );
```
Maintain a `CHANGELOG.md` in the theme root. On fork: bump version, add entry.

### Comments — disabled everywhere, always
```php
// inc/setup.php
// Disable comments on all post types and pages
add_action( 'init', function(): void {
    foreach ( get_post_types() as $post_type ) {
        if ( post_type_supports( $post_type, 'comments' ) ) {
            remove_post_type_support( $post_type, 'comments' );
            remove_post_type_support( $post_type, 'trackbacks' );
        }
    }
});

// Close comments on all existing posts
add_filter( 'comments_open',    '__return_false', 20, 2 );
add_filter( 'pings_open',       '__return_false', 20, 2 );
add_filter( 'comments_array',   '__return_empty_array', 10, 2 );

// Remove comments from admin menu
add_action( 'admin_menu', function(): void {
    remove_menu_page( 'edit-comments.php' );
});

// Remove comments from admin bar
add_action( 'init', function(): void {
    if ( is_admin_bar_showing() ) {
        remove_action( 'admin_bar_menu', 'wp_admin_bar_comments_menu', 60 );
    }
});
```
Do not register `comments.php`. Do not add comment template partials.
This is permanent and applies to all CPTs and native post types.

### i18n / localization
Theme is English-only at launch but fully localization-ready.
```php
// inc/setup.php
function tts_load_textdomain(): void {
    load_theme_textdomain( 'tts-theme', TTS_THEME_DIR . '/languages' );
}
add_action( 'after_setup_theme', 'tts_load_textdomain' );
```
All user-facing strings must use translation functions — even if never translated,
this enables future localization without a code pass.
Generate `.pot` file via WP-CLI before delivery: `wp i18n make-pot . languages/tts-theme.pot`

### Meta box nonce boilerplate
Every meta box save must follow this exact pattern — no exceptions:

```php
// In save_post callback for every meta box
function tts_save_service_meta( int $post_id ): void {
    // 1. Verify nonce
    if ( ! isset( $_POST['tts_service_nonce'] )
      || ! wp_verify_nonce( sanitize_key( $_POST['tts_service_nonce'] ), 'tts_save_service_meta' ) ) {
        return;
    }
    // 2. Check auto-save
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    // 3. Check capability
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;
    // 4. Check post type
    if ( get_post_type( $post_id ) !== 'tts_service' ) return;

    // 5. Sanitize and save each field
    if ( isset( $_POST['service_price'] ) ) {
        update_post_meta( $post_id, 'service_price', sanitize_text_field( wp_unslash( $_POST['service_price'] ) ) );
    }
    // ... repeat for each field
}
add_action( 'save_post', 'tts_save_service_meta' );
```

### Meta box template detection gotcha
`get_page_template_slug()` is unreliable in `add_meta_boxes` for new unsaved posts
because the template hasn't been saved yet. Use this pattern instead:

```php
add_action( 'add_meta_boxes', function(): void {
    // Use JS to show/hide meta boxes based on template dropdown selection
    // Register all page meta boxes on all pages, then hide irrelevant ones via JS
    add_meta_box( 'tts_home_meta', 'Home Page Settings', 'tts_home_meta_cb', 'page', 'normal' );
} );

// In admin.js — show/hide meta boxes when template dropdown changes
document.getElementById('page_template')?.addEventListener('change', function() {
    const template = this.value;
    document.getElementById('tts_home_meta')?.closest('.postbox-container')
        .style.display = template.includes('template-home') ? '' : 'none';
});
```

Alternatively, rely on the saved `_wp_page_template` meta on subsequent edits:
```php
add_action( 'add_meta_boxes_page', function( WP_Post $post ): void {
    $template = get_post_meta( $post->ID, '_wp_page_template', true );
    if ( $template === 'templates/template-home.php' ) {
        add_meta_box( 'tts_home_meta', 'Home Page Settings', 'tts_home_meta_cb', 'page' );
    }
});
```
Both approaches are valid. Use the JS approach for a seamless experience on new posts.

### Forms
Do not scaffold form HTML or PHP handling. Forms are provided by the client's
chosen plugin (Contact Form 7, Gravity Forms, WPForms, Fluent Forms, etc.) and
inserted via shortcode into `_embed_block` meta fields or template embed slots.
The theme renders shortcodes via `do_shortcode()` — nothing more.

If a sample/starter form is needed for a specific client, generate a CF7 or
Gravity Forms import JSON/XML file separately when requested during that client
build — not as part of the base theme.

---

## 27. PHP_CodeSniffer Setup

### Installation
```bash
composer require --dev squizlabs/php_codesniffer wp-coding-standards/wpcs
./vendor/bin/phpcs --config-set installed_paths vendor/wp-coding-standards/wpcs
```

### phpcs.xml (theme root)
```xml
<?xml version="1.0"?>
<ruleset name="TTS Theme">
    <description>TTS WordPress Theme coding standards</description>

    <file>.</file>
    <exclude-pattern>*/vendor/*</exclude-pattern>
    <exclude-pattern>*/node_modules/*</exclude-pattern>
    <exclude-pattern>*/assets/dist/*</exclude-pattern>

    <arg name="basepath" value="."/>
    <arg name="extensions" value="php"/>
    <arg value="sp"/>

    <rule ref="WordPress-VIP-Go"/>

    <!-- Allow short array syntax -->
    <rule ref="Generic.Arrays.DisallowLongArraySyntax"/>

    <!-- Theme-specific prefix -->
    <rule ref="WordPress.NamingConventions.PrefixAllGlobals">
        <properties>
            <property name="prefixes" type="array" value="tts"/>
        </properties>
    </rule>

    <rule ref="WordPress.WP.I18n">
        <properties>
            <property name="text_domain" type="array" value="tts-theme"/>
        </properties>
    </rule>
</ruleset>
```

### Running PHPCS
```bash
# Check all files
./vendor/bin/phpcs

# Auto-fix what's fixable
./vendor/bin/phpcbf

# Check single file
./vendor/bin/phpcs inc/helpers.php
```

Run PHPCS before every client fork delivery. Fix all errors; review warnings.

---

## 28. What NOT to Do

- Do not use ACF or any custom field plugin
- Do not use the WordPress Customizer for anything
- Do not use `query_posts()` — ever
- Do not put business logic in template files — use helpers
- Do not register CPTs, meta boxes, or options inside `functions.php` directly
- Do not hard-code phone numbers, URLs, addresses, or copy in templates
- Do not store image URLs in meta — always store attachment IDs
- Do not add profile conditionals outside `template-home.php` and `hero.php`
- Do not build repeater fields — use CPTs for list-based content
- Do not use jQuery for tab switching or simple UI interactions
- Do not set ad-hoc `max-width` values — use the three container classes only
- Do not add breakpoints beyond the four defined in Section 13
- Do not use CSS Grid for single-axis layouts — use flexbox
- Do not use `outline: none` without providing a visible focus replacement
- Do not use `loading="lazy"` on hero/above-fold images
- Do not omit `alt` attributes on any `<img>` element
- Do not commit `.env` or any file containing real API keys or credentials
- Do not use real-sounding placeholder copy — use `tts_placeholder()` always
- Do not make URL fields assume full external URLs — use `tts_the_url()` always
- Do not leave author archives, date archives, or untemplated CPT archives live
- Do not enqueue scripts globally — scope to pages that need them
- Do not create new files without checking if an existing partial covers the need
- Do not scaffold form HTML or PHP — forms come from plugins via shortcode only
- Do not enable or reference comments anywhere — ever
- Do not set any font size below 18px (1.125rem)
- Do not add new env vars without also adding them to `.env.example`
- Do not skip `wp_body_open()` in header.php — GTM noscript depends on it

---

## 29. Forking for a Client Site

### Find-and-replace strings — run across ALL .php, .css, .js, .md files
| Find (case-sensitive) | Replace with |
|----------------------|-------------|
| `tts_` | `clientslug_` |
| `tts-theme` | `clientslug-theme` |
| `TTS_` | `CLIENTSLUG_` |
| `TTS Theme` | `Client Name` |
| `Text Domain: tts-theme` | `Text Domain: clientslug-theme` |
| `Version: 1.0.0` | bump to appropriate version |
| `value="tts"` (in phpcs.xml) | `value="clientslug"` |

After the automated replace, manually verify:
- `style.css` theme header (Theme Name, Author, Description, Version, Text Domain)
- `CHANGELOG.md` — add fork entry
- Any inline HTML comments referencing TTS
- `phpcs.xml` prefix rule

### Fork delivery checklist
1. Duplicate theme folder → rename to `clientslug-theme`
2. Run all find-and-replace strings above
3. Update `CHANGELOG.md` — add fork entry with date and client name
4. Bump version in `style.css` and `functions.php` constant
5. Run `composer install` then `npm install`
6. Run `npm run build:prod` — must pass PHPCS clean
7. Set site profile in Admin Options → Tab 01
8. Fill out Admin Options Tabs 02–05 with client data from intake form
9. Push intake data via n8n → REST API to auto-populate CPTs
10. Upload logo + set `tts_logo_alt` (required)
11. Set brand colors, font pairing, header/footer layout in Tab 01
12. Assign page templates to pages and fill custom fields
13. Set up Appearance → Menus (Primary, Footer, Footer Legal)
14. Test all profile sections render correctly for active profile
15. Run `wp i18n make-pot . languages/clientslug-theme.pot`
16. Verify no `[PLACEHOLDER:` text visible anywhere on front end
17. Verify all images have alt text set in Media Library
18. Run Accessibility Checker — resolve all errors
19. Run Theme Check plugin — resolve all warnings
20. Check Core Web Vitals via PageSpeed Insights (LCP <2.5s, CLS <0.1, INP <200ms)
21. Verify GTM fires correctly in Google Tag Assistant
22. Verify `WP_DEBUG` is false on production server
23. Verify no real API keys committed to version control
24. **Deactivate dev-only plugins before handoff:**
    - [ ] Theme Check
    - [ ] Query Monitor
    - [ ] Accessibility Checker
    - [ ] Broken Link Checker

---

## 30. Session Startup Checklist

At the start of **every** Claude Code session:

- [ ] Read CLAUDE.md fully before writing any code
- [ ] Load the appropriate skill(s) for this session type (see Section 3)
- [ ] Identify which file(s) this session will work on
- [ ] Verify build order — do all dependencies exist before building dependents?
- [ ] Use `tts_` prefix on all new functions, hooks, option keys, meta keys
- [ ] Use `tts_get_option()` — never `get_option()` directly
- [ ] Use `tts_the_url()` — never assume URL field format
- [ ] Use `tts_placeholder()` — never write default copy in templates
- [ ] Use `tts_get_image()` — never retrieve image URLs from meta
- [ ] Use `tts_render_cta()` — never hard-code button pairs inline
- [ ] Follow nonce boilerplate exactly for any meta box save (Section 26)
- [ ] Escape all output before closing the session
- [ ] Check container classes — only the three defined in Section 13
- [ ] Check breakpoints — only the four defined in Section 13
- [ ] Verify no font size below 18px introduced
- [ ] Verify no jQuery added without justification
- [ ] Verify no profile conditionals added outside allowed files
- [ ] Verify no comments code, template, or functionality added
- [ ] Verify `wp_body_open()` called in header.php if modifying header
- [ ] Verify new env vars added to `.env.example` if introduced
