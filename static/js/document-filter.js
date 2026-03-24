/**
 * Document Filtering and Sorting
 * Provides client-side filtering and sorting for document archive table
 */

(function() {
  'use strict';

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  function init() {
    const table = document.querySelector('.document-table');
    if (!table) return;

    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr.document-item'));

    // Insert controls above the table
    const controls = createFilterControls(rows.length);
    table.parentNode.insertBefore(controls, table);

    // Sort state matches the default order Hugo renders (date desc)
    let sortField = 'date';
    let sortDir = 'desc';
    let searchTerm = '';

    // Column header click-to-sort
    table.querySelectorAll('th[data-sort]').forEach(th => {
      th.addEventListener('click', () => {
        const field = th.dataset.sort;
        if (sortField === field) {
          sortDir = sortDir === 'asc' ? 'desc' : 'asc';
        } else {
          sortField = field;
          sortDir = field === 'date' ? 'desc' : 'asc';
        }
        updateSortIcons(table, sortField, sortDir);
        syncSortSelect(sortField, sortDir);
        applyFilters();
      });

      th.addEventListener('keydown', e => {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          th.click();
        }
      });
    });

    // Search input
    const searchInput = document.getElementById('search-filter');
    if (searchInput) {
      searchInput.addEventListener('input', debounce(e => {
        searchTerm = e.target.value.toLowerCase();
        applyFilters();
      }, 300));
    }

    // Sort select (mirrors column header state; useful on mobile)
    const sortSelect = document.getElementById('sort-select');
    if (sortSelect) {
      sortSelect.addEventListener('change', e => {
        const parts = e.target.value.split('-');
        sortDir = parts.pop();
        sortField = parts.join('-');
        updateSortIcons(table, sortField, sortDir);
        applyFilters();
      });
    }

    // Clear button
    const clearBtn = document.getElementById('clear-filters');
    if (clearBtn) {
      clearBtn.addEventListener('click', () => {
        if (searchInput) searchInput.value = '';
        if (sortSelect) sortSelect.value = 'date-desc';
        searchTerm = '';
        sortField = 'date';
        sortDir = 'desc';
        updateSortIcons(table, sortField, sortDir);
        applyFilters();
      });
    }

    function applyFilters() {
      const visible = rows.filter(row => {
        const show = !searchTerm || row.textContent.toLowerCase().includes(searchTerm);
        row.style.display = show ? '' : 'none';
        return show;
      });

      visible.sort((a, b) => {
        const valA = (a.dataset[sortField] || '').toLowerCase();
        const valB = (b.dataset[sortField] || '').toLowerCase();
        const cmp = valA.localeCompare(valB);
        return sortDir === 'asc' ? cmp : -cmp;
      });

      visible.forEach(row => tbody.appendChild(row));
      updateCount(visible.length, rows.length);
    }

    function updateCount(visible, total) {
      const el = document.getElementById('results-count');
      if (!el) return;
      el.textContent = visible === total
        ? `Showing all ${total} documents`
        : `Showing ${visible} of ${total} documents`;
    }
  }

  function createFilterControls(total) {
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
            <option value="title-asc">Title (A–Z)</option>
            <option value="title-desc">Title (Z–A)</option>
            <option value="source-asc">Source (A–Z)</option>
          </select>
        </div>
        <div class="filter-group">
          <button id="clear-filters" class="btn-secondary" aria-label="Clear all filters">Clear Filters</button>
        </div>
      </div>
      <div id="results-count" class="results-count" role="status" aria-live="polite">Showing all ${total} documents</div>
    `;
    return controls;
  }

  function updateSortIcons(table, activeField, dir) {
    table.querySelectorAll('th[data-sort]').forEach(th => {
      const icon = th.querySelector('.sort-icon');
      if (!icon) return;
      if (th.dataset.sort === activeField) {
        icon.textContent = dir === 'asc' ? ' ↑' : ' ↓';
        th.setAttribute('aria-sort', dir === 'asc' ? 'ascending' : 'descending');
      } else {
        icon.textContent = '';
        th.setAttribute('aria-sort', 'none');
      }
    });
  }

  function syncSortSelect(field, dir) {
    const select = document.getElementById('sort-select');
    if (!select) return;
    const val = `${field}-${dir}`;
    if (select.querySelector(`option[value="${val}"]`)) {
      select.value = val;
    }
  }

  function debounce(func, wait) {
    let timeout;
    return function(...args) {
      clearTimeout(timeout);
      timeout = setTimeout(() => func.apply(this, args), wait);
    };
  }

})();
