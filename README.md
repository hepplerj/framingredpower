# Framing Red Power - Hugo Migration

This repository contains the Hugo-based version of the Framing Red Power digital history project, migrated from the original PHP-based site.

## About the Project

Framing Red Power explores the history of the Trail of Broken Treaties, newspaper coverage, and the American Indian Movement's use of media in crafting their image during the 1972-1973 period.

## Technical Details

- **Static Site Generator**: Hugo v0.147.2+extended
- **Original Site**: PHP-based (migrated January 2026)
- **Content**: 94 TEI XML documents converted to Markdown, narrative analysis pages, and interactive browse tools

## Project Structure

```
├── content/              # All markdown content
│   ├── archive/          # 94 converted TEI documents
│   │   ├── newspapers/   # Newspaper articles
│   │   ├── television/   # TV transcripts
│   │   ├── other/        # Speeches, treaties, etc.
│   │   └── fbi/          # FBI documents
│   ├── narrative/        # Analytical essays (3 sections)
│   ├── sources/          # Browse tools (timeline, maps, etc.)
│   └── about/            # About page
├── layouts/              # Hugo templates
│   ├── _default/         # Base templates
│   ├── partials/         # Reusable components
│   ├── archive/          # Document templates
│   ├── narrative/        # Two-column narrative layout
│   └── sources/          # Browse tools layout
├── static/               # Static assets
│   ├── css/              # Original stylesheets preserved
│   ├── js/               # JavaScript (jQuery 1.12.4)
│   ├── images/           # All original images
│   ├── xml/              # Timeline data (papercoverage.xml)
│   └── archive-xml/      # Original TEI XML files preserved
└── public/               # Generated site (not in git)
```

## Building the Site

### Requirements

- Hugo v0.147.2 or higher (extended version)
- Python 3.x (for XML conversion script, if needed)

### Development

```bash
# Start development server
hugo server

# Build for production
hugo --minify
```

### Deployment

The site generates static HTML in the `public/` directory. Deploy to any static hosting:

- **Netlify**: Connect repo, set build command to `hugo --minify`
- **Vercel**: Similar to Netlify
- **GitHub Pages**: Use GitHub Actions workflow
- **Traditional hosting**: Upload `public/` directory contents

## Migration Details

### What Was Preserved

1. **Design**: Exact CSS preservation (800px width, #D6D1B1 tan background, #710100 red accents)
2. **Content**: All 97 original TEI XML documents (3 had parsing errors, 94 converted successfully)
3. **URLs**: All old PHP URLs redirect via Hugo aliases
4. **Features**:
   - Google Custom Search Engine
   - SIMILE Timeline (with papercoverage.xml)
   - Google Maps embeds (protest map, AIM chapters, relocation centers)
   - Word cloud visualizations
   - Narrative themes with interactive markers
   - Two-column layout for narrative pages with hierarchical sidebar navigation

### What Was Updated

1. **jQuery**: Updated from 1.2.3 to 1.12.4 for compatibility
2. **Analytics**: Preserved original Google Analytics UA tracking code (consider upgrading to GA4)
3. **Structure**: Converted from PHP includes to Hugo templates and partials

### Content Sections

#### Archive Documents (94 files)
- Newspaper articles from NYT, Washington Post, LA Times, Chicago Tribune, etc.
- Television transcripts (NBC)
- Speeches, treaties, government documents
- FBI files

Each document includes:
- Original metadata (title, date, source, page)
- Full text content
- Link to preserved TEI XML file
- Document type taxonomy

#### Narrative Analysis (13 pages)
Three main sections with subsections:

1. **Race, Print Media, and Political Activism**
   - Politics and the Image
   - Media in the 1960s
   - Civil Rights and Media Coverage

2. **The American Indian Movement**
   - Founding Years
   - Intellectual Origins
   - AIM and the Politics of Media

3. **The Trail of Broken Treaties**
   - Planning, the Caravan, and the Breakdown
   - Media and the Discourse of Indian Politics
   - Identity, Militancy, and the Politics of Law and Order

#### Browse Tools (9 pages)
- Search (Google Custom Search Engine)
- Timeline (SIMILE Timeline with papercoverage.xml)
- Word Clouds (cumulative visualizations by media type)
- Protest Map (Google Maps)
- AIM Chapters Map
- Relocation Centers Map
- Narrative Themes (interactive markers)
- Topic Timeline (extensive table)

## Taxonomies

The site uses Hugo taxonomies for organizing documents:

- `document_types`: newspaper-reports, newspaper-opinions, television-reports, etc.
- `publications`: nyt, wapo, lat, ct, time, nbc, etc.
- `categories`: (for future organization)
- `tags`: (for future tagging)

Access taxonomies at:
- `/document_types/` - List all document types
- `/document_types/newspaper-reports/` - Filter by type
- `/publications/` - List all publications

## URL Structure

### Current URLs (Hugo)
- Homepage: `/`
- Narrative: `/narrative/`, `/narrative/activism/`, etc.
- Archive: `/archive/`, `/archive/newspapers/`, `/archive/television/`
- Documents: `/archive/archive/[slug]/` (e.g., `/archive/archive/indians-to-drive-to-capital/`)
- Browse: `/sources/`, `/sources/timeline/`, etc.
- About: `/about/`

### Legacy URLs (Redirected)
All old PHP URLs redirect automatically:
- `/index.php` → `/`
- `/narrative/index.php` → `/narrative/`
- `/archive/types.php` → `/archive/`
- `/archive/newspapers/frp.nyt.19721005.xml` → `/archive/archive/indians-to-drive-to-capital/`
- `/sources/timeline/index.php` → `/sources/timeline/`

## Scripts

### XML to Markdown Conversion

The `convert_xml_to_md.py` script (already run) converts TEI XML documents to Hugo-compatible Markdown:

```bash
python3 convert_xml_to_md.py
```

This script:
- Parses TEI XML headers for metadata
- Extracts body content
- Generates Markdown with structured front matter
- Preserves original XML in `static/archive-xml/`
- Creates proper aliases for URL compatibility

## Configuration

Key settings in `hugo.toml`:

- **baseURL**: Set to `https://framingredpower.org/`
- **Taxonomies**: document_types, publications, categories, tags
- **Menu**: Main navigation configured in config
- **Permalinks**: Custom URL patterns for sections
- **Pagination**: 20 items per page

## Maintenance

### Adding New Documents

1. Place TEI XML in appropriate `archive/` subdirectory
2. Run conversion script: `python3 convert_xml_to_md.py`
3. Rebuild site: `hugo`

### Updating Content

1. Edit Markdown files in `content/`
2. Front matter controls metadata and aliases
3. HTML can be embedded in Markdown (unsafe mode enabled)

### Analytics

Current setup uses Google Analytics Universal Analytics (UA-12786552-1). Consider migrating to GA4:

1. Create GA4 property
2. Update tracking code in `layouts/partials/footer.html`
3. Replace `_gat._getTracker()` with GA4 gtag.js

## Browser Compatibility

The site maintains the original design specification:
- Fixed 800px width (not responsive)
- Best viewed in modern browsers
- Original note said "best viewed in Firefox 3.0 or higher"

## Credits

- **Original Site**: Jason A. Heppler (2008-2009)
- **Hugo Migration**: Completed January 2026
- **Institution**: University of Nebraska-Lincoln, Center for Digital Research in the Humanities

## License

See `content/about/_index.md` for copyright and conditions of use.

## Support

For issues or questions about the site, contact Jason Heppler at jason.heppler@huskers.unl.edu
