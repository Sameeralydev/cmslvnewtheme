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

    document.querySelectorAll('[data-date-picker]').forEach((input) => {
        const syncDate = () => input.classList.toggle('has-date', Boolean(input.value));
        input.addEventListener('input', syncDate);
        input.addEventListener('change', syncDate);
        input.addEventListener('click', () => input.showPicker?.());
        syncDate();
    });

    document.querySelectorAll('[data-time-picker]').forEach((input) => {
        input.addEventListener('click', () => input.showPicker?.());
    });
    const timeFrom = document.querySelector('[data-duration-from]');
    const timeTo = document.querySelector('[data-duration-to]');
    const duration = document.querySelector('[data-duration-badge]');
    const updateDuration = () => {
        if (!timeFrom?.value || !timeTo?.value || !duration) return duration && (duration.textContent = '');
        let minutes = (Number(timeTo.value.split(':')[0]) * 60 + Number(timeTo.value.split(':')[1])) - (Number(timeFrom.value.split(':')[0]) * 60 + Number(timeFrom.value.split(':')[1]));
        if (minutes < 0) minutes += 1440;
        duration.textContent = `${minutes} mins`;
    };
    timeFrom?.addEventListener('input', updateDuration); timeTo?.addEventListener('input', updateDuration);

    document.getElementById('directory-branch')?.addEventListener('change', () => {
        document.getElementById('directory-class')?.dispatchEvent(new Event('change'));
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

    const importClass = document.getElementById('curriculum-import-class');
    const importSubject = document.getElementById('curriculum-import-subject');
    if (importClass && importSubject) {
        importClass.addEventListener('change', async () => {
            const selected = importClass.value;
            importSubject.innerHTML = '<option value="">Loading...</option>';
            importSubject.disabled = true;
            if (!selected) {
                importSubject.innerHTML = '<option value="">Select</option>';
                importSubject.disabled = false;
                return;
            }
            try {
                const response = await fetch(importClass.dataset.subjectUrl.replace('__CLASS__', selected), {headers: {'Accept': 'application/json'}});
                if (!response.ok) throw new Error('Unable to load subjects');
                const subjects = await response.json();
                importSubject.innerHTML = '<option value="">Select</option>' + subjects.map((subject) => `<option value="${subject.id}">${subject.name}${subject.code ? ` (${subject.code})` : ''}</option>`).join('');
            } catch (error) {
                importSubject.innerHTML = '<option value="">Unable to load subjects</option>';
            } finally {
                importSubject.disabled = false;
            }
        });
    }

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

    document.querySelectorAll('#timetable-table th.cursor-pointer, #master-timetable-table th.cursor-pointer').forEach((header) => {
        const label = header.textContent.replace(/[\u2191\u2193\u2195\u25b2\u25bc]/g, '').trim();
        header.dataset.sortDirection = '';
        header.textContent = `${label} \u25bc`;
    });

    document.addEventListener('click', (event) => {
        const header = event.target.closest('#syllabus-results th, #directory-results th.cursor-pointer, #curriculum-table th.cursor-pointer, #time-allocation-table th.cursor-pointer, #timetable-table th.cursor-pointer, #master-timetable-table th.cursor-pointer');
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
    const timeAllocationPager = document.querySelector('#time-allocation-table + div > span:last-child');
    if (timeAllocationPager) { timeAllocationPager.className = 'list-pagination'; timeAllocationPager.innerHTML = '<button type="button" disabled aria-label="Previous page"><i class="fa fa-angle-left"></i></button><span>1</span><button type="button" disabled aria-label="Next page"><i class="fa fa-angle-right"></i></button>'; }
    const formatTime = (value) => {
        const [hours, minutes] = String(value || '').split(':').map(Number);
        if (Number.isNaN(hours) || Number.isNaN(minutes)) return value || '';
        const suffix = hours >= 12 ? 'PM' : 'AM';
        return `${hours % 12 || 12}:${String(minutes).padStart(2, '0')} ${suffix}`;
    };
    document.getElementById('timetable-branch')?.addEventListener('change', async (event) => {
        const slot = document.getElementById('timetable-slot');
        if (!slot || !event.target.value) return;
        slot.innerHTML = '<option value="">Loading...</option>';
        try {
            const url = slot.dataset.slotsUrl.replace('__BRANCH__', event.target.value);
            const slots = await fetch(url, {headers: {'X-Requested-With': 'XMLHttpRequest'}}).then((response) => response.json());
            slot.innerHTML = '<option value="">Select</option>' + slots.map((item) => `<option value="${item.id}">${item.slot} (${formatTime(item.start_time)} - ${formatTime(item.end_time)})</option>`).join('');
        } catch { slot.innerHTML = '<option value="">Unable to load slots</option>'; }
    });
    const directoryToolbar = document.querySelector('.syllabus-directory-toolbar');
    const directoryTable = document.getElementById('directory-results');
    if (directoryToolbar && directoryTable) directoryTable.closest('section')?.insertBefore(directoryToolbar, directoryTable);
    document.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-table-export]');
        if (!button) return;
        const table = document.getElementById(button.dataset.table);
        if (!table) return;
        event.preventDefault();
        const rows = [...table.rows].map((row) => [...row.cells].map((cell) => cell.innerText.trim()).join('\t')).join('\n');
        if (button.dataset.tableExport === 'copy') {
            try { await navigator.clipboard.writeText(rows); }
            catch { const helper = document.createElement('textarea'); helper.value = rows; document.body.appendChild(helper); helper.select(); document.execCommand('copy'); helper.remove(); }
        }
        if (button.dataset.tableExport === 'csv' || button.dataset.tableExport === 'excel') {
            const csvRows = [...table.rows].map((row) => [...row.cells].map((cell) => `"${cell.innerText.trim().replaceAll('"', '""')}"`).join(','));
            const isCsv = button.dataset.tableExport === 'csv';
            const blob = new Blob([isCsv ? '\ufeff' + csvRows.join('\n') : rows], {type: isCsv ? 'text/csv;charset=utf-8' : 'application/vnd.ms-excel;charset=utf-8'});
            const link = document.createElement('a'); link.href = URL.createObjectURL(blob); link.download = `time-allocation.${isCsv ? 'csv' : 'xls'}`; document.body.appendChild(link); link.click(); link.remove(); setTimeout(() => URL.revokeObjectURL(link.href), 1000);
        }
        if (button.dataset.tableExport === 'print') {
            if (button.dataset.table === 'directory-results' || button.dataset.table === 'syllabus-results') window.open(`/admin/academics/syllabus/print${window.location.search}`, '_blank');
            else if (button.dataset.table === 'time-allocation-table') window.open(`/admin/academics/timetable/timeallocation/print${window.location.search}`, '_blank');
            else if (button.dataset.table === 'timetable-table') window.open(`/admin/academics/timetable/print${window.location.search}`, '_blank');
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

    const csvValue = (value) => `"${String(value).replaceAll('"', '""')}"`;
    const finishCurriculumPrint = () => {
        document.body.classList.remove('is-printing-curriculum');
        document.querySelectorAll('#curriculum-table .print-hide-column').forEach((cell) => cell.classList.remove('print-hide-column'));
    };

    const printCurriculumTable = (table, title) => {
        const printableTable = table.cloneNode(true);
        const headers = [...printableTable.tHead.rows[0].cells];
        const actionIndex = headers.findIndex((cell) => cell.textContent.trim().toLowerCase() === 'action');
        if (actionIndex >= 0) [...printableTable.rows].forEach((row) => row.cells[actionIndex]?.remove());
        printableTable.querySelectorAll('.curriculum-actions, .float-right').forEach((element) => element.remove());
        printableTable.querySelectorAll('th.cursor-pointer').forEach((header) => {
            header.textContent = header.textContent.replace(/[\u2191\u2193\u2195\u25b2\u25bc]/g, '').trim();
        });

        const printWindow = window.open('', '_blank', 'width=1000,height=700');
        if (!printWindow) return;
        printWindow.document.open();
        printWindow.document.write(`<!doctype html><html><head><meta charset="utf-8"><title>${title}</title><link rel="stylesheet" href="${window.location.origin}/assets/css/curriculum-print-table.css"></head><body><h1>${title}</h1>${printableTable.outerHTML}</body></html>`);
        printWindow.document.close();
        printWindow.addEventListener('load', () => {
            printWindow.focus();
            printWindow.print();
        }, {once: true});
    };

    window.addEventListener('afterprint', finishCurriculumPrint);
    document.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-export]');
        if (!button) return;

        event.preventDefault();
        event.stopImmediatePropagation();
        const table = button.closest('.curriculum-print-section')?.querySelector('#curriculum-table');
        if (!table) return;
        const visibleCells = (row) => [...row.cells].filter((cell) => cell.style.display !== 'none');
        const rows = [...table.rows].map((row) => visibleCells(row).map((cell) => cell.innerText.trim()));

        if (button.dataset.export === 'copy') {
            await navigator.clipboard?.writeText(rows.map((row) => row.join('\t')).join('\n'));
            return;
        }
        if (button.dataset.export === 'csv' || button.dataset.export === 'excel') {
            const csv = rows.map((row) => row.map(csvValue).join(',')).join('\r\n');
            const type = button.dataset.export === 'csv' ? 'text/csv;charset=utf-8' : 'application/vnd.ms-excel';
            const link = document.createElement('a');
            link.href = URL.createObjectURL(new Blob([csv], {type}));
            link.download = `curriculum.${button.dataset.export === 'csv' ? 'csv' : 'xls'}`;
            link.click();
            URL.revokeObjectURL(link.href);
            return;
        }
        if (button.dataset.export === 'pdf' || button.dataset.export === 'print') {
            const title = button.closest('.curriculum-print-section')?.querySelector('h2')?.textContent?.trim() || 'Curriculum List';
            printCurriculumTable(table, title);
        }
    }, true);

    document.addEventListener('click', (event) => {
        const button = event.target.closest('[data-column-toggle]');
        if (!button) return;
        event.preventDefault();
        event.stopImmediatePropagation();
        document.querySelectorAll('.curriculum-column-menu').forEach((menu) => menu.remove());
        const table = document.getElementById(button.dataset.columnToggle);
        if (!table?.tHead) return;
        const menu = document.createElement('div');
        menu.className = 'curriculum-column-menu';
        [...table.tHead.rows[0].cells].forEach((header, index) => {
            const label = document.createElement('label');
            label.className = 'flex cursor-pointer items-center gap-2 px-2 py-1';
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox'; checkbox.checked = header.style.display !== 'none'; checkbox.dataset.column = String(index);
            label.append(checkbox, document.createTextNode(` ${header.innerText.replace(/[\u2191\u2193\u2195\u25b2\u25bc]/g, '').trim()}`));
            menu.appendChild(label);
        });
        button.parentElement.classList.add('relative');
        button.parentElement.appendChild(menu);
        menu.addEventListener('change', (changeEvent) => {
            const index = Number(changeEvent.target.dataset.column);
            const visible = changeEvent.target.checked;
            [...table.rows].forEach((row) => { if (row.cells[index]) row.cells[index].style.display = visible ? '' : 'none'; });
        });
        document.addEventListener('click', (closeEvent) => { if (!menu.contains(closeEvent.target) && closeEvent.target !== button) menu.remove(); }, {once: true});
    }, true);

};

if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initializeAdminShell);
else initializeAdminShell();
