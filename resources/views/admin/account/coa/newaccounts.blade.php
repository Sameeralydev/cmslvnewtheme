@extends('admin.layouts.app')

@section('title', $title)

@section('content')
    <div class="legacy-coa">
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix">Accounts Type List</h3>
                            <button type="button" class="btn-add-modal" onclick="openAddAccountTypeModal()">
                                <i class="fa fa-plus"></i> Add
                            </button>
                        </div>
                        <div class="box-body">
                            <div class="legacy-datatable-toolbar">
                                <input type="search" id="accTypeSearchInput" placeholder="Search..." autocomplete="off">
                                <div class="legacy-datatable-icons">
                                    <span id="btnCopyAccType" title="Copy"><i class="fa fa-copy"></i></span>
                                    <span id="btnCsvAccType" title="CSV"><i class="fa fa-file-csv"></i></span>
                                    <span id="btnExcelAccType" title="Excel"><i class="fa fa-file-text"></i></span>
                                    <span id="btnPdfAccType" title="PDF"><i class="fa fa-file-pdf"></i></span>
                                    <span id="btnPrintAccType" title="Print"><i class="fa fa-print"></i></span>
                                    <span id="btnColumnsAccType" title="Columns"><i class="fa fa-table-list"></i></span>
                                </div>
                            </div>
                            <table class="table table-striped table-bordered table-hover example" id="accTypeTable">
                                <thead>
                                    <tr>
                                        <th class="sortable" data-sort-col="type" style="width: 45%; cursor: pointer;" title="Sort by Account Type">
                                            Account Type <i class="fa fa-sort pull-right" style="margin-top:2px; opacity:0.8;"></i>
                                        </th>
                                        <th class="sortable" data-sort-col="name" style="width: 45%; cursor: pointer;" title="Sort by Account Name">
                                            Account Name <i class="fa fa-sort pull-right" style="margin-top:2px; opacity:0.8;"></i>
                                        </th>
                                        <th class="text-right noExport" style="width: 10%; text-align: right;">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="accTypeTableBody">
                                    @forelse ($hierarchy as $type)
                                        <tr class="group-header-row" data-type-name="{{ strtolower($type->name) }}" data-type-code="{{ $type->code }}">
                                            <td class="mailbox-name"><strong>{{ $type->code }}. {{ $type->name }}</strong></td>
                                            <td class="mailbox-name"></td>
                                            <td class="mailbox-name"></td>
                                        </tr>
                                        @foreach ($type->newaccounts as $newAccount)
                                            <tr class="item-row" data-search="{{ strtolower(($type->name ?? '') . ' ' . ($type->code ?? '') . ' ' . ($newAccount->name ?? '') . ' ' . ($newAccount->code ?? '')) }}">
                                                <td class="mailbox-name"></td>
                                                <td class="mailbox-name" style="padding-left: 20px;">{{ $newAccount->code }}. {{ $newAccount->name }}</td>
                                                <td class="mailbox-date text-right" style="text-align: right; white-space: nowrap; width: 10%;">
                                                    @unless ((bool) ($newAccount->is_system ?? false))
                                                        <a href="javascript:void(0)" onclick="openEditAccountTypeModal('{{ $newAccount->id }}', '{{ $type->id }}', '{{ addslashes($newAccount->name) }}', '{{ addslashes($newAccount->note ?? '') }}')" class="btn btn-primary btn-xs" title="Edit">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                    @endunless
                                                </td>
                                            </tr>
                                        @endforeach
                                    @empty
                                        <tr id="emptyRow">
                                            <td colspan="3" class="text-center" style="padding: 15px; color: #777;">No account type records found, or the legacy tables are not available in this environment.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- Modern Add / Edit Modal Popup --}}
    <div class="coa-modal-backdrop" id="accountTypeModal" onclick="handleBackdropClick(event)">
        <div class="coa-modal-dialog modal-dialog-md">
            <div class="coa-modal-header">
                <h4 class="coa-modal-title" id="accountTypeModalTitle">
                    {{ $account ? 'Edit Accounts Type' : 'Add Accounts Type' }}
                </h4>
                <button type="button" class="coa-modal-close" onclick="closeAddAccountTypeModal()" aria-label="Close">&times;</button>
            </div>

            <form id="accountTypeForm" action="{{ $account ? route('admin.account.accounts.newaccounts.update', $account->id, false) : route('admin.account.accounts.newaccounts.store', absolute: false) }}" method="post" accept-charset="utf-8">
                @csrf
                <input type="hidden" name="id" id="modalAccountId" value="{{ $account->id ?? '' }}">

                <div class="coa-modal-body">
                    <div class="form-group">
                        <label>Accounts Head <span class="req">*</span></label>
                        <select id="modal_accounts_type_id" name="accounts_type_id" class="form-control selectval" required>
                            <option value="">Select</option>
                            @foreach ($accountTypes as $accountType)
                                <option value="{{ $accountType->id }}" @selected((string) old('accounts_type_id', $account->accounts_type_id ?? '') === (string) $accountType->id)>{{ $accountType->name }}</option>
                            @endforeach
                        </select>
                        @error('accounts_type_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Account Type Name <span class="req">*</span></label>
                        <input id="modal_name" name="name" type="text" class="form-control" value="{{ old('name', $account->name ?? '') }}" placeholder="Enter account type name" required>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" id="modal_description" name="description" rows="3" placeholder="Enter description...">{{ old('description', $account->note ?? '') }}</textarea>
                    </div>
                </div>

                <div class="coa-modal-footer">
                    <button type="button" class="btn-modal-cancel" onclick="closeAddAccountTypeModal()">Cancel</button>
                    <button type="submit" class="btn-modal-save" id="modalSubmitBtn">{{ $account ? 'Update' : 'Save' }}</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Toast Notification --}}
    <div id="accToast" style="display: none; position: fixed; bottom: 25px; right: 25px; background: #24448d; color: #fff; padding: 10px 18px; border-radius: 4px; font-size: 13px; font-weight: 600; box-shadow: 0 4px 12px rgba(0,0,0,0.25); z-index: 99999;">
        Table copied to clipboard!
    </div>
@endsection

@push('scripts')
<script src="{{ asset('assets/dist/datatables/js/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/dist/datatables/js/vfs_fonts.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var searchInput = document.getElementById('accTypeSearchInput');
        var table = document.getElementById('accTypeTable');
        var tbody = document.getElementById('accTypeTableBody');
        var toast = document.getElementById('accToast');

        function showToast(msg) {
            if (!toast) return;
            toast.innerText = msg;
            toast.style.display = 'block';
            setTimeout(function () { toast.style.display = 'none'; }, 2200);
        }

        // 1. Live Search Filter
        if (searchInput && tbody) {
            searchInput.addEventListener('input', function () {
                var filter = this.value.toLowerCase().trim();
                var rows = Array.from(tbody.querySelectorAll('tr:not(#emptyRow):not(#noMatchRow)'));

                if (!filter) {
                    rows.forEach(function (r) { r.style.display = ''; });
                    var noMatch = document.getElementById('noMatchRow');
                    if (noMatch) noMatch.style.display = 'none';
                    return;
                }

                var currentGroup = null;
                var groupHasVisibleChild = false;
                var totalVisible = 0;

                rows.forEach(function (row) {
                    if (row.classList.contains('group-header-row')) {
                        if (currentGroup && !groupHasVisibleChild) {
                            currentGroup.style.display = 'none';
                        }
                        currentGroup = row;
                        groupHasVisibleChild = false;
                        var groupText = (row.getAttribute('data-type-name') || '') + ' ' + (row.getAttribute('data-type-code') || '');
                        if (groupText.indexOf(filter) > -1) {
                            row.style.display = '';
                            groupHasVisibleChild = true;
                            totalVisible++;
                        } else {
                            row.style.display = '';
                        }
                    } else if (row.classList.contains('item-row')) {
                        var search = row.getAttribute('data-search') || row.innerText.toLowerCase();
                        if (search.indexOf(filter) > -1) {
                            row.style.display = '';
                            groupHasVisibleChild = true;
                            totalVisible++;
                            if (currentGroup) currentGroup.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    }
                });

                if (currentGroup && !groupHasVisibleChild) {
                    currentGroup.style.display = 'none';
                }

                var noMatchRow = document.getElementById('noMatchRow');
                if (totalVisible === 0) {
                    if (!noMatchRow) {
                        noMatchRow = document.createElement('tr');
                        noMatchRow.id = 'noMatchRow';
                        noMatchRow.innerHTML = '<td colspan="3" class="text-center" style="padding:15px; color:#777;">No matching records found</td>';
                        tbody.appendChild(noMatchRow);
                    }
                    noMatchRow.style.display = '';
                } else if (noMatchRow) {
                    noMatchRow.style.display = 'none';
                }
            });
        }

        // 2. Action: Copy to Clipboard
        var btnCopy = document.getElementById('btnCopyAccType');
        if (btnCopy && table) {
            btnCopy.addEventListener('click', function () {
                var rows = Array.from(table.querySelectorAll('tr'));
                var text = '';
                rows.forEach(function (r) {
                    if (r.style.display === 'none' || r.id === 'noMatchRow' || r.id === 'emptyRow') return;
                    var cells = r.querySelectorAll('th, td');
                    var rowData = [];
                    cells.forEach(function (c, idx) {
                        if (idx < cells.length - 1) {
                            rowData.push(c.innerText.trim());
                        }
                    });
                    text += rowData.join("\t") + "\n";
                });

                if (navigator.clipboard) {
                    navigator.clipboard.writeText(text).then(function () {
                        showToast('Table copied to clipboard!');
                    });
                } else {
                    var textarea = document.createElement('textarea');
                    textarea.value = text;
                    document.body.appendChild(textarea);
                    textarea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textarea);
                    showToast('Table copied to clipboard!');
                }
            });
        }

        // 3. Action: CSV Export
        function exportCSV(filename) {
            if (!table) return;
            var rows = Array.from(table.querySelectorAll('tr'));
            var csv = "\uFEFF\"Account Type\",\"Account Name\"\n";
            rows.forEach(function (r) {
                if (r.style.display === 'none' || r.id === 'noMatchRow' || r.id === 'emptyRow' || r.parentElement.tagName === 'THEAD') return;
                var cells = r.querySelectorAll('td');
                if (cells.length >= 2) {
                    var c0 = '"' + cells[0].innerText.trim().replace(/"/g, '""') + '"';
                    var c1 = '"' + cells[1].innerText.trim().replace(/"/g, '""') + '"';
                    csv += c0 + ',' + c1 + "\n";
                }
            });

            var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            var link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = filename;
            link.style.display = 'none';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            showToast('CSV downloaded successfully!');
        }

        var btnCsv = document.getElementById('btnCsvAccType');
        if (btnCsv) {
            btnCsv.addEventListener('click', function () {
                exportCSV('Accounts_Type_List.csv');
            });
        }

        // 4. Action: Excel Export
        var btnExcel = document.getElementById('btnExcelAccType');
        if (btnExcel) {
            btnExcel.addEventListener('click', function () {
                exportCSV('Accounts_Type_List.xls');
            });
        }

        // 5. Action: PDF Download matching exact format
        function generatePdfDownload() {
            if (typeof pdfMake === 'undefined') {
                window.print();
                return;
            }

            var tableBody = [];
            tableBody.push([
                { text: 'Account Type', bold: true, fillColor: '#2F5DA8', color: '#ffffff', fontSize: 10 },
                { text: 'Account Name', bold: true, fillColor: '#2F5DA8', color: '#ffffff', fontSize: 10 }
            ]);

            var rows = Array.from(tbody.querySelectorAll('tr:not(#emptyRow):not(#noMatchRow)'));
            var itemIndex = 0;

            rows.forEach(function (r) {
                if (r.style.display === 'none') return;
                var cells = r.querySelectorAll('td');
                if (cells.length < 2) return;

                var isGroup = r.classList.contains('group-header-row');
                var c0 = cells[0].innerText.trim();
                var c1 = cells[1].innerText.trim();

                var bg = isGroup ? '#ffffff' : (itemIndex % 2 === 1 ? '#f4f6f8' : '#ffffff');
                if (!isGroup) itemIndex++;

                tableBody.push([
                    { text: c0, bold: isGroup, fillColor: bg, fontSize: 9.5, color: '#333333' },
                    { text: c1, bold: false, fillColor: bg, fontSize: 9.5, color: '#333333' }
                ]);
            });

            var docDefinition = {
                pageOrientation: 'portrait',
                pageSize: 'A4',
                pageMargins: [40, 40, 40, 40],
                content: [
                    {
                        text: 'Accounts Type List',
                        fontSize: 16,
                        bold: true,
                        color: '#111827',
                        margin: [0, 0, 0, 16]
                    },
                    {
                        table: {
                            headerRows: 1,
                            widths: ['35%', '65%'],
                            body: tableBody
                        },
                        layout: {
                            hLineWidth: function (i, node) {
                                return (i === 0 || i === 1 || i === node.table.body.length) ? 0.8 : 0;
                            },
                            vLineWidth: function () {
                                return 0;
                            },
                            hLineColor: function (i) {
                                return i === 1 ? '#2F5DA8' : '#e5e7eb';
                            },
                            paddingLeft: function () { return 8; },
                            paddingRight: function () { return 8; },
                            paddingTop: function () { return 5.5; },
                            paddingBottom: function () { return 5.5; }
                        }
                    }
                ],
                defaultStyle: {
                    font: 'Roboto'
                }
            };

            pdfMake.createPdf(docDefinition).download('Accounts Type List.pdf');
            showToast('Accounts Type List.pdf downloaded!');
        }

        var btnPdf = document.getElementById('btnPdfAccType');
        if (btnPdf) {
            btnPdf.addEventListener('click', function () {
                generatePdfDownload();
            });
        }

        // 6. Action: Print
        var btnPrint = document.getElementById('btnPrintAccType');
        if (btnPrint) {
            btnPrint.addEventListener('click', function () {
                window.print();
            });
        }

        // 7. Action: Columns Toggle / Info
        var btnColumns = document.getElementById('btnColumnsAccType');
        if (btnColumns) {
            btnColumns.addEventListener('click', function () {
                showToast('All columns visible (Account Type, Account Name, Action)');
            });
        }

        // 8. Header Column Sorting
        var sortDirections = {
            type: 'asc',
            name: 'asc'
        };

        document.querySelectorAll('#accTypeTable th.sortable').forEach(function (th) {
            th.addEventListener('click', function () {
                var column = this.getAttribute('data-sort-col');
                var currentDir = sortDirections[column] || 'asc';
                var newDir = currentDir === 'asc' ? 'desc' : 'asc';
                sortDirections[column] = newDir;

                // Update sort icons
                document.querySelectorAll('#accTypeTable th.sortable i').forEach(function (icon) {
                    icon.className = 'fa fa-sort pull-right';
                });
                var icon = this.querySelector('i');
                if (icon) {
                    icon.className = newDir === 'asc' ? 'fa fa-sort-asc pull-right' : 'fa fa-sort-desc pull-right';
                }

                // Parse groups and items
                var allRows = Array.from(tbody.querySelectorAll('tr:not(#emptyRow):not(#noMatchRow)'));
                var groups = [];
                var curGroup = null;

                allRows.forEach(function (row) {
                    if (row.classList.contains('group-header-row')) {
                        if (curGroup) {
                            groups.push(curGroup);
                        }
                        curGroup = {
                            headerRow: row,
                            typeName: (row.innerText || '').trim().toLowerCase(),
                            itemRows: []
                        };
                    } else if (row.classList.contains('item-row')) {
                        if (curGroup) {
                            curGroup.itemRows.push({
                                row: row,
                                name: (row.cells[1] ? row.cells[1].innerText : '').trim().toLowerCase()
                            });
                        }
                    }
                });

                if (curGroup) {
                    groups.push(curGroup);
                }

                if (groups.length === 0) return;

                if (column === 'type') {
                    groups.sort(function (a, b) {
                        var cmp = a.typeName.localeCompare(b.typeName, undefined, { numeric: true, sensitivity: 'base' });
                        return newDir === 'asc' ? cmp : -cmp;
                    });
                } else if (column === 'name') {
                    groups.forEach(function (g) {
                        g.itemRows.sort(function (a, b) {
                            var cmp = a.name.localeCompare(b.name, undefined, { numeric: true, sensitivity: 'base' });
                            return newDir === 'asc' ? cmp : -cmp;
                        });
                    });
                }

                // Re-append sorted rows
                tbody.innerHTML = '';
                groups.forEach(function (g) {
                    tbody.appendChild(g.headerRow);
                    g.itemRows.forEach(function (item) {
                        tbody.appendChild(item.row);
                    });
                });
            });
        });
    });

    function openAddAccountTypeModal() {
        var modal = document.getElementById('accountTypeModal');
        var form = document.getElementById('accountTypeForm');
        var title = document.getElementById('accountTypeModalTitle');
        var submitBtn = document.getElementById('modalSubmitBtn');
        var idField = document.getElementById('modalAccountId');
        var nameField = document.getElementById('modal_name');
        var descField = document.getElementById('modal_description');
        var headSelect = document.getElementById('modal_accounts_type_id');

        if (form) {
            form.action = "{{ route('admin.account.accounts.newaccounts.store', absolute: false) }}";
        }
        if (title) title.innerText = 'Add Accounts Type';
        if (submitBtn) submitBtn.innerText = 'Save';
        if (idField) idField.value = '';
        if (nameField) nameField.value = '';
        if (descField) descField.value = '';
        if (headSelect) headSelect.value = '';

        if (modal) {
            modal.classList.add('is-open');
            document.body.style.overflow = 'hidden';
            if (nameField) setTimeout(function () { nameField.focus(); }, 100);
        }
    }

    function openEditAccountTypeModal(id, headId, name, note) {
        var modal = document.getElementById('accountTypeModal');
        var form = document.getElementById('accountTypeForm');
        var title = document.getElementById('accountTypeModalTitle');
        var submitBtn = document.getElementById('modalSubmitBtn');
        var idField = document.getElementById('modalAccountId');
        var nameField = document.getElementById('modal_name');
        var descField = document.getElementById('modal_description');
        var headSelect = document.getElementById('modal_accounts_type_id');

        if (form) {
            form.action = "{{ url('admin/account/accounts/newaccounts') }}/" + id;
        }
        if (title) title.innerText = 'Edit Accounts Type';
        if (submitBtn) submitBtn.innerText = 'Update';
        if (idField) idField.value = id;
        if (nameField) nameField.value = name || '';
        if (descField) descField.value = note || '';
        if (headSelect) headSelect.value = headId || '';

        if (modal) {
            modal.classList.add('is-open');
            document.body.style.overflow = 'hidden';
            if (nameField) setTimeout(function () { nameField.focus(); }, 100);
        }
    }

    function closeAddAccountTypeModal() {
        var modal = document.getElementById('accountTypeModal');
        if (modal) {
            modal.classList.remove('is-open');
            document.body.style.overflow = '';
        }
    }

    function handleBackdropClick(event) {
        if (event.target && event.target.classList.contains('coa-modal-backdrop')) {
            closeAddAccountTypeModal();
        }
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeAddAccountTypeModal();
        }
    });

    @if ($account || $errors->any())
        document.addEventListener('DOMContentLoaded', function () {
            var modal = document.getElementById('accountTypeModal');
            if (modal) {
                modal.classList.add('is-open');
                document.body.style.overflow = 'hidden';
            }
        });
    @endif
</script>
@endpush
