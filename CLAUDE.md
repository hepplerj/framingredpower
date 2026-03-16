# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Framing Red Power is a digital history project exploring the American Indian Movement's 1972 occupation of the Bureau of Indian Affairs and media coverage of the Trail of Broken Treaties. Built with Hugo (static site generator), migrated from a PHP-based site.

## Build Commands

```bash
# Development server
hugo server

# Full production build (text analysis + Hugo + Pagefind search index)
./build.sh

# Individual build steps:
uv run --with python-frontmatter scripts/analyze_text.py   # Generate text analysis JSON
hugo --minify                                                # Build static site
npx -y pagefind --site public                               # Generate search index
```

Requires Hugo v0.147.2+ (extended version). Python text analysis uses `uv` for dependency management.

## Architecture

### Content Sections

- `content/archive/` — 94 archival documents (newspapers, television, FBI, speeches/treaties), converted from TEI XML. Each has YAML frontmatter with `document_types` and `publication` taxonomies.
- `content/narrative/` — 13 analytical essay pages in 3 hierarchical sections (activism, AIM, Trail of Broken Treaties). Weight-based ordering with two-column layout (sidebar nav + content).
- `content/sources/` — Interactive browse tools (search, timeline, text analysis dashboard, maps). Each page uses a custom layout template.
- `content/about/` — About page.

### Layout System

Each content section has its own template directory under `layouts/`:

- `layouts/_default/baseof.html` — Base template wrapping all pages
- `layouts/archive/` — Document display with metadata box; `list.html` includes live filtering/sorting via `document-filter.js`
- `layouts/narrative/` — Two-column layout with hierarchical sidebar navigation (`narrative-nav.html` partial)
- `layouts/sources/` — Per-page custom templates (e.g., `text-analysis.html`, `timeline-modern.html`, `map-leaflet.html`)
- `layouts/partials/` — Shared components (head, header, navigation, footer, doc-nav, narrative-nav, lazy-image)

### Static Assets

- `static/css/modern.css` — Primary stylesheet using CSS custom properties (`--color-primary: #710100`, `--color-bg-body: #D6D1B1`, fonts: Crimson Text + Libre Baskerville)
- `static/js/` — jQuery 1.12.4, document filtering, site scripts
- `static/data/` — JSON/CSV data files for visualizations (text-analysis.json, kwic-index.json, aim-chapters.csv, aim-protests.csv)
- `static/archive-xml/` — Original TEI XML files preserved for reference

### External Libraries (CDN)

D3.js v7, Observable Plot v0.6, and d3-cloud v1.2.7 are loaded via CDN for the text analysis dashboard.

### Key Configuration

`hugo.toml`: Taxonomies (document_types, publications), unsafe HTML rendering enabled in Markdown, custom permalinks, 20 items/page pagination.

### Scripts

- `scripts/analyze_text.py` — Extracts text from archive Markdown files, generates word frequencies and KWIC index. Outputs to `static/data/text-analysis.json` and `static/data/kwic-index.json`.
- `convert_xml_to_md.py` — One-time TEI XML to Markdown converter (already run; preserves metadata, creates URL aliases for old PHP routes).

### URL Compatibility

Old PHP URLs are preserved via Hugo `aliases` in document frontmatter (e.g., `/archive/newspapers/frp.nyt.19721005.xml` redirects to the Hugo page).
