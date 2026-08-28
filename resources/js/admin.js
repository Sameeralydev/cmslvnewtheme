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

<<<<<<< HEAD
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
=======
    document.querySelectorAll('[data-toast]').forEach((toast) => {
        const close = toast.querySelector('[data-toast-close]');
        const dismiss = () => {
            if (toast.dataset.dismissed) return;
            toast.dataset.dismissed = '1';
            toast.classList.remove('toast-slide-in');
            toast.classList.add('toast-slide-out');
            window.setTimeout(() => toast.remove(), 350);
        };
        close?.addEventListener('click', dismiss);
        window.setTimeout(dismiss, 3000);
    });

    document.querySelectorAll('[data-modal-open]').forEach((button) => {
        button.addEventListener('click', () => {
            const modal = document.querySelector(`[data-modal="${button.dataset.modalOpen}"]`);
            if (!modal) return;
            modal.classList.add('is-open');
            document.body.classList.add('admin-modal-open');
        });
    });

    const closeModal = (modal) => {
        modal?.classList.remove('is-open');
        if (!document.querySelector('.admin-modal.is-open')) document.body.classList.remove('admin-modal-open');
    };
    document.querySelectorAll('[data-modal-close]').forEach((button) => {
        button.addEventListener('click', () => closeModal(button.closest('.admin-modal')));
    });
    document.querySelectorAll('.admin-modal').forEach((modal) => {
        modal.addEventListener('click', (event) => {
            if (event.target === modal) closeModal(modal);
        });
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') closeModal(document.querySelector('.admin-modal.is-open'));
    });
    if (document.querySelector('.admin-modal.is-open')) document.body.classList.add('admin-modal-open');

    const syllabusLabels = ['Branch', 'Term', 'Months', 'Class', 'Section', 'Subject', 'Status'];
    document.querySelectorAll('#syllabus-results th').forEach((header, index) => {
        const label = syllabusLabels[index];
        if (!label) return;
        const normalize = () => {
            header.textContent = `${label} ${header.dataset.order === 'asc' ? '\u25b2' : '\u25bc'}`;
        };
        normalize();
        header.addEventListener('click', normalize);
    });

    document.querySelectorAll('#curriculum-table th.cursor-pointer').forEach((header) => {
        const label = header.textContent.replace(/[\u2191\u2193\u2195\u25b2\u25bc]/g, '').replace(/\s*â.*$/g, '').replace(/[^A-Za-z0-9 /]+/g, '').trim();
        header.dataset.sortDirection = '';
        header.textContent = `${label} \u25bc`;
    });

    document.addEventListener('click', (event) => {
        const header = event.target.closest('#syllabus-results th, #directory-results th.cursor-pointer, #curriculum-table th.cursor-pointer');
        if (!header) return;
        event.preventDefault();
        event.stopImmediatePropagation();
        const table = header.closest('table');
        const index = [...header.parentElement.children].indexOf(header);
        const body = table.tBodies[0];
        const rows = [...body.rows].filter((row) => row.cells.length > 1);
        const ascending = header.dataset.sortDirection !== 'asc';
        table.querySelectorAll('th').forEach((cell) => { cell.dataset.sortDirection = ''; });
        header.dataset.sortDirection = ascending ? 'asc' : 'desc';
        rows.sort((a, b) => (a.cells[index]?.innerText || '').localeCompare(b.cells[index]?.innerText || '', undefined, {numeric: true, sensitivity: 'base'}) * (ascending ? 1 : -1));
        rows.forEach((row) => body.appendChild(row));
        const label = header.textContent.replace(/[\u2191\u2193\u2195\u25b2\u25bc]/g, '').replace(/\s*â.*$/g, '').trim();
        header.textContent = `${label} ${ascending ? '\u25b2' : '\u25bc'}`;
    }, true);

    document.querySelectorAll('[data-table-search]').forEach((input) => {
        input.addEventListener('input', () => {
            const table = document.getElementById(input.dataset.tableSearch);
            const query = input.value.toLowerCase();
            table?.tBodies[0].querySelectorAll('tr').forEach((row) => { row.hidden = !row.innerText.toLowerCase().includes(query); });
        });
    });
    const directoryToolbar = document.querySelector('.syllabus-directory-toolbar');
    const directoryTable = document.getElementById('directory-results');
    if (directoryToolbar && directoryTable) directoryTable.closest('section')?.insertBefore(directoryToolbar, directoryTable);
    document.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-table-export]');
        if (!button) return;
        const table = document.getElementById(button.dataset.table);
        if (!table) return;
        const rows = [...table.rows].map((row) => [...row.cells].map((cell) => cell.innerText.trim()).join('\t')).join('\n');
        if (button.dataset.tableExport === 'copy') await navigator.clipboard?.writeText(rows);
        if (button.dataset.tableExport === 'csv' || button.dataset.tableExport === 'excel') {
            const blob = new Blob([rows], {type: button.dataset.tableExport === 'csv' ? 'text/csv' : 'application/vnd.ms-excel'});
            const link = document.createElement('a'); link.href = URL.createObjectURL(blob); link.download = `syllabus.${button.dataset.tableExport === 'csv' ? 'csv' : 'xls'}`; link.click(); URL.revokeObjectURL(link.href);
        }
        if (button.dataset.tableExport === 'print') {
            if (button.dataset.table === 'directory-results') window.open(`/admin/academics/syllabus/print${window.location.search}`, '_blank');
            else window.print();
        }
    });
    document.addEventListener('click', (event) => {
        const button = event.target.closest('[data-table-columns]');
        if (!button) return;
        const table = document.getElementById(button.dataset.tableColumns);
        const headers = table ? [...table.tHead.rows[0].cells] : [];
        headers.forEach((header, index) => { header.style.display = header.style.display === 'none' ? '' : 'none'; [...table.rows].forEach((row) => { if (row.cells[index]) row.cells[index].style.display = header.style.display; }); });
    });

>>>>>>> 3ad0cb5a0f32fcd6b32268372f5f2da5b3c69c60
};

if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initializeAdminShell);
else initializeAdminShell();
