#!/usr/bin/env python3
"""
Convert TEI XML documents from archive/ to Hugo Markdown format.
Preserves original XML files in static/archive-xml/
"""

import os
import re
import shutil
from pathlib import Path
from xml.etree import ElementTree as ET

def clean_text(text):
    """Clean up text content"""
    if not text:
        return ""
    # Remove extra whitespace
    text = re.sub(r'\s+', ' ', text)
    return text.strip()

def extract_metadata(root):
    """Extract metadata from TEI header"""
    metadata = {
        'title': '',
        'date': '',
        'publication': '',
        'source': '',
        'page': '',
        'author': '',
        'document_type': 'document'
    }

    # Extract title
    title_elem = root.find('.//title[@type="main"]')
    if title_elem is not None and title_elem.text:
        metadata['title'] = clean_text(title_elem.text).strip('"')

    # Extract date
    date_elem = root.find('.//sourceDesc/bibl/date[@value]')
    if date_elem is not None:
        metadata['date'] = date_elem.get('value', '')

    # Extract publication/journal
    pub_elem = root.find('.//sourceDesc/bibl/title[@level="j"]')
    if pub_elem is not None and pub_elem.text:
        metadata['source'] = clean_text(pub_elem.text)
        # Create publication slug
        pub_slug = metadata['source'].lower()
        pub_slug = re.sub(r'[^a-z0-9]+', '-', pub_slug).strip('-')
        metadata['publication'] = pub_slug

    # Extract page
    page_elem = root.find('.//sourceDesc/bibl/biblScope[@type="page"]')
    if page_elem is not None and page_elem.text:
        metadata['page'] = clean_text(page_elem.text)

    # Extract author
    author_elem = root.find('.//sourceDesc/bibl/author[@n]')
    if author_elem is not None and author_elem.text:
        author_text = clean_text(author_elem.text)
        if author_text:
            metadata['author'] = author_text

    return metadata

def extract_body_content(root):
    """Extract and convert body content from TEI to HTML"""
    body = root.find('.//body')
    if body is None:
        return ""

    content_parts = []

    # Process all div1 elements in body
    for div in body.findall('.//div1[@type="body"]'):
        # Process paragraphs
        for elem in div.iter():
            if elem.tag == 'p':
                para_text = ''.join(elem.itertext())
                para_text = clean_text(para_text)
                if para_text:
                    content_parts.append(f"<p>{para_text}</p>\n")
            elif elem.tag == 'head' and elem.get('type') == 'main':
                head_text = clean_text(''.join(elem.itertext()))
                if head_text:
                    content_parts.append(f"<h3>{head_text}</h3>\n\n")

    return '\n'.join(content_parts)

def determine_document_type(filepath):
    """Determine document type from filepath"""
    path_str = str(filepath)

    if 'newspapers' in path_str:
        return 'newspaper-reports'
    elif 'editorials' in path_str:
        return 'newspaper-opinions'
    elif 'television' in path_str:
        return 'television-reports'
    elif 'magazines' in path_str:
        return 'news-magazines'
    elif 'books' in path_str:
        return 'books'
    elif 'treaties' in path_str:
        return 'treaties'
    elif 'statutes' in path_str:
        return 'statutes'
    elif 'government' in path_str:
        return 'government-documents'
    elif 'speeches' in path_str:
        return 'speeches'
    elif 'documents' in path_str:
        return 'activist-documents'
    else:
        return 'other'

def convert_xml_file(xml_path, output_base, static_base):
    """Convert a single XML file to Markdown"""
    try:
        # Parse XML
        tree = ET.parse(xml_path)
        root = tree.getroot()

        # Extract metadata
        metadata = extract_metadata(root)

        # Extract content
        content = extract_body_content(root)

        # Determine paths
        rel_path = xml_path.relative_to(Path('archive'))
        section = rel_path.parent.name
        filename = xml_path.stem + '.md'

        # Create output directory
        output_dir = output_base / section
        output_dir.mkdir(parents=True, exist_ok=True)

        # Create static archive directory
        static_dir = static_base / section
        static_dir.mkdir(parents=True, exist_ok=True)

        # Copy original XML to static
        static_xml_path = static_dir / xml_path.name
        shutil.copy2(xml_path, static_xml_path)

        # Determine document type
        doc_type = determine_document_type(xml_path)

        # Build front matter
        front_matter = f"""---
title: "{metadata['title']}"
date: {metadata['date']}
document_types: ["{doc_type}"]
publication: "{metadata['publication']}"
source: "{metadata['source']}"
page: "{metadata['page']}"
tei_original: "/archive-xml/{section}/{xml_path.name}"
aliases:
  - /archive/{section}/{xml_path.stem}.xml
"""

        if metadata['author']:
            front_matter += f'author: "{metadata["author"]}"\n'

        front_matter += "---\n\n"

        # Add metadata display
        content_with_meta = f"""<div class="document-metadata">
<p><strong>Source:</strong> {metadata['source']}</p>
<p><strong>Date:</strong> {metadata['date']}</p>
"""

        if metadata['page']:
            content_with_meta += f"<p><strong>Page:</strong> {metadata['page']}</p>\n"

        if metadata['author']:
            content_with_meta += f"<p><strong>Author:</strong> {metadata['author']}</p>\n"

        content_with_meta += f"""<p><strong>Original TEI XML:</strong> <a href="{static_xml_path.relative_to(static_base.parent)}">{xml_path.name}</a></p>
</div>

<hr>

{content}
"""

        # Write markdown file
        output_file = output_dir / filename
        with open(output_file, 'w', encoding='utf-8') as f:
            f.write(front_matter)
            f.write(content_with_meta)

        print(f"✓ Converted: {xml_path} -> {output_file}")
        return True

    except Exception as e:
        print(f"✗ Error converting {xml_path}: {e}")
        return False

def main():
    """Main conversion process"""
    archive_dir = Path('archive')
    content_dir = Path('content/archive')
    static_dir = Path('static/archive-xml')

    # Find all XML files
    xml_files = list(archive_dir.glob('**/*.xml'))

    print(f"Found {len(xml_files)} XML files to convert")
    print("-" * 60)

    success_count = 0
    fail_count = 0

    for xml_file in sorted(xml_files):
        if convert_xml_file(xml_file, content_dir, static_dir):
            success_count += 1
        else:
            fail_count += 1

    print("-" * 60)
    print(f"\nConversion complete!")
    print(f"Success: {success_count}")
    print(f"Failed: {fail_count}")

if __name__ == '__main__':
    main()
