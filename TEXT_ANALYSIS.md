# Text Analysis Dashboard

## Overview

The text analysis dashboard provides interactive visualizations of word frequencies, temporal patterns, and keywords in context across all 94 archival newspaper documents.

**Stats:**
- 94 documents analyzed
- 46,534 total words
- 7,580 unique terms (stopwords removed)

## Features

### 1. Word Frequency Bar Chart
- Top 50 most common words
- Horizontal bar chart sorted by frequency
- Interactive tooltips with exact counts

### 2. Term Frequency Timeline
- Line chart showing how key terms appear over time
- Pre-selected terms: indian, indians, protesters, government, treaty, bureau
- Date range: October 1972 - March 1973

### 3. Document Statistics
- Distribution of documents by publication source
- Bar chart showing document counts

### 4. Word Cloud
- Visual representation of top 100 words
- Size and color mapped to frequency
- **Interactive**: Click any word to search for it in context below

### 5. Keywords in Context (KWIC)
- Search for any term
- Shows surrounding context (~200 characters)
- Links to source documents
- Highlights search term in results
- Displays up to 50 matches

## Technology

- **Observable Plot**: Modern, declarative visualization library
- **D3.js**: Data manipulation and SVG rendering
- **Client-side only**: All analysis runs in the browser
- **Privacy-respecting**: No external tracking or API calls

## Data Generation

The analysis data is generated at build time by `scripts/analyze_text.py`:

```bash
# Run manually
uv run --with python-frontmatter scripts/analyze_text.py

# Or included in build
./build.sh
```

This generates two JSON files:
- `static/data/text-analysis.json` - Word frequencies and statistics
- `static/data/kwic-index.json` - Full text for KWIC searches (~535KB)

## Usage

Visit `/sources/text-analysis/` to use the dashboard.

The old `/sources/wordclouds/` URL redirects to the new text analysis page via Hugo aliases.

## Future Enhancements

Possible additions:
- Term co-occurrence network
- Topic modeling visualization
- N-gram analysis
- Sentiment analysis over time
- Publication-specific comparisons
- Download data as CSV
- Custom term selection for timeline
