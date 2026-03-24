---
title: "Text Analysis: Methods and Data Pipeline"
slug: "text-analysis-methods"
---

This page describes how the [Text Analysis Dashboard](/sources/text-analysis/) was built — what the corpus contains, how the scripts process it, and how the visualizations are generated. It is intended for readers who want to understand or replicate the computational methods used in this project.

---

## The Corpus

The analysis draws on **94 archival documents** collected in the project's digital archive. The documents span newspaper reports, television transcripts, FBI records, and primary source texts — with the bulk of the corpus consisting of newspaper coverage from major U.S. publications between October 1972 and March 1973: the *New York Times*, *Washington Post*, *Los Angeles Times*, *Chicago Tribune*, *Minneapolis Tribune*, and *Time* magazine.

Each document was originally encoded in TEI XML and converted to Markdown with YAML frontmatter recording metadata such as publication source, date, author, and document type. The text analysis pipeline reads these Markdown files directly.

---

## Data Pipeline

The analysis is performed by a Python script ([`scripts/analyze_text.py`](https://github.com/hepplerj/framingredpower/blob/main/scripts/analyze_text.py)) that runs as part of the build process. Its output — a pair of JSON files — is what the browser-side visualizations read.

### Document Ingestion

The script walks the `content/archive/` directory recursively, loading each Markdown file using the [`python-frontmatter`](https://github.com/eyeseast/python-frontmatter) library. This separates the YAML metadata (title, date, publication) from the document body. Index files (`_index.md`) are skipped.

### Text Cleaning

Before any analysis, the document text is cleaned:

1. **HTML tags removed** — the archive content includes inline HTML (headings, paragraph tags); these are stripped with a regex.
2. **Special characters removed** — punctuation and symbols are replaced with spaces, preserving hyphens within words.
3. **Whitespace normalized** — multiple spaces and newlines are collapsed to single spaces.

### Stopword Filtering

Words are lowercased and split on whitespace. A custom stopword list removes common English function words (`the`, `of`, `and`, `to`, etc.) as well as high-frequency but low-information terms common in this corpus (`said`, `says`, `one`, `two`, `also`, `can`, `may`). Words shorter than three characters and pure numerals are also removed.

The stopword list was assembled manually rather than borrowed wholesale from a standard NLP library, to keep the dependencies minimal and to allow project-specific tuning. This means the list reflects deliberate choices about which words carry interpretive weight in this corpus.

### Word Frequency

After filtering, a Python `Counter` tallies all words across the full corpus. The top 100 terms by raw frequency are written to the output JSON. The dashboard displays the top 50.

### Bigrams and Trigrams

N-grams are extracted from the **already-filtered** word list — that is, stopwords are removed before forming pairs and triples. This means "bureau indian" rather than "bureau of indian" appears as a bigram, and "bureau indian affairs" rather than "bureau of indian affairs" as a trigram. While this loses the prepositions and articles that appear in natural speech, it surfaces the content-word collocations that are most analytically useful for studying how events were framed.

Bigrams are formed by sliding a two-word window across each document's filtered word list; trigrams use a three-word window. A `Counter` tallies all n-grams across the full corpus. The output JSON includes the top 50 bigrams and top 40 trigrams; the dashboard displays the top 30 bigrams and top 25 trigrams.

### KWIC Index

Separately from the frequency analysis, the script generates a Keywords in Context (KWIC) index. This reads each document's cleaned text (with stopwords *not* removed, so searches work on natural language) and stores the full text alongside metadata. This is written to a second JSON file (`kwic-index.json`).

### Output Files

Two JSON files are written to `static/data/`:

- **`text-analysis.json`** — word, bigram, and trigram frequencies; per-document metadata; aggregate statistics
- **`kwic-index.json`** — full cleaned text of each document for in-browser KWIC search

---

## Visualizations

The dashboard is built entirely in the browser, loading the JSON data with D3 and rendering charts with [Observable Plot](https://observablehq.com/plot/).

### Frequency Bar Charts

Word, bigram, and trigram frequencies are displayed as horizontal bar charts. The charts are generated with `Plot.barX` from Observable Plot, sorted descending by count. Count labels are overlaid on each bar using `Plot.text`. Charts are sized responsively to the viewport width.

### Word Cloud

The word cloud uses [d3-cloud](https://github.com/jasondavies/d3-cloud), a layout library that places words at sizes proportional to their frequency, using a square-root scale to prevent the most common words from completely dominating. Font sizes range from 14px to 48px. Words are placed horizontally only (no rotation) for readability, with a rectangular spiral packing algorithm. Clicking a word in the cloud pre-fills the KWIC search and scrolls to the results.

### Keywords in Context Search

The KWIC search runs entirely in the browser against the pre-built index. When a user submits a term, a regular expression matches the term (including partial matches) across all documents in the index. For each match, 100 characters of surrounding context are extracted and displayed with the matched term highlighted. Results are capped at 50 occurrences.

---

## Technical Stack

| Component | Tool |
|---|---|
| Source code | [github.com/hepplerj/framingredpower](https://github.com/hepplerj/framingredpower) |
| Text extraction and analysis | Python 3, `python-frontmatter`, `collections.Counter` |
| Dependency management | [`uv`](https://github.com/astral-sh/uv) |
| Site build | [Hugo](https://gohugo.io/) v0.147+ |
| Frequency charts | [Observable Plot](https://observablehq.com/plot/) v0.6 |
| Word cloud layout | [d3-cloud](https://github.com/jasondavies/d3-cloud) v1.2.7 |
| Data loading and manipulation | [D3.js](https://d3js.org/) v7 |

---

## Limitations

**Stopword choices shape results.** Any text analysis reflects decisions about which words are "meaningful." The stopword list here is manually curated and was not subject to systematic evaluation. Different lists would produce different frequency rankings.

**N-grams cross document boundaries.** The n-gram window is applied per-document, so no bigram or trigram spans two different sources. But n-grams do not account for sentence or paragraph boundaries within a document, so some pairings may cross natural phrase boundaries.

**No comparative baseline.** The frequencies here describe this corpus in isolation. Without a baseline — such as general newspaper language from the same period — it is difficult to say whether a word or phrase is distinctive to this coverage or simply common in 1970s newspaper prose generally.

**Corpus size.** 94 documents is a modest corpus by computational text analysis standards. Frequency rankings at this scale can be sensitive to a small number of documents. The KWIC search, which returns results in context, is often more analytically useful than raw frequency counts.
