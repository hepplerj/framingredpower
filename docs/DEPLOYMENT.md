# Deployment Guide for Framing Red Power

This guide covers deployment options for the Hugo-based Framing Red Power site.

## Pre-Deployment Checklist

- [x] All content migrated (94 documents, 13 narrative pages, 9 browse tools)
- [x] Static assets copied (CSS, JS, images, XML files)
- [x] URL aliases configured for old PHP URLs
- [x] Site builds without errors (`hugo --minify`)
- [x] Navigation and menus working correctly
- [x] Design preserved (800px, colors, fonts)
- [ ] Test in multiple browsers
- [ ] Verify all interactive features (timeline, maps, search)
- [ ] Review Google Analytics setup (consider upgrading to GA4)

## Build Commands

### Development
```bash
hugo server
# or with drafts and future content:
hugo server --buildDrafts --buildFuture
```

Access at: http://localhost:1313

### Production Build
```bash
# Option 1: Use the build script (recommended)
./build.sh

# Option 2: Manual build
hugo --minify
npx -y pagefind --site public
```

Output directory: `public/`

The build script automatically runs Hugo and Pagefind to index the site for search.

## Deployment Options

### Option 1: Netlify (Recommended)

**Pros**: Free for static sites, automatic HTTPS, continuous deployment, CDN
**Cons**: None for this use case

**Steps**:
1. Create a Netlify account at https://netlify.com
2. Connect your Git repository (GitHub, GitLab, Bitbucket)
3. Configure build settings:
   - **Build command**: `hugo --minify`
   - **Publish directory**: `public`
   - **Environment variables**: `HUGO_VERSION` = `0.147.2`
4. Deploy!

**Custom Domain**:
- Add `framingredpower.org` in Netlify's domain settings
- Update DNS records as instructed by Netlify
- Netlify handles HTTPS certificate automatically

**netlify.toml** (optional, add to repository):
```toml
[build]
  publish = "public"
  command = "hugo --minify"

[context.production.environment]
  HUGO_VERSION = "0.147.2"
  HUGO_ENV = "production"

[[redirects]]
  from = "/*"
  to = "/404.html"
  status = 404
```

### Option 2: Vercel

**Pros**: Similar to Netlify, excellent performance
**Cons**: None for this use case

**Steps**:
1. Create Vercel account at https://vercel.com
2. Import Git repository
3. Vercel auto-detects Hugo
4. Configure if needed:
   - **Framework Preset**: Hugo
   - **Build Command**: `hugo --minify`
   - **Output Directory**: `public`
5. Deploy!

### Option 3: GitHub Pages

**Pros**: Free, integrated with GitHub
**Cons**: Requires GitHub Actions workflow

**Steps**:
1. Create `.github/workflows/hugo.yml`:

```yaml
name: Deploy Hugo site to Pages

on:
  push:
    branches: ["main"]
  workflow_dispatch:

permissions:
  contents: read
  pages: write
  id-token: write

jobs:
  build:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - name: Setup Hugo
        uses: peaceiris/actions-hugo@v2
        with:
          hugo-version: '0.147.2'
          extended: true
      - name: Build
        run: hugo --minify
      - name: Upload artifact
        uses: actions/upload-pages-artifact@v2
        with:
          path: ./public

  deploy:
    environment:
      name: github-pages
      url: ${{ steps.deployment.outputs.page_url }}
    runs-on: ubuntu-latest
    needs: build
    steps:
      - name: Deploy to GitHub Pages
        id: deployment
        uses: actions/deploy-pages@v3
```

2. Enable GitHub Pages in repository settings
3. Set source to "GitHub Actions"
4. Push to main branch to trigger deployment

**Custom Domain**: Configure in GitHub Pages settings

### Option 4: Cloudflare Pages

**Pros**: Excellent CDN, free, fast
**Cons**: None

**Steps**:
1. Create Cloudflare Pages account
2. Connect Git repository
3. Configure:
   - **Build command**: `hugo --minify`
   - **Build output directory**: `public`
   - **Environment variable**: `HUGO_VERSION` = `0.147.2`
4. Deploy!

### Option 5: Traditional Web Hosting

**Pros**: Use existing hosting, complete control
**Cons**: Manual deployment, no automatic builds

**Steps**:
1. Build the site locally:
   ```bash
   hugo --minify
   ```

2. Upload `public/` directory contents via:
   - FTP/SFTP
   - rsync: `rsync -avz --delete public/ user@server:/path/to/webroot/`
   - SCP: `scp -r public/* user@server:/path/to/webroot/`

3. Ensure web server is configured:
   - Apache: `.htaccess` for redirects (already exists in project)
   - Nginx: Configure redirects in server block

**Nginx Configuration Example**:
```nginx
server {
    listen 80;
    server_name framingredpower.org www.framingredpower.org;
    root /var/www/framingredpower.org/public;
    index index.html;

    location / {
        try_files $uri $uri/ =404;
    }

    # Optional: redirect www to non-www
    if ($host = www.framingredpower.org) {
        return 301 https://framingredpower.org$request_uri;
    }
}
```

## Post-Deployment

### 1. Verify Deployment

Check these URLs work correctly:
- Homepage: https://framingredpower.org/
- Narrative: https://framingredpower.org/narrative/
- Archive: https://framingredpower.org/archive/
- A document: https://framingredpower.org/archive/archive/indians-to-drive-to-capital/
- Sources: https://framingredpower.org/sources/timeline/

### 2. Test Redirects

Verify old PHP URLs redirect:
- https://framingredpower.org/index.php → https://framingredpower.org/
- https://framingredpower.org/narrative/index.php → https://framingredpower.org/narrative/
- https://framingredpower.org/archive/types.php → https://framingredpower.org/archive/

### 3. Verify Interactive Features

- Google Custom Search: https://framingredpower.org/sources/search/
- SIMILE Timeline: https://framingredpower.org/sources/timeline/
- Google Maps embeds work
- Word clouds display
- Navigation highlighting works on all pages

### 4. Update DNS

If using a custom domain:
1. Point `framingredpower.org` to hosting provider
2. Configure `www.framingredpower.org` (redirect to non-www recommended)
3. Wait for DNS propagation (up to 48 hours, usually faster)

### 5. SSL/HTTPS

Most modern hosting providers (Netlify, Vercel, Cloudflare, GitHub Pages) provide automatic HTTPS. For traditional hosting:
- Use Let's Encrypt: https://letsencrypt.org/
- Most control panels (cPanel, Plesk) have built-in Let's Encrypt support

### 6. Monitor

- Check Google Analytics for traffic
- Monitor error logs for 404s or broken links
- Set up uptime monitoring (UptimeRobot, Pingdom, etc.)

## Updating Content

### Via Git (Recommended for Netlify/Vercel/GitHub Pages/Cloudflare)

1. Edit content files locally
2. Commit and push to Git repository
3. Hosting provider automatically rebuilds and deploys

### Traditional Hosting

1. Edit content locally
2. Build: `hugo --minify`
3. Upload `public/` directory

## Rollback

### Git-Based Hosting
1. Revert Git commit
2. Push to trigger rebuild

### Traditional Hosting
1. Keep backup of previous `public/` directory
2. Upload backup if needed

## Maintenance

### Regular Updates
- Check for Hugo updates periodically
- Update jQuery if security issues arise (currently on 1.12.4)
- Review and update Google Analytics (consider GA4 migration)

### Content Updates
- Archive documents: Add XML to `archive/`, run conversion script
- Narrative pages: Edit Markdown in `content/narrative/`
- Browse tools: Edit Markdown in `content/sources/`

### Backup
- Entire site is in Git repository
- Original TEI XML files preserved in `static/archive-xml/`
- Keep backup of `.git` directory and database (if any)

## Troubleshooting

### Build Fails
- Check Hugo version matches `.hugo-version` (0.147.2)
- Verify `hugo.toml` syntax
- Look for front matter errors in Markdown files

### 404 Errors
- Check aliases in front matter
- Verify permalink configuration
- Ensure files exist in `content/` directory

### Broken Links
- Use Hugo's built-in link checker
- Check for hardcoded URLs in content
- Verify image paths start with `/`

### Styling Issues
- Verify CSS files in `static/css/`
- Check browser console for 404s on CSS/JS
- Ensure paths in `layouts/partials/head.html` are correct

## Support

For deployment issues or questions:
- Hugo documentation: https://gohugo.io/documentation/
- Hugo forums: https://discourse.gohugo.io/
- Project contact: jason.heppler@huskers.unl.edu
