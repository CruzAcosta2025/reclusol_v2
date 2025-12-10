/**
 * Sidebar Active Item Logic
 * Detects current page and highlights the active sidebar item
 */

export function initSidebar() {
    const currentUrl = window.location.href;
    const currentPath = window.location.pathname;

    // First, remove all active states
    document.querySelectorAll('[data-sidebar-link], [data-sidebar-parent]').forEach(el => {
        el.classList.remove('bg-M1');
        el.removeAttribute('data-active');
    });

    // Get all sidebar links
    const sidebarLinks = document.querySelectorAll('[data-sidebar-link]');
    let activeFound = false;

    sidebarLinks.forEach(link => {
        const href = link.getAttribute('href');

        // Skip placeholder links
        if (href === '#') return;

        // Extract path from href
        const linkPath = new URL(href, window.location.origin).pathname;

        // Check if current path matches link path exactly
        if (currentPath === linkPath || currentPath.includes(linkPath)) {
            // Mark link as active
            link.classList.add('bg-M1');
            link.setAttribute('data-active', 'true');
            activeFound = true;

            // If link has a parent button (for grouped items), mark parent as active and expanded
            const parentGroup = link.closest('[data-sidebar-group]');
            if (parentGroup) {
                const parentButton = parentGroup.querySelector('[data-sidebar-parent]');
                if (parentButton) {
                    parentButton.classList.add('bg-M1');
                    parentButton.setAttribute('data-active', 'true');

                    // Mark parent group as expanded
                    if (parentGroup.__x && parentGroup.__x.$data) {
                        parentGroup.__x.$data.expanded = true;
                    }
                }
            }
        }
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSidebar);
} else {
    initSidebar();
}

window.addEventListener('popstate', initSidebar);
