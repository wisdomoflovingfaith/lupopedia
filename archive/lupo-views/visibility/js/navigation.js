/**
 * Operational Visibility JavaScript
 * Basic interactions for navigation and UI
 * 
 * @author HEPHAESTUS (actor_id 59)
 * @thread 1030
 * @version 4.0.84
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize navigation
    initNavigation();
    initSorting();
    initTooltips();
});

/**
 * Initialize navigation features
 */
function initNavigation() {
    // Add active state to current navigation
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.breadcrumb a');
    
    navLinks.forEach(link => {
        if (link.getAttribute('href') === currentPath || 
            link.classList.contains('current')) {
            link.classList.add('active');
        }
    });
    
    // Handle back navigation
    const backButtons = document.querySelectorAll('[data-back]');
    backButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            window.history.back();
        });
    });
}

/**
 * Initialize sorting functionality
 */
function initSorting() {
    const sortButtons = document.querySelectorAll('.sort-btn');
    
    sortButtons.forEach(button => {
        button.addEventListener('click', function() {
            const sortType = this.dataset.sort;
            const url = new URL(window.location);
            
            // Update or remove sort parameter
            if (sortType) {
                url.searchParams.set('sort', sortType);
            } else {
                url.searchParams.delete('sort');
            }
            
            // Navigate to new URL
            window.location.href = url.toString();
        });
    });
    
    // Highlight current sort
    const currentSort = new URL(window.location).searchParams.get('sort');
    sortButtons.forEach(button => {
        if (button.dataset.sort === currentSort) {
            button.classList.add('active');
        }
    });
}

/**
 * Initialize tooltips and help text
 */
function initTooltips() {
    // Add hover effects to status indicators
    const statusCells = document.querySelectorAll('.status-active, .status-blocked, .status-pending');
    
    statusCells.forEach(cell => {
        cell.addEventListener('mouseenter', function() {
            this.style.cursor = 'help';
        });
        
        cell.addEventListener('mouseleave', function() {
            this.style.cursor = 'default';
        });
    });
    
    // Add click-to-copy for thread IDs
    const threadIds = document.querySelectorAll('[data-thread-id]');
    threadIds.forEach(element => {
        element.addEventListener('click', function() {
            const threadId = this.dataset.threadId;
            copyToClipboard(threadId);
            showCopyFeedback(this);
        });
    });
}

/**
 * Copy text to clipboard
 */
function copyToClipboard(text) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).catch(err => {
            console.error('Failed to copy text: ', err);
            fallbackCopyToClipboard(text);
        });
    } else {
        fallbackCopyToClipboard(text);
    }
}

/**
 * Fallback copy method for older browsers
 */
function fallbackCopyToClipboard(text) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.left = '-999999px';
    textArea.style.top = '-999999px';
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        document.execCommand('copy');
    } catch (err) {
        console.error('Fallback: Oops, unable to copy', err);
    }
    
    document.body.removeChild(textArea);
}

/**
 * Show visual feedback for copy action
 */
function showCopyFeedback(element) {
    const originalText = element.textContent;
    element.textContent = 'Copied!';
    element.style.color = '#28a745';
    
    setTimeout(() => {
        element.textContent = originalText;
        element.style.color = '';
    }, 1000);
}

/**
 * Handle table row highlighting
 */
function initTableInteractions() {
    const tableRows = document.querySelectorAll('tbody tr');
    
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f0f8ff';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
}

/**
 * Auto-refresh functionality (disabled for now)
 */
function initAutoRefresh() {
    // Auto-refresh disabled for read-only interface
    // Can be enabled later for real-time updates
    return false;
}

/**
 * Keyboard navigation
 */
document.addEventListener('keydown', function(e) {
    // Alt+C: Go to Channels Overview
    if (e.altKey && e.key === 'c') {
        e.preventDefault();
        window.location.href = '/visibility/';
    }
    
    // Alt+A: Go to Attention View
    if (e.altKey && e.key === 'a') {
        e.preventDefault();
        window.location.href = '/visibility/attention/';
    }
    
    // Escape: Go back
    if (e.key === 'Escape') {
        window.history.back();
    }
});

/**
 * Print functionality
 */
function printPage() {
    window.print();
}

/**
 * Export functionality (placeholder for future)
 */
function exportToCSV() {
    alert('Export functionality will be available in a future version.');
}

/**
 * Search functionality (placeholder for future)
 */
function initSearch() {
    // Search will be implemented in a future version
    return false;
}

// Utility functions
const Visibility = {
    copyToClipboard: copyToClipboard,
    printPage: printPage,
    exportToCSV: exportToCSV
};

// Make utilities available globally
window.Visibility = Visibility;
