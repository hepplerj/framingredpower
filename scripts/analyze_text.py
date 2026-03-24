#!/usr/bin/env python3
"""
Text Analysis Script for Framing Red Power
Extracts text from document markdown files and generates analysis data
"""

import os
import re
import json
from pathlib import Path
from collections import Counter
from datetime import datetime
import frontmatter

# Common English stopwords
STOPWORDS = {
    'a', 'about', 'above', 'after', 'again', 'against', 'all', 'am', 'an', 'and', 'any', 'are',
    'as', 'at', 'be', 'because', 'been', 'before', 'being', 'below', 'between', 'both', 'but',
    'by', 'could', 'did', 'do', 'does', 'doing', 'down', 'during', 'each', 'few', 'for', 'from',
    'further', 'had', 'has', 'have', 'having', 'he', 'her', 'here', 'hers', 'herself', 'him',
    'himself', 'his', 'how', 'i', 'if', 'in', 'into', 'is', 'it', 'its', 'itself', 'just', 'me',
    'might', 'more', 'most', 'must', 'my', 'myself', 'no', 'nor', 'not', 'now', 'of', 'off', 'on',
    'once', 'only', 'or', 'other', 'our', 'ours', 'ourselves', 'out', 'over', 'own', 's', 'same',
    'she', 'should', 'so', 'some', 'such', 't', 'than', 'that', 'the', 'their', 'theirs', 'them',
    'themselves', 'then', 'there', 'these', 'they', 'this', 'those', 'through', 'to', 'too',
    'under', 'until', 'up', 'very', 'was', 'we', 'were', 'what', 'when', 'where', 'which', 'while',
    'who', 'whom', 'why', 'will', 'with', 'would', 'you', 'your', 'yours', 'yourself', 'yourselves',
    'said', 'says', 'one', 'two', 'also', 'can', 'may', 'make', 'made', 'get', 'got'
}

def clean_text(text):
    """Clean and normalize text"""
    # Remove HTML tags
    text = re.sub(r'<[^>]+>', ' ', text)
    # Remove special characters but keep hyphens in words
    text = re.sub(r'[^\w\s-]', ' ', text)
    # Normalize whitespace
    text = re.sub(r'\s+', ' ', text)
    return text.strip()

def extract_words(text):
    """Extract and clean words from text"""
    text = clean_text(text)
    words = text.lower().split()
    # Filter stopwords and short words
    words = [w for w in words if len(w) > 2 and w not in STOPWORDS]
    # Remove numbers
    words = [w for w in words if not w.isdigit()]
    return words

def extract_ngrams(words, n):
    """Extract n-grams from a list of already-filtered words"""
    return [' '.join(words[i:i+n]) for i in range(len(words) - n + 1)]

def analyze_documents(content_dir):
    """Analyze all document markdown files"""
    archive_dir = Path(content_dir) / 'archive'

    all_words = []
    all_bigrams = []
    all_trigrams = []
    documents = []
    words_by_date = {}
    words_by_publication = {}

    # Process all markdown files in archive
    for md_file in archive_dir.rglob('*.md'):
        if md_file.name == '_index.md':
            continue

        try:
            # Parse frontmatter and content
            post = frontmatter.load(md_file)

            # Extract metadata
            title = post.get('title', '')
            date = post.get('date', '')
            publication = post.get('source', post.get('publication', 'Unknown'))
            doc_types = post.get('document_types', [])

            # Extract and clean content
            content = post.content
            words = extract_words(content)

            if not words:
                continue

            all_words.extend(words)
            all_bigrams.extend(extract_ngrams(words, 2))
            all_trigrams.extend(extract_ngrams(words, 3))

            # Determine section from file path
            relative_path = md_file.relative_to(archive_dir)
            section = relative_path.parts[0] if len(relative_path.parts) > 1 else 'archive'

            # Store document info
            doc_info = {
                'title': title,
                'date': str(date) if date else None,
                'publication': publication,
                'types': doc_types if isinstance(doc_types, list) else [doc_types],
                'word_count': len(words),
                'path': str(md_file.relative_to(content_dir)),
                'url': f"/archive/{section}/{md_file.stem}/"
            }
            documents.append(doc_info)

            # Track words by date
            if date:
                date_key = str(date)[:10]  # YYYY-MM-DD
                if date_key not in words_by_date:
                    words_by_date[date_key] = []
                words_by_date[date_key].extend(words)

            # Track words by publication
            if publication not in words_by_publication:
                words_by_publication[publication] = []
            words_by_publication[publication].extend(words)

        except Exception as e:
            print(f"Error processing {md_file}: {e}")
            continue

    # Calculate overall word frequencies
    word_freq = Counter(all_words)
    top_words = word_freq.most_common(100)

    bigram_freq = Counter(all_bigrams)
    top_bigrams = bigram_freq.most_common(50)

    trigram_freq = Counter(all_trigrams)
    top_trigrams = trigram_freq.most_common(40)

    # Calculate word frequencies by date
    date_frequencies = {}
    for date, words in words_by_date.items():
        freq = Counter(words)
        date_frequencies[date] = dict(freq.most_common(50))

    # Calculate word frequencies by publication
    pub_frequencies = {}
    for pub, words in words_by_publication.items():
        freq = Counter(words)
        pub_frequencies[pub] = dict(freq.most_common(50))

    # Sort documents by date
    documents.sort(key=lambda x: x['date'] or '0000-00-00', reverse=True)

    return {
        'word_frequencies': [{'word': word, 'count': count} for word, count in top_words],
        'bigram_frequencies': [{'phrase': phrase, 'count': count} for phrase, count in top_bigrams],
        'trigram_frequencies': [{'phrase': phrase, 'count': count} for phrase, count in top_trigrams],
        'documents': documents,
        'date_frequencies': date_frequencies,
        'publication_frequencies': pub_frequencies,
        'total_words': len(all_words),
        'unique_words': len(word_freq),
        'total_documents': len(documents),
        'generated': datetime.now().isoformat()
    }

def generate_kwic_index(content_dir, context_words=5):
    """Generate keywords in context index"""
    archive_dir = Path(content_dir) / 'archive'
    kwic_data = []

    for md_file in archive_dir.rglob('*.md'):
        if md_file.name == '_index.md':
            continue

        try:
            post = frontmatter.load(md_file)
            title = post.get('title', '')
            date = post.get('date', '')
            content = clean_text(post.content)

            # Determine section from file path
            relative_path = md_file.relative_to(archive_dir)
            section = relative_path.parts[0] if len(relative_path.parts) > 1 else 'archive'

            # Store full text for KWIC searches
            kwic_data.append({
                'title': title,
                'date': str(date) if date else None,
                'url': f"/archive/{section}/{md_file.stem}/",
                'text': content
            })

        except Exception as e:
            print(f"Error processing {md_file} for KWIC: {e}")
            continue

    return kwic_data

def main():
    """Main analysis function"""
    # Paths
    script_dir = Path(__file__).parent
    project_dir = script_dir.parent
    content_dir = project_dir / 'content'
    static_dir = project_dir / 'static'
    data_dir = static_dir / 'data'

    # Create data directory
    data_dir.mkdir(parents=True, exist_ok=True)

    print("🔍 Analyzing documents...")
    analysis = analyze_documents(content_dir)

    print(f"✅ Processed {analysis['total_documents']} documents")
    print(f"📊 Found {analysis['unique_words']} unique words")
    print(f"💬 Total words: {analysis['total_words']}")

    # Save analysis data
    with open(data_dir / 'text-analysis.json', 'w') as f:
        json.dump(analysis, f, indent=2)
    print(f"💾 Saved analysis to static/data/text-analysis.json")

    # Generate KWIC index
    print("🔍 Generating KWIC index...")
    kwic = generate_kwic_index(content_dir)
    with open(data_dir / 'kwic-index.json', 'w') as f:
        json.dump(kwic, f, indent=2)
    print(f"💾 Saved KWIC index to static/data/kwic-index.json")

    print("✨ Text analysis complete!")

if __name__ == '__main__':
    main()
