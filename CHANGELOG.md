# Changelog — Drum Study Theme

All notable changes to this theme are documented here.
On fork: bump version in `style.css` and `functions.php`, then add an entry below.

---

## [1.1.0] — 2026-07-02

### Changed
- Forked from TTS Starter Theme: completed the `tts_`/`TTS_` → `drumstudy_`/`DRUMSTUDY_` prefix rename across all functions, hooks, option keys, meta keys, CPT slugs, and REST namespace (`tts/v1` → `drumstudy/v1`); renamed `single-tts_*.php`/`archive-tts_*.php` files and `languages/tts-theme.pot`
- Special case: `tts_testimonial` renamed to `drumstudy_testim` (not `drumstudy_testimonial`) to stay within WordPress's 20-character post-type slug limit
- New bold rock/editorial design tokens (dark charcoal, brand blue accent, Oswald/Inter expressive pairing) replacing the generic starter palette
- Populated site content for The Drum Study (Jordan Cohen — Las Vegas drum lessons, booking profile): hero, bio, services, FAQs, gallery, About/Contact pages

### Added
- `template-parts/sections/intro.php` (Groove Assessment lead-capture) completing a previously-documented-but-unbuilt homepage pattern
- `template-parts/sections/gear-guide.php` (Beginner Starter Kit affiliate gear guide, 3 fixed slots)
- Solo-bio layout branch in `template-parts/sections/team.php` for single-instructor businesses

## [1.0.0] — 2026-06-14

### Added
- Initial complete build of TTS Starter Theme
- 8 site profiles: booking, local, creative, venture, sales, events, directory, community
- 9 Custom Post Types: tts_service, tts_testimonial, tts_team, tts_gallery, tts_faq, tts_event, tts_location, tts_press, tts_demo
- Admin Options (6 tabs): Identity, Business, Social, CTAs & Banner, Integrations, Profile Settings
- 16 meta box files covering all page templates and CPTs
- Tailwind CSS v4 via Vite 5 build (CSS-first @theme config, no tailwind.config.js)
- Profile-aware homepage (template-home.php) with 8 section orderings
- 10 page templates: home, about, contact, features, donate, portfolio, full-width, minimal, blank, splash
- 13 global template parts: skip-nav, header (standard/minimal), footer (standard/minimal), banner, nav, location, pagination, cta-strip
- 17 section partials: hero (profile-aware), services, testimonials, team, gallery, faqs, features, stats, hours-location, booking-embed, donate-embed, press-logos, video-demo, events-feed, updates-feed, locations-list, embed-block
- 10 card partials: service, testimonial, team, gallery, event, location, press, demo, update, search-result
- 7 CPT single templates, 4 CPT archive templates, plus single.php, archive.php, search.php, 404.php
- REST API endpoints (/tts/v1/intake, /services, /team, /testimonials) for n8n integration
- SEO: meta tags, Open Graph, Twitter Card, JSON-LD schema, GTM/GA4/Pixel, sitemap customization, llms.txt
- Maintenance mode (503 + standalone static template)
- Accessibility: WCAG 2.1 AA structure, skip nav, ARIA landmarks, reduced motion, 44px tap targets
- PHP_CodeSniffer with WordPress-VIP-Go ruleset
- Security: manage_options gating on intake endpoint, URL scheme allowlist, XSS defence in tts_the_url()

---

*Fork entries go above this line.*
