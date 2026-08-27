@extends('admin.layouts.app')

@section('title', 'Chart of Accounts')

@section('content')
    @include('admin.account.coa._styles')

    <div class="legacy-coa">
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-legacy-tab><i class="fa fa-list"></i> List View</a></li>
                            <li><a href="#tab_2" data-legacy-tab><i class="fa fa-columns"></i> Details View</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active table-responsive no-padding" id="tab_1">
                                <div class="legacy-datatable-toolbar">
                                    <input type="search" id="coaSearchInput" placeholder="Search...">
                                    <div class="legacy-datatable-icons">
                                        <span id="btnCopy" title="Copy"><i class="fa fa-copy"></i></span>
                                        <span id="btnExcel" title="Excel"><i class="fa fa-file-csv"></i></span>
                                        <span id="btnCsv" title="CSV"><i class="fa fa-file-text"></i></span>
                                        <span id="btnPdf" title="PDF"><i class="fa fa-file-pdf"></i></span>
                                        <span id="btnPrint" title="Print"><i class="fa fa-print"></i></span>
                                        <span id="btnColumns" title="Columns"><i class="fa fa-table-list"></i></span>
                                    </div>
                                </div>
                                <table class="table table-striped table-bordered table-hover example" id="coaTable" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="sortable" data-col="0">Account Head <i class="fa fa-sort pull-right" style="margin-top:2px; opacity:0.8;"></i></th>
                                            <th class="sortable" data-col="1">Account Type <i class="fa fa-sort pull-right" style="margin-top:2px; opacity:0.8;"></i></th>
                                            <th class="sortable" data-col="2">Account Name <i class="fa fa-sort pull-right" style="margin-top:2px; opacity:0.8;"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody id="coaTableBody">
                                        @forelse ($chartRows as $row)
                                            <tr>
                                                <td>{{ $row->account_head }}</td>
                                                <td>{{ $row->account_type }}</td>
                                                <td class="text-left" style="text-align: left !important;">{{ $row->account_code }}. {{ $row->account_name }}</td>
                                            </tr>
                                        @empty
                                            <tr id="emptyRow">
                                                <td colspan="3">No chart of accounts records found, or the legacy tables are not available in this environment.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="tab-pane" id="tab_2">
                                <div class="panel-group" id="accordion1">
                                    @php
                                        $currentBranchId = $branchId ?? (app(\App\Services\BranchContext::class)->id() ?: 1);
                                        $accountTypesList = \Illuminate\Support\Facades\DB::table('accounts_type')->orderBy('id')->get();
                                    @endphp
                                    @foreach ($accountTypesList as $headIndex => $head)
                                        @php
                                            $newAccounts = \Illuminate\Support\Facades\DB::table('accountsnew')
                                                ->where(function ($q) use ($head) {
                                                    $q->where('accounts_type_id', $head->id)
                                                      ->orWhere('accounts_type_id', (string) $head->id);
                                                    if (!empty($head->code)) {
                                                        $q->orWhere('accounts_type_id', $head->code);
                                                    }
                                                })
                                                ->orderBy('id')
                                                ->get();
                                        @endphp
                                        <div class="panel panel-default" style="margin-bottom: 5px; border: 1px solid #ddd; border-radius: 4px; background: #fff;">
                                            <div class="panel-heading" style="background-color: #f5f5f5; border-bottom: 1px solid #ddd; padding: 10px 15px; cursor: pointer;">
                                                <h4 class="panel-title" style="margin: 0; font-size: 14px; font-weight: 500; color: #333;">
                                                    <a href="#collapse{{ $headIndex }}" data-panel-toggle style="color: #333; text-decoration: none; display: block;">
                                                        {{ $head->code }}. {{ $head->name }}
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapse{{ $headIndex }}" class="panel-collapse collapse {{ $headIndex === 0 ? 'in' : '' }}" style="{{ $headIndex === 0 ? 'display: block;' : 'display: none;' }}">
                                                <div class="panel-body" style="padding: 15px; background: #fff;">
                                                    <div class="panel-group" id="accordion11_{{ $headIndex }}">
                                                        @foreach ($newAccounts as $typeIndex => $type)
                                                            @php
                                                                $accountsList = \Illuminate\Support\Facades\DB::table('accountshead')
                                                                    ->where(function ($q) use ($type) {
                                                                        $q->where('new_accounts_id', $type->id)
                                                                          ->orWhere('new_accounts_id', (string) $type->id);
                                                                        if (!empty($type->code)) {
                                                                            $q->orWhere('new_accounts_id', $type->code);
                                                                        }
                                                                    })
                                                                    ->where(function ($q) use ($currentBranchId) {
                                                                        $q->whereNull('brc_id')
                                                                          ->orWhere('brc_id', 0)
                                                                          ->orWhere('brc_id', '')
                                                                          ->orWhere('brc_id', $currentBranchId);
                                                                    })
                                                                    ->orderBy('id')
                                                                    ->get();
                                                                $isFirst = ($headIndex === 0 && $typeIndex === 0);
                                                            @endphp
                                                            <div class="sub-acc-type" style="margin-bottom: 6px;">
                                                                <a href="#collapse{{ $headIndex }}{{ $typeIndex }}" data-panel-toggle style="color: #337ab7; font-size: 13px; text-decoration: none; display: inline-block; padding: 2px 0; cursor: pointer;">
                                                                    {{ $type->code }}. {{ $type->name }} &raquo;
                                                                </a>
                                                                <div id="collapse{{ $headIndex }}{{ $typeIndex }}" class="panel-collapse collapse {{ $isFirst ? 'in' : '' }}" style="{{ $isFirst ? 'display: block;' : 'display: none;' }}">
                                                                    <div class="sub-acc-items" style="padding: 5px 0 8px 18px; font-size: 13px; color: #333; line-height: 1.8;">
                                                                        @foreach ($accountsList as $account)
                                                                            {{ $account->code }}. {{ $account->name }}<br/>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. Tab Switching
            document.querySelectorAll('[data-legacy-tab]').forEach(function (tabLink) {
                tabLink.addEventListener('click', function (event) {
                    event.preventDefault();
                    document.querySelectorAll('.legacy-coa .nav-tabs li').forEach(function (tab) {
                        tab.classList.remove('active');
                    });
                    document.querySelectorAll('.legacy-coa .tab-pane').forEach(function (pane) {
                        pane.classList.remove('active');
                    });
                    tabLink.parentElement.classList.add('active');
                    var targetPane = document.querySelector(tabLink.getAttribute('href'));
                    if (targetPane) {
                        targetPane.classList.add('active');
                    }
                });
            });

            // 2. Accordion Toggles
            document.querySelectorAll('[data-panel-toggle]').forEach(function (toggle) {
                toggle.addEventListener('click', function (event) {
                    event.preventDefault();
                    var href = toggle.getAttribute('href');
                    if (href && href.startsWith('#')) {
                        var panel = document.getElementById(href.substring(1));
                        if (panel) {
                            var isHidden = window.getComputedStyle(panel).display === 'none' || !panel.classList.contains('in');
                            if (isHidden) {
                                panel.classList.add('in');
                                panel.style.display = 'block';
                            } else {
                                panel.classList.remove('in');
                                panel.style.display = 'none';
                            }
                        }
                    }
                });
            });

            // 3. Search Filter
            var searchInput = document.getElementById('coaSearchInput');
            var tableBody = document.getElementById('coaTableBody');
            if (searchInput && tableBody) {
                var rows = Array.from(tableBody.querySelectorAll('tr:not(#emptyRow)'));
                searchInput.addEventListener('input', function () {
                    var term = this.value.trim().toLowerCase();
                    var visibleCount = 0;
                    rows.forEach(function (row) {
                        var text = row.textContent.toLowerCase();
                        if (text.includes(term)) {
                            row.style.display = '';
                            visibleCount++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    var noMatch = document.getElementById('noMatchRow');
                    if (visibleCount === 0 && term !== '') {
                        if (!noMatch) {
                            noMatch = document.createElement('tr');
                            noMatch.id = 'noMatchRow';
                            noMatch.innerHTML = '<td colspan="3" class="text-center" style="padding:15px; color:#777;">No matching records found</td>';
                            tableBody.appendChild(noMatch);
                        }
                        noMatch.style.display = '';
                    } else if (noMatch) {
                        noMatch.style.display = 'none';
                    }
                });
            }

            // 4. Clickable Column Sorting
            var sortDirs = [1, 1, 1];
            document.querySelectorAll('.legacy-coa th.sortable').forEach(function (th) {
                th.addEventListener('click', function () {
                    var colIndex = parseInt(this.getAttribute('data-col'), 10);
                    var nextDir = sortDirs[colIndex] === 1 ? -1 : 1;
                    sortDirs[colIndex] = nextDir;

                    // Update icons
                    document.querySelectorAll('.legacy-coa th.sortable i').forEach(function (icon) {
                        icon.className = 'fa fa-sort pull-right';
                    });
                    var currentIcon = this.querySelector('i');
                    if (currentIcon) {
                        currentIcon.className = nextDir === 1 ? 'fa fa-sort-asc pull-right' : 'fa fa-sort-desc pull-right';
                    }

                    // Sort visible rows
                    var activeRows = rows.filter(function (r) { return r.style.display !== 'none'; });
                    activeRows.sort(function (a, b) {
                        var aText = a.children[colIndex] ? a.children[colIndex].textContent.trim() : '';
                        var bText = b.children[colIndex] ? b.children[colIndex].textContent.trim() : '';
                        return aText.localeCompare(bText, undefined, { numeric: true, sensitivity: 'base' }) * nextDir;
                    });

                    activeRows.forEach(function (row) {
                        tableBody.appendChild(row);
                    });
                });
            });

            // 5. Action: Copy to Clipboard
            var btnCopy = document.getElementById('btnCopy');
            if (btnCopy) {
                btnCopy.addEventListener('click', function () {
                    var visibleRows = rows.filter(function (r) { return r.style.display !== 'none'; });
                    var text = "Account Head\tAccount Type\tAccount Name\n";
                    visibleRows.forEach(function (row) {
                        var cells = Array.from(row.querySelectorAll('td')).map(function (td) { return td.textContent.trim(); });
                        text += cells.join("\t") + "\n";
                    });

                    if (navigator.clipboard) {
                        navigator.clipboard.writeText(text).then(function () {
                            alert('Table copied to clipboard!');
                        });
                    }
                });
            }

            // 6. Action: CSV / Excel Export
            function exportCSV(filename) {
                var visibleRows = rows.filter(function (r) { return r.style.display !== 'none'; });
                var csv = "\uFEFF\"Account Head\",\"Account Type\",\"Account Name\"\n";
                visibleRows.forEach(function (row) {
                    var cells = Array.from(row.querySelectorAll('td')).map(function (td) {
                        return '"' + td.textContent.trim().replace(/"/g, '""') + '"';
                    });
                    csv += cells.join(",") + "\n";
                });
                var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
                var link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = filename;
                link.style.display = 'none';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }

            var btnExcel = document.getElementById('btnExcel');
            if (btnExcel) {
                btnExcel.addEventListener('click', function () {
                    exportCSV('chart_of_accounts.csv');
                });
            }

            var btnCsv = document.getElementById('btnCsv');
            if (btnCsv) {
                btnCsv.addEventListener('click', function () {
                    exportCSV('chart_of_accounts.csv');
                });
            }

            // 7. Action: Print & PDF
            var btnPrint = document.getElementById('btnPrint');
            if (btnPrint) {
                btnPrint.addEventListener('click', function () {
                    window.print();
                });
            }

            function generateCoaPdfDownload() {
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

                var visibleRows = rows.filter(function (r) { return r.style.display !== 'none'; });
                var itemIndex = 0;

                visibleRows.forEach(function (r) {
                    var cells = r.querySelectorAll('td');
                    if (cells.length < 3) return;

                    var c0 = cells[0].innerText.trim();
                    var c1 = cells[1].innerText.trim();
                    var c2 = cells[2].innerText.trim();

                    var bg = (itemIndex % 2 === 1 ? '#f4f6f8' : '#ffffff');
                    itemIndex++;

                    tableBody.push([
                        { text: c0, bold: false, fillColor: bg, fontSize: 9.5, color: '#333333' },
                        { text: c1, bold: false, fillColor: bg, fontSize: 9.5, color: '#333333' },
                        { text: c2, bold: false, fillColor: bg, fontSize: 9.5, color: '#333333' }
                    ]);
                });

                var docDefinition = {
                    pageOrientation: 'portrait',
                    pageSize: 'A4',
                    pageMargins: [40, 40, 40, 40],
                    content: [
                        {
                            text: 'Chart of Accounts',
                            fontSize: 16,
                            bold: true,
                            color: '#111827',
                            margin: [0, 0, 0, 16]
                        },
                        {
                            table: {
                                headerRows: 1,
                                widths: ['25%', '25%', '50%'],
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

                pdfMake.createPdf(docDefinition).download('Chart of Accounts.pdf');
            }

            var btnPdf = document.getElementById('btnPdf');
            if (btnPdf) {
                btnPdf.addEventListener('click', function () {
                    generateCoaPdfDownload();
                });
            }

            var btnColumns = document.getElementById('btnColumns');
            if (btnColumns) {
                btnColumns.addEventListener('click', function () {
                    alert('Displaying all columns');
                });
            }
        });
    </script>
    <script src="{{ asset('assets/dist/datatables/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/dist/datatables/js/vfs_fonts.js') }}"></script>
@endsection

