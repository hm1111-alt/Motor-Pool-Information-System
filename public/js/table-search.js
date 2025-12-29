document.addEventListener('DOMContentLoaded', function() {
    // Handle search input for all tables with search functionality
    const searchInputs = document.querySelectorAll('.table-search-input');
    searchInputs.forEach(input => {
        // Clear search functionality
        const clearButton = input.parentNode.querySelector('.clear-search');
        if (clearButton) {
            clearButton.addEventListener('click', function() {
                input.value = '';
                triggerSearch(input);
            });
        }
        
        // Search on input
        input.addEventListener('keyup', debounce(function() {
            triggerSearch(this);
        }, 300)); // Debounce for 300ms
    });
    
    // Handle tab switching with AJAX
    const tabLinks = document.querySelectorAll('[data-tab-switch]');
    tabLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            // Get the table ID from the search input's data-table-id attribute
            const searchInput = document.querySelector('.table-search-input');
            let tableId = '';
            if (searchInput && searchInput.dataset.tableId) {
                tableId = searchInput.dataset.tableId;
            } else {
                // Fallback: try to find the table ID from the table element
                const table = document.querySelector('table[id]');
                if (table) {
                    tableId = table.id;
                }
            }
            
            const tab = this.dataset.tabSwitch;  // Changed from dataset.tab to dataset.tabSwitch
            const url = this.href.split('?')[0]; // Get base URL without query params
            
            // Update active tab
            document.querySelectorAll('[data-tab-switch]').forEach(tabLink => {
                tabLink.classList.remove('border-[#1e6031]', 'text-[#1e6031]', 'font-semibold');
                tabLink.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            });
            this.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            this.classList.add('border-[#1e6031]', 'text-[#1e6031]', 'font-semibold');
            
            // Get search term if exists
            const searchTerm = searchInput ? searchInput.value : '';
            
            // Make AJAX request
            makeAjaxRequest(url, searchTerm, tab, tableId);
        });
    });
    
    // Handle pagination links with AJAX
    document.addEventListener('click', function(e) {
        if (e.target.closest('a[href*="page="]')) {
            e.preventDefault();
            
            // Find the table ID from the current page context
            const table = document.querySelector('table[id]');
            const searchInput = document.querySelector('.table-search-input');
            
            let tableId = '';
            if (searchInput && searchInput.dataset.tableId) {
                tableId = searchInput.dataset.tableId;
            } else if (table) {
                tableId = table.id;
            }
            
            // Get the URL from the clicked pagination link
            const url = e.target.closest('a[href*="page="]').href;
            
            // Get the search term if exists
            const searchTerm = searchInput ? searchInput.value : '';
            
            // Get the tab if exists
            const activeTab = document.querySelector('.border-\\[\\#1e6031\\]');
            const tab = activeTab ? activeTab.dataset.tabSwitch : 'pending';
            
            // Make AJAX request
            makeAjaxRequest(url, searchTerm, tab, tableId);
        }
    });
});

// Trigger search function
function triggerSearch(inputElement) {
    const searchTerm = inputElement.value;
    const tableId = inputElement.dataset.tableId;
    const url = inputElement.dataset.url;
    
    // Get current tab if exists
    const activeTab = document.querySelector('.border-\\[\\#1e6031\\]');
    const tab = activeTab ? activeTab.dataset.tabSwitch : 'pending';
    
    // Make AJAX request
    makeAjaxRequest(url, searchTerm, tab, tableId);
}

// Make AJAX request function
function makeAjaxRequest(url, searchTerm, tab, tableId) {
    // Show loading indicator in table body
    const tableBody = document.querySelector(`#${tableId} tbody`);
    if (tableBody) {
        tableBody.innerHTML = '<tr><td colspan="10" class="px-6 py-4 text-center"><div class="flex justify-center"><div class="animate-spin rounded-full h-6 w-6 border-b-2 border-[#1e6031]"></div></div></td></tr>';
    }
    
    // Make AJAX request - request JSON response
    fetch(`${url}?search=${encodeURIComponent(searchTerm)}&tab=${tab}&ajax=1`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        // Update the table body
        const tableBody = document.querySelector(`#${tableId} tbody`);
        if (tableBody && data.table_body) {
            tableBody.innerHTML = data.table_body;
        }
        
        // Update pagination if it exists
        const paginationSection = document.querySelector(`#pagination-section, #${tableId.replace('-', '')}-pagination-section`);
        if (paginationSection && data.pagination) {
            paginationSection.innerHTML = data.pagination;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Show error message
        const tableBody = document.querySelector(`#${tableId} tbody`);
        if (tableBody) {
            tableBody.innerHTML = '<tr><td colspan="10" class="px-6 py-4 text-center text-red-500">An error occurred while loading data. Please try again.</td></tr>';
        }
    });
}

// Debounce function to limit AJAX requests
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