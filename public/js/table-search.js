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
            const tableId = this.closest('[data-table]').dataset.table;
            const tab = this.dataset.tab;
            const url = this.href.split('?')[0]; // Get base URL without query params
            
            // Update active tab
            document.querySelectorAll(`[data-table="${tableId}"] [data-tab-switch]`).forEach(tabLink => {
                tabLink.classList.remove('border-[#1e6031]', 'text-[#1e6031]', 'font-semibold');
                tabLink.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            });
            this.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            this.classList.add('border-[#1e6031]', 'text-[#1e6031]', 'font-semibold');
            
            // Get search term if exists
            const searchInput = document.querySelector(`.table-search-input[data-table-id="${tableId}"]`);
            const searchTerm = searchInput ? searchInput.value : '';
            
            // Make AJAX request
            makeAjaxRequest(url, searchTerm, tab, tableId);
        });
    });
});

// Trigger search function
function triggerSearch(inputElement) {
    const searchTerm = inputElement.value;
    const tableId = inputElement.dataset.tableId;
    const url = inputElement.dataset.url;
    
    // Get current tab if exists
    const activeTab = document.querySelector(`[data-table="${tableId}"] .border-\[\#1e6031\]`);
    const tab = activeTab ? activeTab.dataset.tab : 'pending';
    
    // Make AJAX request
    makeAjaxRequest(url, searchTerm, tab, tableId);
}

// Make AJAX request function
function makeAjaxRequest(url, searchTerm, tab, tableId) {
    // Show loading indicator
    const tableBody = document.querySelector(`#${tableId} tbody`);
    if (tableBody) {
        tableBody.innerHTML = '<tr><td colspan="10" class="px-6 py-4 text-center"><div class="flex justify-center"><div class="animate-spin rounded-full h-6 w-6 border-b-2 border-[#1e6031]"></div></div></td></tr>';
    }
    
    // Make AJAX request
    fetch(`${url}?search=${encodeURIComponent(searchTerm)}&tab=${tab}&ajax=1`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.text())
    .then(html => {
        // Update the table body
        const tableBody = document.querySelector(`#${tableId} tbody`);
        if (tableBody) {
            tableBody.innerHTML = html;
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