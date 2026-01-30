#!/bin/bash
# Build script for Framing Red Power
# Builds Hugo site and indexes with Pagefind

set -e  # Exit on error

echo "🔨 Building Hugo site..."
hugo --minify

echo "🔍 Indexing with Pagefind..."
npx -y pagefind --site public

echo "✅ Build complete!"
echo "📊 Site ready in public/ directory"
