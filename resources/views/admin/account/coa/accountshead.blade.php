@extends('admin.layouts.app')

@section('title', $title)

@php
    $selectedHeadId = old('accounts_head_id', $account->accounts_head_id ?? '');
    $selectedTypeId = old('account_type_id', $account->new_accounts_id ?? '');
    $openingAmount = old('opening_balance_amount', $openingBalance->debit_amount ?? $openingBalance->credit_amount ?? '');
@endphp

@section('content')
    <div class="legacy-coa">
        <section class="content">
            <div class="row">
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{ $title }}</h3>
                        </div>

                        <form action="{{ $account ? route('admin.account.accounts.accountshead.update', ['account' => $account->id, 'branch' => $branchId], false) : route('admin.account.accounts.accountshead.store', ['branch' => $branchId], false) }}" method="post" accept-charset="utf-8">
                            @csrf
                            <div class="box-body">

                                @if ($account)
                                    <input type="hidden" name="id" value="{{ $account->id }}">
                                @endif

                                @if ($branches !== [])
                                    <div class="form-group">
                                        <label>Branch</label><small class="req"> *</small>
                                        <select id="brc_id" name="brc_id" class="form-control selectval brc_id" onchange="getBranchByID(this.value);">
                                            <option value="">Select</option>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->id }}" @selected((int) $branchId === (int) $branch->id)>{{ $branch->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label>Account Head</label><small class="req"> *</small>
                                    <select id="accounts_head_id" name="accounts_head_id" class="form-control">
                                        <option value="">Select</option>
                                        @foreach ($accountTypes as $accountType)
                                            <option value="{{ $accountType->id }}" @selected((string) $selectedHeadId === (string) $accountType->id)>{{ $accountType->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('accounts_head_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Account Type</label><small class="req"> *</small>
                                    <select id="account_type_id" name="account_type_id" class="form-control selectval">
                                        <option value="">Select</option>
                                        @foreach ($newAccounts as $newAccount)
                                            <option value="{{ $newAccount->id }}" @selected((string) $selectedTypeId === (string) $newAccount->id)>{{ $newAccount->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('account_type_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div id="ooa" class="hidden">
                                    <div class="form-group">
                                        <label>Account Name</label> <small class="req"> *</small>
                                        <input autofocus id="name" name="name" type="text" class="form-control" value="{{ old('name', $account->name ?? '') }}">
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div id="ob" class="hidden">
                                        <div class="form-group">
                                            <label>Staff</label>
                                            <select id="staff_id" name="staff_id" class="form-control">
                                                <option value="">Select</option>
                                                @foreach ($staffList as $staff)
                                                    <option value="{{ $staff->staff_id ?? $staff->id }}" @selected((string) old('staff_id', $account->staff_id ?? '') === (string) ($staff->staff_id ?? $staff->id))>
                                                        {{ $staff->employee_id }} - {{ trim(($staff->name ?? '').' '.($staff->surname ?? '')) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Opening Balance Date</label>
                                            <input id="date" name="date" type="date" class="form-control date" value="{{ old('date', isset($openingBalance->date) ? \Illuminate\Support\Carbon::parse($openingBalance->date)->toDateString() : now()->toDateString()) }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Opening Balance Amount</label>
                                            <input id="opening_balance_amount" name="opening_balance_amount" type="text" class="form-control" value="{{ $openingAmount }}" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $account->note ?? '') }}</textarea>
                                    </div>
                                </div>

                                <div id="ooamsg" class="hidden">
                                    <div class="alert alert-danger text-left trevd hidden">Please add "trade receivable" in the "Student Admission" menu from "Admission Process" tab.</div>
                                    <div class="alert alert-danger text-left trpayabl hidden">Please add "trade Payable" in the "Supplier" menu from "Inventory Process" tab.</div>
                                    <div class="alert alert-danger text-left invt hidden">Please add "Inventories" in the "Product/Service" menu from "Inventory Process" tab.</div>
                                    <div class="alert alert-danger text-left salaies hidden">Please add " Staff Directory" in the "Employees" menu from "Staff Recruitment" tab.</div>
                                    <div class="alert alert-danger text-left sales hidden">"Sales" accounts cannot be created here. They are automatically generated when adding new products / services.</div>
                                    <div class="alert alert-danger text-left salesreturn hidden">"Sales Return" accounts cannot be created here. They are automatically generated when adding new products / services</div>
                                    <div class="alert alert-danger text-left purchases hidden">"Purchases" accounts cannot be created here. They are automatically generated when adding new products / services</div>
                                    <div class="alert alert-danger text-left purchasesreturn hidden">"Purchases Return" accounts cannot be created here. They are automatically generated when adding new products / services</div>
                                    <div class="alert alert-danger text-left costofsales hidden">"Cost of Sales" accounts cannot be created here. They are automatically generated when adding new products / services</div>
                                </div>
                            </div>

                            <div class="box-footer footer-end">
                                @if ($account)
                                    <a href="{{ route('admin.account.accounts.accountshead', ['branch' => $branchId], false) }}" class="btn btn-default">Cancel</a>
                                @endif
                                <button type="submit" class="btn btn-primary">{{ $account ? 'Update' : 'Save' }}</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="box box-primary">
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix">Accounts Head List</h3>
                        </div>
                        <div class="box-body">
                            <div class="legacy-datatable-toolbar">
                                <input type="search" id="accHeadSearchInput" placeholder="Search..." autocomplete="off">
                                <div class="legacy-datatable-icons">
                                    <span id="btnCopyAccHead" title="Copy"><i class="fa fa-copy"></i></span>
                                    <span id="btnCsvAccHead" title="CSV"><i class="fa fa-file-csv"></i></span>
                                    <span id="btnExcelAccHead" title="Excel"><i class="fa fa-file-text"></i></span>
                                    <span id="btnPdfAccHead" title="PDF"><i class="fa fa-file-pdf"></i></span>
                                    <span id="btnPrintAccHead" title="Print"><i class="fa fa-print"></i></span>
                                    <span id="btnColumnsAccHead" title="Columns"><i class="fa fa-table-list"></i></span>
                                </div>
                            </div>
                            <table class="table table-striped table-bordered table-hover example" id="accHeadTable">
                                <thead>
                                    <tr>
                                        <th class="sortable" data-sort-col="head" style="width: 30%; cursor: pointer;" title="Sort by Account Head">
                                            Account Head <i class="fa fa-sort pull-right" style="margin-top:2px; opacity:0.8;"></i>
                                        </th>
                                        <th class="sortable" data-sort-col="type" style="width: 30%; cursor: pointer;" title="Sort by Account Type">
                                            Account Type <i class="fa fa-sort pull-right" style="margin-top:2px; opacity:0.8;"></i>
                                        </th>
                                        <th class="sortable" data-sort-col="name" style="width: 25%; cursor: pointer;" title="Sort by Account Name">
                                            Account Name <i class="fa fa-sort pull-right" style="margin-top:2px; opacity:0.8;"></i>
                                        </th>
                                        <th class="text-right noExport" style="width: 15%; text-align: right;">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="accHeadTableBody">
                                    @forelse ($hierarchy as $head)
                                        <tr class="head-group-row" data-head-name="{{ strtolower($head->name) }}" data-head-code="{{ $head->code }}">
                                            <td><strong>{{ $head->code }}. {{ $head->name }}</strong></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        @foreach ($head->newaccounts as $type)
                                            <tr class="type-group-row" data-search="{{ strtolower(($head->name ?? '') . ' ' . ($head->code ?? '') . ' ' . ($type->name ?? '') . ' ' . ($type->code ?? '')) }}">
                                                <td></td>
                                                <td><strong>{{ $type->code }}. {{ $type->name }}</strong></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            @foreach ($type->accountshead as $accountHead)
                                                <tr class="account-item-row" data-search="{{ strtolower(($head->name ?? '') . ' ' . ($head->code ?? '') . ' ' . ($type->name ?? '') . ' ' . ($type->code ?? '') . ' ' . ($accountHead->name ?? '') . ' ' . ($accountHead->code ?? '')) }}">
                                                    <td></td>
                                                    <td></td>
                                                    <td style="padding-left: 20px;">{{ $accountHead->code }}. {{ $accountHead->name }}</td>
                                                    <td class="mailbox-date text-right" style="text-align: right; white-space: nowrap; width: 12%;">
                                                        @unless ((bool) ($accountHead->is_system ?? false))
                                                            @php
                                                                $isPosted = (int) ($accountHead->is_posted ?? 0) === 1;
                                                                $isActive = ($accountHead->is_active ?? 'yes') === 'yes';
                                                            @endphp
                                                            <button onclick="changestatuspost('{{ $accountHead->id }}')" type="button" class="btn {{ $isPosted ? 'btn-success' : 'btn-danger' }} btn-xs" title="{{ $isPosted ? 'Is Posted' : 'Is Post' }}">
                                                                <i class="fa fa-plus"></i>
                                                            </button>
                                                            <a href="{{ route('admin.account.accounts.accountshead.edit', ['account' => $accountHead->id, 'branch' => $accountHead->brc_id ?: $branchId], false) }}" class="btn btn-primary btn-xs" title="Edit">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                            <button onclick="changestatus('{{ $accountHead->id }}')" type="button" class="btn {{ $isActive ? 'btn-success' : 'btn-danger' }} btn-xs" title="{{ $isActive ? 'Active' : 'In Active' }}">
                                                                <i class="fa {{ $isActive ? 'fa-check' : 'fa-remove' }}"></i>
                                                            </button>
                                                        @endunless
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    @empty
                                        <tr id="emptyHeadRow">
                                            <td colspan="4" class="text-center" style="padding: 15px; color: #777;">No accounts head records found, or the legacy tables are not available in this environment.</td>
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

    {{-- Toast Notification --}}
    <div id="accHeadToast" style="display: none; position: fixed; bottom: 25px; right: 25px; background: #24448d; color: #fff; padding: 10px 18px; border-radius: 4px; font-size: 13px; font-weight: 600; box-shadow: 0 4px 12px rgba(0,0,0,0.25); z-index: 99999;">
        Table copied to clipboard!
    </div>

    <script>
        function getBranchByID(val) {
            if (val) {
                window.location.href = '{{ url('/admin/account/accounts/accountshead') }}/' + val;
            }
        }

        function setAccountTypeVisibility(value) {
            var blocked = {
                3: 'trevd',
                23: 'invt',
                13: 'trpayabl',
                33: 'sales',
                34: 'salesreturn',
                35: 'purchases',
                36: 'purchasesreturn',
                37: 'costofsales'
            };
            document.querySelectorAll('#ooamsg .alert').forEach(function (element) {
                element.style.display = 'none';
            });

            if (blocked[value]) {
                document.getElementById('ooa').style.display = 'none';
                document.getElementById('ooamsg').style.display = 'block';
                document.querySelector('.' + blocked[value]).style.display = 'block';

                return;
            }

            document.getElementById('ooamsg').style.display = 'none';
            document.getElementById('ooa').style.display = 'block';
        }

        function setOpeningBalanceVisibility(headId) {
            document.getElementById('ob').style.display = ['1', '2', '3'].includes(String(headId)) ? 'block' : 'none';
        }

        function loadAccountTypes(headId, selectedTypeId) {
            var target = document.getElementById('account_type_id');
            target.innerHTML = '<option value="">Select</option>';

            if (!headId) {
                setAccountTypeVisibility('');
                setOpeningBalanceVisibility('');

                return;
            }

            fetch('{{ route('admin.account.accounts.newaccounts.by-head', absolute: false) }}?accounts_head_id=' + encodeURIComponent(headId), {
                headers: {'X-Requested-With': 'XMLHttpRequest'}
            })
                .then(function (response) { return response.json(); })
                .then(function (items) {
                    items.forEach(function (item) {
                        var option = document.createElement('option');
                        option.value = item.id;
                        option.textContent = item.name;

                        if (String(selectedTypeId || '') === String(item.id)) {
                            option.selected = true;
                        }

                        target.appendChild(option);
                    });

                    setAccountTypeVisibility(target.value);
                    setOpeningBalanceVisibility(headId);
                });
        }

        function postStatus(url, id) {
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({id: id})
            })
                .then(function (response) { return response.json(); })
                .then(function (data) {
                    if (data.status === 'success') {
                        window.location.reload();
                    } else {
                        alert((data.error || ['Unable to update record.']).join(' '));
                    }
                });
        }

        function changestatus(id) {
            postStatus('{{ route('admin.account.accounts.change-status', absolute: false) }}', id);
        }

        function changestatuspost(id) {
            postStatus('{{ route('admin.account.accounts.change-status-post', absolute: false) }}', id);
        }

        document.addEventListener('DOMContentLoaded', function () {
            var headSelect = document.getElementById('accounts_head_id');
            var typeSelect = document.getElementById('account_type_id');

            headSelect.addEventListener('change', function () {
                loadAccountTypes(this.value, '');
            });
            typeSelect.addEventListener('change', function () {
                setAccountTypeVisibility(this.value);
            });
            loadAccountTypes(headSelect.value, '{{ $selectedTypeId }}');

            // Toolbar functionality
            var searchInput = document.getElementById('accHeadSearchInput');
            var table = document.getElementById('accHeadTable');
            var tbody = document.getElementById('accHeadTableBody');
            var toast = document.getElementById('accHeadToast');

            function showToast(msg) {
                if (!toast) return;
                toast.innerText = msg;
                toast.style.display = 'block';
                setTimeout(function () { toast.style.display = 'none'; }, 2200);
            }

            // 1. Live Search
            if (searchInput && tbody) {
                searchInput.addEventListener('input', function () {
                    var filter = this.value.toLowerCase().trim();
                    var rows = Array.from(tbody.querySelectorAll('tr:not(#emptyHeadRow):not(#noMatchHeadRow)'));

                    if (!filter) {
                        rows.forEach(function (r) { r.style.display = ''; });
                        var noMatch = document.getElementById('noMatchHeadRow');
                        if (noMatch) noMatch.style.display = 'none';
                        return;
                    }

                    var currentHead = null;
                    var currentType = null;
                    var headHasVisible = false;
                    var typeHasVisible = false;
                    var totalVisible = 0;

                    rows.forEach(function (row) {
                        if (row.classList.contains('head-group-row')) {
                            if (currentHead && !headHasVisible) {
                                currentHead.style.display = 'none';
                            }
                            if (currentType && !typeHasVisible) {
                                currentType.style.display = 'none';
                            }
                            currentHead = row;
                            currentType = null;
                            headHasVisible = false;
                            typeHasVisible = false;
                            row.style.display = '';
                        } else if (row.classList.contains('type-group-row')) {
                            if (currentType && !typeHasVisible) {
                                currentType.style.display = 'none';
                            }
                            currentType = row;
                            typeHasVisible = false;
                            row.style.display = '';
                        } else if (row.classList.contains('account-item-row')) {
                            var search = row.getAttribute('data-search') || row.innerText.toLowerCase();
                            if (search.indexOf(filter) > -1) {
                                row.style.display = '';
                                headHasVisible = true;
                                typeHasVisible = true;
                                totalVisible++;
                                if (currentHead) currentHead.style.display = '';
                                if (currentType) currentType.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                        }
                    });

                    if (currentHead && !headHasVisible) {
                        currentHead.style.display = 'none';
                    }
                    if (currentType && !typeHasVisible) {
                        currentType.style.display = 'none';
                    }

                    var noMatchRow = document.getElementById('noMatchHeadRow');
                    if (totalVisible === 0) {
                        if (!noMatchRow) {
                            noMatchRow = document.createElement('tr');
                            noMatchRow.id = 'noMatchHeadRow';
                            noMatchRow.innerHTML = '<td colspan="4" class="text-center" style="padding:15px; color:#777;">No matching records found</td>';
                            tbody.appendChild(noMatchRow);
                        }
                        noMatchRow.style.display = '';
                    } else if (noMatchRow) {
                        noMatchRow.style.display = 'none';
                    }
                });
            }

            // 2. Copy to Clipboard
            var btnCopy = document.getElementById('btnCopyAccHead');
            if (btnCopy && table) {
                btnCopy.addEventListener('click', function () {
                    var rows = Array.from(table.querySelectorAll('tr'));
                    var text = '';
                    rows.forEach(function (r) {
                        if (r.style.display === 'none' || r.id === 'noMatchHeadRow' || r.id === 'emptyHeadRow') return;
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
                        showToast('Table copied to clipboard!');
                    }
                });
            }

            // 3. CSV Export
            function exportCSV(filename) {
                if (!table) return;
                var rows = Array.from(table.querySelectorAll('tr'));
                var csv = "\uFEFF\"Account Head\",\"Account Type\",\"Account Name\"\n";
                rows.forEach(function (r) {
                    if (r.style.display === 'none' || r.id === 'noMatchHeadRow' || r.id === 'emptyHeadRow' || r.parentElement.tagName === 'THEAD') return;
                    var cells = r.querySelectorAll('td');
                    if (cells.length >= 3) {
                        var c0 = '"' + cells[0].innerText.trim().replace(/"/g, '""') + '"';
                        var c1 = '"' + cells[1].innerText.trim().replace(/"/g, '""') + '"';
                        var c2 = '"' + cells[2].innerText.trim().replace(/"/g, '""') + '"';
                        csv += c0 + ',' + c1 + ',' + c2 + "\n";
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
                showToast('Exported successfully!');
            }

            var btnCsv = document.getElementById('btnCsvAccHead');
            if (btnCsv) {
                btnCsv.addEventListener('click', function () { exportCSV('Accounts_Head_List.csv'); });
            }

            var btnExcel = document.getElementById('btnExcelAccHead');
            if (btnExcel) {
                btnExcel.addEventListener('click', function () { exportCSV('Accounts_Head_List.xls'); });
            }

            function generateAccHeadPdfDownload() {
                if (typeof pdfMake === 'undefined') {
                    window.print();
                    return;
                }

                var tableBody = [];
                tableBody.push([
                    { text: 'Account Head', bold: true, fillColor: '#2F5DA8', color: '#ffffff', fontSize: 10 },
                    { text: 'Account Type', bold: true, fillColor: '#2F5DA8', color: '#ffffff', fontSize: 10 },
                    { text: 'Account Name', bold: true, fillColor: '#2F5DA8', color: '#ffffff', fontSize: 10 }
                ]);

                var rows = Array.from(tbody.querySelectorAll('tr:not(#emptyHeadRow):not(#noMatchHeadRow)'));
                var itemIndex = 0;

                rows.forEach(function (r) {
                    if (r.style.display === 'none') return;
                    var cells = r.querySelectorAll('td');
                    if (cells.length < 3) return;

                    var isHead = r.classList.contains('head-group-row');
                    var isType = r.classList.contains('type-group-row');
                    var c0 = cells[0].innerText.trim();
                    var c1 = cells[1].innerText.trim();
                    var c2 = cells[2].innerText.trim();

                    var bg = (isHead || isType) ? '#ffffff' : (itemIndex % 2 === 1 ? '#f4f6f8' : '#ffffff');
                    if (!isHead && !isType) itemIndex++;

                    tableBody.push([
                        { text: c0, bold: isHead, fillColor: bg, fontSize: 9.5, color: '#333333' },
                        { text: c1, bold: isType, fillColor: bg, fontSize: 9.5, color: '#333333' },
                        { text: c2, bold: false, fillColor: bg, fontSize: 9.5, color: '#333333' }
                    ]);
                });

                var docDefinition = {
                    pageOrientation: 'portrait',
                    pageSize: 'A4',
                    pageMargins: [40, 40, 40, 40],
                    content: [
                        {
                            text: 'Accounts Head List',
                            fontSize: 16,
                            bold: true,
                            color: '#111827',
                            margin: [0, 0, 0, 16]
                        },
                        {
                            table: {
                                headerRows: 1,
                                widths: ['30%', '30%', '40%'],
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

                pdfMake.createPdf(docDefinition).download('Accounts Head List.pdf');
                showToast('Accounts Head List.pdf downloaded!');
            }

            var btnPdf = document.getElementById('btnPdfAccHead');
            if (btnPdf) {
                btnPdf.addEventListener('click', function () {
                    generateAccHeadPdfDownload();
                });
            }

            var btnPrint = document.getElementById('btnPrintAccHead');
            if (btnPrint) {
                btnPrint.addEventListener('click', function () {
                    window.print();
                });
            }

            var btnColumns = document.getElementById('btnColumnsAccHead');
            if (btnColumns) {
                btnColumns.addEventListener('click', function () {
                    showToast('All columns visible');
                });
            }

            // 8. Header Column Sorting for Accounts Head List
            var sortDirections = {
                head: 'asc',
                type: 'asc',
                name: 'asc'
            };

            document.querySelectorAll('#accHeadTable th.sortable').forEach(function (th) {
                th.addEventListener('click', function () {
                    var column = this.getAttribute('data-sort-col');
                    var currentDir = sortDirections[column] || 'asc';
                    var newDir = currentDir === 'asc' ? 'desc' : 'asc';
                    sortDirections[column] = newDir;

                    // Update sort icons
                    document.querySelectorAll('#accHeadTable th.sortable i').forEach(function (icon) {
                        icon.className = 'fa fa-sort pull-right';
                    });
                    var icon = this.querySelector('i');
                    if (icon) {
                        icon.className = newDir === 'asc' ? 'fa fa-sort-asc pull-right' : 'fa fa-sort-desc pull-right';
                    }

                    // Parse hierarchy: Heads -> Types -> Items
                    var allRows = Array.from(tbody.querySelectorAll('tr:not(#emptyHeadRow):not(#noMatchHeadRow)'));
                    var heads = [];
                    var curHead = null;
                    var curType = null;

                    allRows.forEach(function (row) {
                        if (row.classList.contains('head-group-row')) {
                            if (curType && curHead) {
                                curHead.types.push(curType);
                                curType = null;
                            }
                            if (curHead) {
                                heads.push(curHead);
                            }
                            curHead = {
                                headRow: row,
                                headName: (row.innerText || '').trim().toLowerCase(),
                                types: []
                            };
                        } else if (row.classList.contains('type-group-row')) {
                            if (curType && curHead) {
                                curHead.types.push(curType);
                            }
                            curType = {
                                typeRow: row,
                                typeName: (row.innerText || '').trim().toLowerCase(),
                                items: []
                            };
                        } else if (row.classList.contains('account-item-row')) {
                            if (curType) {
                                var itemName = (row.cells[2] ? row.cells[2].innerText : '').trim().toLowerCase();
                                curType.items.push({
                                    itemRow: row,
                                    itemName: itemName
                                });
                            }
                        }
                    });

                    if (curType && curHead) {
                        curHead.types.push(curType);
                    }
                    if (curHead) {
                        heads.push(curHead);
                    }

                    if (heads.length === 0) return;

                    if (column === 'head') {
                        heads.sort(function (a, b) {
                            var cmp = a.headName.localeCompare(b.headName, undefined, { numeric: true, sensitivity: 'base' });
                            return newDir === 'asc' ? cmp : -cmp;
                        });
                    } else if (column === 'type') {
                        heads.forEach(function (h) {
                            h.types.sort(function (a, b) {
                                var cmp = a.typeName.localeCompare(b.typeName, undefined, { numeric: true, sensitivity: 'base' });
                                return newDir === 'asc' ? cmp : -cmp;
                            });
                        });
                    } else if (column === 'name') {
                        heads.forEach(function (h) {
                            h.types.forEach(function (t) {
                                t.items.sort(function (a, b) {
                                    var cmp = a.itemName.localeCompare(b.itemName, undefined, { numeric: true, sensitivity: 'base' });
                                    return newDir === 'asc' ? cmp : -cmp;
                                });
                            });
                        });
                    }

                    // Re-append in sorted order
                    tbody.innerHTML = '';
                    heads.forEach(function (h) {
                        tbody.appendChild(h.headRow);
                        h.types.forEach(function (t) {
                            tbody.appendChild(t.typeRow);
                            t.items.forEach(function (item) {
                                tbody.appendChild(item.itemRow);
                            });
                        });
                    });
                });
            });
        });
    </script>
    <script src="{{ asset('assets/dist/datatables/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/dist/datatables/js/vfs_fonts.js') }}"></script>
@endsection
