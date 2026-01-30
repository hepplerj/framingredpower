/**
 * Document Filtering and Sorting
 * Provides client-side filtering and sorting for document lists
 */

(function() {
  'use strict';

  // Wait for DOM to be ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initDocumentFilter);
  } else {
    initDocumentFilter();
  }

  function initDocumentFilter() {
    const documentList = document.querySelector('.document-list');
    if (!documentList) return;

    // Create filter controls
    const controls = createFilterControls();
    documentList.parentNode.insertBefore(controls, documentList);

    // Get all documents
    const documents = Array.from(documentList.querySelectorAll('.document-item'));

    // Add filter listeners
    setupFilterListeners(documents);
  }

  function createFilterControls() {
    const controls = document.createElement('div');
    controls.className = 'document-controls';
    controls.innerHTML = `
      <div class="filter-bar">
        <div class="filter-group">
          <label for="search-filter">
            <span class="sr-only">Search documents</span>
            <input
              type="search"
              id="search-filter"
              placeholder="Search documents..."
              aria-label="Search documents by title, source, or content"
            />
          </label>
        </div>

        <div class="filter-group">
          <label for="sort-select">Sort by:</label>
          <select id="sort-select" aria-label="Sort documents by">
            <option value="date-desc">Date (Newest first)</option>
            <option value="date-asc">Date (Oldest first)</option>
            <option value="title-asc">Title (A-Z)</option>
            <option value="title-desc">Title (Z-A)</option>
            <option value="source-asc">Source (A-Z)</option>
          </select>
        </div>

        <div class="filter-group">
          <button
            id="clear-filters"
            class="btn-secondary"
            aria-label="Clear all filters"
          >
            Clear Filters
          </button>
        </div>
      </div>

      <div id="results-count" class="results-count" role="status" aria-live="polite">
        Showing all documents
      </div>
    `;
    return controls;
  }

  function setupFilterListeners(documents) {
    const searchInput = document.getElementById('search-filter');
    const sortSelect = document.getElementById('sort-select');
    const clearBtn = document.getElementById('clear-filters');
    const resultsCount = document.getElementById('results-count');

    let currentFilters = {
      search: '',
      sort: 'date-desc'
    };

    // Search filter
    searchInput.addEventListener('input', debounce(function(e) {
      currentFilters.search = e.target.value.toLowerCase();
      applyFilters();
    }, 300));

    // Sort
    sortSelect.addEventListener('change', function(e) {
      currentFilters.sort = e.target.value;
      applyFilters();
    });

    // Clear filters
    clearBtn.addEventListener('click', function() {
      searchInput.value = '';
      sortSelect.value = 'date-desc';
      currentFilters = { search: '', sort: 'date-desc' };
      applyFilters();
    });

    function applyFilters() {
      let visibleCount = 0;

      // Filter documents
      const filteredDocs = documents.filter(doc => {
        if (!currentFilters.search) return true;

        const text = doc.textContent.toLowerCase();
        const match = text.includes(currentFilters.search);

        doc.style.display = match ? '' : 'none';
        if (match) visibleCount++;

        return match;
      });

      // Sort documents
      sortDocuments(filteredDocs, currentFilters.sort);

      // Update count
      updateResultsCount(visibleCount, documents.length);
    }

    function sortDocuments(docs, sortBy) {
      docs.sort((a, b) => {
        switch(sortBy) {
          case 'date-desc':
            return compareDates(b, a);
          case 'date-asc':
            return compareDates(a, b);
          case 'title-asc':
            return compareText(a, b, 'h2');
          case 'title-desc':
            return compareText(b, a, 'h2');
          case 'source-asc':
            return compareText(a, b, '.meta');
          default:
            return 0;
        }
      });

      // Reorder in DOM
      const list = docs[0].parentNode;
      docs.forEach(doc => list.appendChild(doc));
    }

    function compareDates(a, b) {
      const dateA = a.querySelector('time')?.getAttribute('datetime') || '';
      const dateB = b.querySelector('time')?.getAttribute('datetime') || '';
      return dateA.localeCompare(dateB);
    }

    function compareText(a, b, selector) {
      const textA = a.querySelector(selector)?.textContent || '';
      const textB = b.querySelector(selector)?.textContent || '';
      return textA.localeCompare(textB);
    }

    function updateResultsCount(visible, total) {
      if (visible === total) {
        resultsCount.textContent = `Showing all ${total} documents`;
      } else {
        resultsCount.textContent = `Showing ${visible} of ${total} documents`;
      }
    }
  }

  // Debounce function for search input
  function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
      const later = () => {
        clearTimeout(timeout);
        func(...args);
      };
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  }
})();
