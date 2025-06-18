// MSX International IT Portal - JavaScript Functions

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    initializeAnimations();
    initializeTooltips();
    initializeCounters();
    setupFormValidation();
});

// Animate elements on scroll
function initializeAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in-up');
            }
        });
    }, observerOptions);

    // Observe all cards and stats
    document.querySelectorAll('.service-card, .stat-card, .dashboard-card').forEach(card => {
        observer.observe(card);
    });
}

// Initialize Bootstrap tooltips
function initializeTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

// Animate counters
function initializeCounters() {
    const counters = document.querySelectorAll('.stat-card h4, .dashboard-card h3');
    
    counters.forEach(counter => {
        const target = parseInt(counter.textContent);
        const duration = 2000; // 2 seconds
        const increment = target / (duration / 16); // 60fps
        let current = 0;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                counter.textContent = target;
                clearInterval(timer);
            } else {
                counter.textContent = Math.floor(current);
            }
        }, 16);
    });
}

// Form validation
function setupFormValidation() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
}

// Show loading state
function showLoading(button) {
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Cargando...';
    button.disabled = true;
    
    setTimeout(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    }, 2000);
}

// Show notification
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Update statistics (for admin panel)
function updateStatistics() {
    const statsCards = document.querySelectorAll('.dashboard-card h3');
    
    statsCards.forEach(card => {
        const currentValue = parseInt(card.textContent);
        const newValue = currentValue + Math.floor(Math.random() * 3) - 1; // Random change -1 to +1
        
        if (newValue >= 0) {
            card.textContent = newValue;
            card.classList.add('text-success');
            setTimeout(() => card.classList.remove('text-success'), 1000);
        }
    });
}

// Search functionality
function searchTickets(query) {
    const tickets = document.querySelectorAll('.update-item');
    
    tickets.forEach(ticket => {
        const title = ticket.querySelector('h6').textContent.toLowerCase();
        const description = ticket.querySelector('p').textContent.toLowerCase();
        
        if (title.includes(query.toLowerCase()) || description.includes(query.toLowerCase())) {
            ticket.style.display = 'flex';
        } else {
            ticket.style.display = 'none';
        }
    });
}

// Dark mode toggle
function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
    localStorage.setItem('darkMode', document.body.classList.contains('dark-mode'));
}

// Initialize dark mode from localStorage
if (localStorage.getItem('darkMode') === 'true') {
    document.body.classList.add('dark-mode');
}

// Export functions for global use
window.MSXPortal = {
    showLoading,
    showNotification,
    updateStatistics,
    searchTickets,
    toggleDarkMode
};

// Keyboard shortcuts
document.addEventListener('keydown', function(event) {
    // Ctrl + D for dark mode
    if (event.ctrlKey && event.key === 'd') {
        event.preventDefault();
        toggleDarkMode();
    }
    
    // Ctrl + / for search
    if (event.ctrlKey && event.key === '/') {
        event.preventDefault();
        const searchInput = document.querySelector('input[type="search"]');
        if (searchInput) {
            searchInput.focus();
        }
    }
});

// Auto-refresh for admin panel
if (window.location.pathname.includes('admin.php')) {
    setInterval(() => {
        // Only update if page is visible
        if (!document.hidden) {
            updateStatistics();
        }
    }, 30000); // Every 30 seconds
}

// Service worker registration for PWA capabilities
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
        navigator.serviceWorker.register('/sw.js')
            .then(function(registration) {
                console.log('ServiceWorker registration successful');
            })
            .catch(function(err) {
                console.log('ServiceWorker registration failed');
            });
    });
} 