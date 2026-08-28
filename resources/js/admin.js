import '../css/app.css';

const initializeAdminShell = () => {
    document.documentElement.classList.remove('dark');
    localStorage.removeItem('theme');

    document.querySelectorAll('[data-sidebar-toggle]').forEach((toggle) => {
        const target = document.getElementById(toggle.dataset.sidebarTarget);
        if (!target) return;

        toggle.addEventListener('click', (event) => {
            if (toggle.dataset.sidebarLink === 'true') return;
            event.preventDefault();
            const willOpen = !target.classList.contains('is-open');
            document.querySelectorAll('.admin-sidebar-tree.is-open').forEach((openTree) => openTree.classList.remove('is-open'));
            document.querySelectorAll('[data-sidebar-toggle][aria-expanded="true"]').forEach((openToggle) => openToggle.setAttribute('aria-expanded', 'false'));
            target.classList.toggle('is-open', willOpen);
            toggle.setAttribute('aria-expanded', String(willOpen));
        });
    });

    document.querySelectorAll('[data-sidebar-menu]').forEach((button) => {
        button.addEventListener('click', () => document.querySelector('.admin-sidebar')?.classList.toggle('is-open'));
    });

    const profileToggle = document.querySelector('[data-profile-toggle]');
    const profileDropdown = document.querySelector('[data-profile-dropdown]');
    if (profileToggle && profileDropdown) {
        const closeProfile = () => {
            profileDropdown.hidden = true;
            profileToggle.setAttribute('aria-expanded', 'false');
        };

        profileToggle.addEventListener('click', (event) => {
            event.stopPropagation();
            const isOpen = !profileDropdown.hidden;
            profileDropdown.hidden = isOpen;
            profileToggle.setAttribute('aria-expanded', String(!isOpen));
        });

        document.addEventListener('click', (event) => {
            if (!profileDropdown.contains(event.target) && !profileToggle.contains(event.target)) closeProfile();
        });
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') closeProfile();
        });
    }

    /* Laravel Toast Functions */
    window.hideToast = function () {
        const toast = document.getElementById('appToast');
        if (!toast) return;

        toast.classList.remove('toast-slide-in');
        toast.classList.add('toast-slide-out');

        setTimeout(() => {
            toast.remove();
        }, 350);
    };

    const toast = document.getElementById('appToast');
    if (toast) {
        setTimeout(() => {
            window.hideToast();
        }, 3000);
    }
};

if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initializeAdminShell);
else initializeAdminShell();
