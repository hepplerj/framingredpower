# Original PHP Site Backup

This folder contains the complete original PHP-based Framing Red Power site.

**Date Archived**: January 30, 2026
**Reason**: Migrated to Hugo static site generator

## Contents

This backup includes:
- All original PHP files (index.php, etc.)
- PHP includes (navigation, header, footer, etc.)
- Original TEI XML documents (97 files in archive-old/)
- Original content directories:
  - about-old/ - About page PHP files
  - archive-old/ - Archive with original XML and PHP files
  - narrative-old/ - Narrative analysis PHP files
  - sources-old/ - Browse tools PHP files
- Original static assets:
  - css-old/ - Original stylesheets
  - js-old/ - Original JavaScript files (jQuery 1.2.3)
  - images-old/ - All original images
  - xml-old/ - Timeline data (papercoverage.xml)
- Server configuration:
  - .htaccess - Apache configuration
  - .htpasswds/ - Password files
  - .well-known/ - Security files
  - *.shtml files - Error pages
- Other:
  - directory/ - Directory files
  - demo/ - Demo files
  - cgi-bin/ - CGI scripts
  - error_log - Server error log
  - favicon.ico - Site icon

## Hugo Equivalents

The new Hugo site structure:
- content/ - All markdown content (migrated from PHP)
- layouts/ - Hugo templates (replaces PHP includes)
- static/ - Static assets (copies of css-old, js-old, images-old, xml-old)
- public/ - Generated static HTML

## Notes

- This backup is for reference and comparison only
- The Hugo site (parent directory) is the active version
- Original TEI XML files are also preserved in static/archive-xml/
- Do not delete this folder - it serves as the authoritative backup

## Restoration

If you need to restore the old PHP site:
1. Copy all files from this folder back to the root
2. Remove or rename the Hugo files (content/, layouts/, static/, etc.)
3. Configure web server for PHP

However, this should only be done in an emergency. The Hugo migration
preserves all functionality while providing better performance and security.

---
For questions about the migration, see:
- ../README.md - Project overview
- ../MIGRATION_SUMMARY.md - Detailed migration report
- ../DEPLOYMENT.md - Deployment instructions
