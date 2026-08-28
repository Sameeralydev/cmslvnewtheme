@extends('admin.layouts.app')

@section('title', 'Assign Fee Voucher')

@section('content')
<div class="feevoucher-container">
    {{-- Select Criteria Card --}}
    <div class="box-card">
        <div class="box-card-header">
            <h3 class="box-card-title">Select Criteria</h3>
            <button type="button" class="btn-print-empty" onclick="openPrintEmptyModal()">
                <i class="fa fa-print"></i> Print Empty Fee Voucher
            </button>
        </div>

        <form id="feevoucherForm" action="{{ url('admin/account/studentfee/assignfeevoucher/' . $brc_id) }}" method="POST">
            @csrf
            <div class="box-card-body">
                {{-- Radio switches --}}
                <div class="criteria-radios">
                    <label class="radio-label">
                        <input type="radio" name="optradio" id="radio_branch" value="branch_wise_fee" {{ (!isset($radiobtnclass) || $radiobtnclass != 'Yes') && (!isset($radiobtnsection) || $radiobtnsection != 'Yes') ? 'checked' : '' }} onchange="switchCriteriaView('branch')">
                        Branch Wise Fee Voucher
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="optradio" id="radio_class" value="class_wise_fee" {{ (isset($radiobtnclass) && $radiobtnclass == 'Yes') ? 'checked' : '' }} onchange="switchCriteriaView('class')">
                        Class Wise Fee Voucher
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="optradio" id="radio_section" value="section_wise_fee" {{ (isset($radiobtnsection) && $radiobtnsection == 'Yes') ? 'checked' : '' }} onchange="switchCriteriaView('section')">
                        Section Wise Fee Voucher
                    </label>
                </div>

                {{-- Row 1: Branch & Session --}}
                <div class="grid-2-col">
                    <div class="form-group-item">
                        <label for="brc_id">Branch <span class="req">*</span></label>
                        <select id="brc_id" name="brc_id" class="custom-select" onchange="handleBranchChange(this.value)">
                            @foreach ($branchlist as $brc)
                                <option value="{{ $brc->id }}" {{ (string)$brc_id === (string)$brc->id ? 'selected' : '' }}>
                                    {{ $brc->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group-item">
                        <label for="session_id">Academic Session <span class="req">*</span></label>
                        <select id="session_id" name="session_id" class="custom-select">
                            @foreach ($sessionlist as $s)
                                <option value="{{ $s->id }}" {{ (string)$current_session === (string)$s->id ? 'selected' : '' }}>
                                    {{ $s->session }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Class & Section (Conditional) --}}
                <div class="grid-2-col" id="classSectionRow" style="{{ (isset($radiobtnclass) && $radiobtnclass == 'Yes') || (isset($radiobtnsection) && $radiobtnsection == 'Yes') ? 'display: grid;' : 'display: none;' }}">
                    <div class="form-group-item" id="classCol">
                        <label for="class_id">Class <span class="req">*</span></label>
                        <select id="class_id" name="class_id" class="custom-select" onchange="loadSectionsForClass(this.value)">
                            <option value="">Select</option>
                            @foreach ($classlist as $cls)
                                <option value="{{ $cls->id }}" {{ (string)$class_id === (string)$cls->id ? 'selected' : '' }}>
                                    {{ $cls->class }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group-item" id="sectionCol" style="{{ (isset($radiobtnsection) && $radiobtnsection == 'Yes') ? 'display: block;' : 'display: none;' }}">
                        <label for="section_id">Section <span class="req">*</span></label>
                        <select id="section_id" name="section_id" class="custom-select">
                            <option value="">Select</option>
                            @if (!empty($sectionlist))
                                @foreach ($sectionlist as $sec)
                                    <option value="{{ $sec->id }}" {{ (string)old('section_id', $section_id) === (string)$sec->id ? 'selected' : '' }}>
                                        {{ $sec->section ?? ($sec->name ?? '') }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                {{-- Dates Row --}}
                <div class="grid-2-col">
                    <div class="form-group-item">
                        <label for="issue_date">Issue Date <span class="req">*</span></label>
                        <input type="text" id="issue_date" name="issue_date" class="custom-input" value="{{ $issue_date ?: date('d/m/Y') }}" required>
                    </div>

                    <div class="form-group-item">
                        <label for="due_date">Due Date <span class="req">*</span></label>
                        <input type="text" id="due_date" name="due_date" class="custom-input" value="{{ $due_date ?: date('d/m/Y') }}" required>
                    </div>
                </div>

                {{-- Fee Month Row --}}
                <div class="grid-2-col">
                    <div class="form-group-item">
                        <label for="fees_month">Fee Month <span class="req">*</span></label>
                        <input type="text" id="fees_month" name="fees_month" class="custom-input" value="{{ $fees_month ?: date('d/m/Y') }}" required>
                    </div>
                    <div></div>
                </div>
            </div>

            <div class="box-card-footer">
                <div>
                    <button type="button" class="btn-revert" onclick="openRevertModal()">
                        <i class="fa fa-undo"></i> Revert
                    </button>
                </div>

                <div class="footer-action-group">
                    <label class="checkbox-inline-label">
                        <input type="checkbox" name="frequency[]" value="Monthly" checked> Monthly Fee
                    </label>
                    <label class="checkbox-inline-label">
                        <input type="checkbox" name="frequency[]" value="Yearly"> Yearly Fee
                    </label>
                    <label class="checkbox-inline-label">
                        <input type="checkbox" name="notification" value="1" checked> Notification
                    </label>
                    <button type="submit" name="search" value="search_filter_branch" id="btnSubmitSearch" class="btn-generate">
                        <i class="fa fa-address-card"></i> Generate Fee Voucher
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Results Table if available --}}
    @if (!empty($resultlist) && count($resultlist) > 0)
        <div class="box-card">
            <div class="box-card-header">
                <h3 class="box-card-title">Generated Fee Vouchers ({{ count($resultlist) }} Students)</h3>
                <button type="button" class="btn-print-empty" onclick="printSelectedVouchers()">
                    <i class="fa fa-print"></i> Print All Vouchers
                </button>
            </div>
            <div class="box-card-body p-0 cmsc-table-wrap">
                <table class="table-results">
                    <thead>
                        <tr>
                            <th class="th-checkbox-col"><input type="checkbox" checked onclick="toggleAllStudents(this)"></th>
                            <th>Admit No</th>
                            <th>Class</th>
                            <th>Student Name</th>
                            <th>Father Name</th>
                            <th>Father Phone</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($resultlist as $std)
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" class="student-cb" checked value="{{ $std->id }}">
                                </td>
                                <td class="font-medium">{{ $std->admission_no }}</td>
                                <td>{{ $std->class }} {{ $std->section ? '(' . $std->section . ')' : '' }}</td>
                                <td class="font-medium">{{ $std->firstname }} {{ $std->lastname }}</td>
                                <td>{{ $std->father_name }}</td>
                                <td>{{ $std->father_phone }}</td>
                                <td class="text-center">
                                    <a href="javascript:void(0)" onclick="window.print()" class="btn-generate btn-sm">
                                        <i class="fa fa-print"></i> Print
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Hidden on Screen, Rendered on Print --}}
        <div class="print-only-voucher-area">
            @foreach ($resultlist as $stdVoucher)
                <div class="voucher-sheet">
                    @php
                        $copies = ['School Copy', 'Parents Copy', 'Bank Copy'];
                        $formattedIssueDate = !empty($issue_date) ? date('d M, Y', strtotime($issue_date)) : date('d M, Y');
                        $formattedDueDate = !empty($due_date) ? date('d M, Y', strtotime($due_date)) : date('d M, Y');
                        $stdFullName = trim(($stdVoucher->firstname ?? '') . ' ' . ($stdVoucher->lastname ?? ''));
                        $stdClass = ($stdVoucher->class ?? '') . (!empty($stdVoucher->section) ? ' - ' . $stdVoucher->section : '');
                        $schoolName = 'Tnt Sol';
                        $branchName = $stdVoucher->branch_name ?? 'Main Campus';
                    @endphp
                    @foreach ($copies as $copyName)
                        <div class="voucher-slip">
                            <div class="voucher-copy-type">{{ $copyName }}</div>
                            <div class="school-header-wrap">
                                <img src="{{ asset('assets/images/s_logo.png') }}" class="school-logo-img" alt="Logo" onerror="this.src='{{ asset('assets/themes/default/images/logo.png') }}'; this.onerror=null;">
                                <div class="school-header-info">
                                    <div class="school-header-title">{{ $schoolName }}</div>
                                    <div class="school-header-sub">{{ $branchName }}</div>
                                    <div class="school-header-city">Gujranwala</div>
                                </div>
                            </div>
                            <div class="bank-info-box">
                                <div class="bank-title">AL Habib</div>
                                <div class="bank-branch-desc">(any branch within Lahore)</div>
                                <div class="bank-acc-label">Current A/C #</div>
                                <div class="bank-acc-number">34543145534</div>
                            </div>
                            <div class="student-meta-box">
                                <div class="meta-flex-row">
                                    <span><strong>Bill No:</strong> {{ $stdVoucher->admission_no }}</span>
                                    <span><strong>Issue Date:</strong> {{ $formattedIssueDate }}</span>
                                </div>
                                <div><strong>Name:</strong> {{ strtoupper($stdFullName) }}</div>
                                <div class="meta-flex-row">
                                    <span><strong>Class:</strong> {{ $stdClass }}</span>
                                    <span><strong>Admission No:</strong> {{ $stdVoucher->admission_no }}</span>
                                </div>
                            </div>
                            <div class="session-pill-wrap">
                                <span class="session-pill">Session: 2026-27</span>
                            </div>
                            <table class="fee-particulars-table">
                                <thead>
                                    <tr>
                                        <th class="sr-col">Sr#</th>
                                        <th>Particulars</th>
                                        <th class="amt-col">Amount(Rs.)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="sr-col">1</td>
                                        <td>Admission Fee {{ date('M d, Y', strtotime($issue_date)) }}</td>
                                        <td class="amt-col">12,000</td>
                                    </tr>
                                    <tr>
                                        <td class="sr-col">2</td>
                                        <td>Tuition Fee {{ date('M d, Y', strtotime($issue_date)) }}</td>
                                        <td class="amt-col">33,608</td>
                                    </tr>
                                    <tr class="total-summary-row">
                                        <td colspan="2" style="text-align: right;">Total Amount:</td>
                                        <td class="amt-col">45,608</td>
                                    </tr>
                                    <tr class="total-summary-row">
                                        <td colspan="2" style="text-align: right;">Payable within Due Date:</td>
                                        <td class="amt-col">45,608</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="due-date-container">Due Date: {{ $formattedDueDate }}</div>
                            <div class="payment-terms-section">
                                <div class="terms-heading">Payment Terms:</div>
                                <ul class="terms-bullet-list">
                                    <li>Rs. 50/- will be charged in case of Re-Issuance of Challan.</li>
                                    <li>Parents must keep their copy for record.</li>
                                    <li>Rs 15/day will be charged after due date.</li>
                                </ul>
                            </div>
                            <div class="depositor-details-box">
                                <div class="depositor-field-line">Depositor Name: _______________________</div>
                                <div class="depositor-field-line">CNIC NO: ______________________________</div>
                                <div class="depositor-field-line">Contact No: ___________________________</div>
                            </div>
                            <div class="accountant-signature-section">
                                <div class="signature-dots">...................................</div>
                                <div class="signature-title">Cashier's / Accountant's</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Revert Confirmation Modal --}}
<div id="revertConfirmModal" class="custom-modal-backdrop">
    <div class="custom-modal-dialog">
        <div class="custom-modal-header">
            <h4><i class="fa fa-exclamation-triangle modal-icon-danger"></i> Confirm Revert</h4>
            <button type="button" onclick="closeRevertModal()" class="modal-close-btn">&times;</button>
        </div>
        <div class="custom-modal-body">
            <p class="modal-title-text">Are you sure you want to revert fee vouchers for this month?</p>
            <p class="modal-subtitle-text">This will remove uncollected fee vouchers created for the specified month and criteria. This action is irreversible.</p>
        </div>
        <div class="custom-modal-footer">
            <button type="button" class="btn-secondary" onclick="closeRevertModal()">Cancel</button>
            <button type="button" class="btn-revert" onclick="confirmRevertAction()">Revert Now</button>
        </div>
    </div>
</div>

{{-- Print Empty Voucher Modal --}}
<div id="printEmptyModal" class="custom-modal-backdrop">
    <div class="custom-modal-dialog">
        <div class="custom-modal-header">
            <h4><i class="fa fa-print modal-icon-success"></i> Print Empty Fee Voucher</h4>
            <button type="button" onclick="closePrintEmptyModal()" class="modal-close-btn">&times;</button>
        </div>
        <div class="custom-modal-body">
            <div class="form-group-item mb-3">
                <label>Branch</label>
                <select id="modal_empty_brc" class="custom-select">
                    @foreach ($branchlist as $brc)
                        <option value="{{ $brc->id }}" {{ (string)$brc_id === (string)$brc->id ? 'selected' : '' }}>{{ $brc->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group-item">
                <label>No. of Empty Vouchers to Print</label>
                <input type="number" id="modal_empty_count" class="custom-input" value="1" min="1" max="100">
            </div>
        </div>
        <div class="custom-modal-footer">
            <button type="button" class="btn-secondary" onclick="closePrintEmptyModal()">Cancel</button>
            <button type="button" class="btn-print-empty" onclick="generateEmptyVoucherPrint()">Print Voucher</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function switchCriteriaView(type) {
        var csRow = document.getElementById('classSectionRow');
        var secCol = document.getElementById('sectionCol');
        var btnSearch = document.getElementById('btnSubmitSearch');

        if (type === 'branch') {
            csRow.style.display = 'none';
            secCol.style.display = 'none';
            btnSearch.value = 'search_filter_branch';
        } else if (type === 'class') {
            csRow.style.display = 'grid';
            secCol.style.display = 'none';
            btnSearch.value = 'search_filter_class';
        } else if (type === 'section') {
            csRow.style.display = 'grid';
            secCol.style.display = 'block';
            btnSearch.value = 'search_filter_section';
            var classSelect = document.getElementById('class_id');
            loadSectionsForClass(classSelect ? classSelect.value : '', "{{ $section_id ?? '' }}");
        }
    }

    function handleBranchChange(brcId) {
        var optRadio = document.querySelector('input[name="optradio"]:checked');
        var chk = optRadio ? optRadio.value : 'branch_wise_fee';
        var url = "{{ url('admin/account/studentfee/assignfeevoucher') }}/" + brcId;
        window.location.href = url;
    }

    var allSectionsList = @json($sectionlist ?? []);

    function populateSectionsDropdown(items, selectedSectionId) {
        var secSelect = document.getElementById('section_id');
        if (!secSelect) return;
        secSelect.innerHTML = '<option value="">Select</option>';
        if (Array.isArray(items) && items.length > 0) {
            items.forEach(function(s) {
                var opt = document.createElement('option');
                opt.value = s.section_id || s.id;
                opt.text = s.section || s.name;
                if (selectedSectionId && String(opt.value) === String(selectedSectionId)) {
                    opt.selected = true;
                }
                secSelect.appendChild(opt);
            });
        }
    }

    function loadSectionsForClass(classId, selectedSectionId) {
        if (!classId) {
            populateSectionsDropdown(allSectionsList, selectedSectionId);
            return;
        }

        var url = "{{ url('admin/account/studentfee/get-sections') }}/" + classId;
        fetch(url)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (Array.isArray(data) && data.length > 0) {
                    populateSectionsDropdown(data, selectedSectionId);
                } else {
                    fetch("{{ url('setting/sections/getByClass') }}?class_id=" + classId)
                        .then(function(res) { return res.json(); })
                        .then(function(fallbackData) {
                            if (Array.isArray(fallbackData) && fallbackData.length > 0) {
                                populateSectionsDropdown(fallbackData, selectedSectionId);
                            } else {
                                populateSectionsDropdown(allSectionsList, selectedSectionId);
                            }
                        })
                        .catch(function() {
                            populateSectionsDropdown(allSectionsList, selectedSectionId);
                        });
                }
            })
            .catch(function() {
                populateSectionsDropdown(allSectionsList, selectedSectionId);
            });
    }

    document.addEventListener('DOMContentLoaded', function() {
        var classSelect = document.getElementById('class_id');
        loadSectionsForClass(classSelect ? classSelect.value : '', "{{ $section_id ?? '' }}");
    });

    function toggleAllStudents(master) {
        var cbs = document.querySelectorAll('.student-cb');
        cbs.forEach(function(cb) { cb.checked = master.checked; });
    }

    function printSelectedVouchers() {
        window.print();
    }

    function openRevertModal() {
        document.getElementById('revertConfirmModal').style.display = 'flex';
    }

    function closeRevertModal() {
        document.getElementById('revertConfirmModal').style.display = 'none';
    }

    function confirmRevertAction() {
        var form = document.getElementById('feevoucherForm');
        form.action = "{{ url('admin/account/studentfee/revertfeevoucher') }}";
        form.submit();
    }

    function openPrintEmptyModal() {
        document.getElementById('printEmptyModal').style.display = 'flex';
    }

    function closePrintEmptyModal() {
        document.getElementById('printEmptyModal').style.display = 'none';
    }

    function generateEmptyVoucherPrint() {
        var brc = document.getElementById('modal_empty_brc').value;
        var count = document.getElementById('modal_empty_count').value || 1;
        var url = "{{ url('admin/account/studentfee/printfeevoucher') }}?brc_id=" + brc + "&empty_count=" + count + "&is_empty=1";
        window.open(url, '_blank');
        closePrintEmptyModal();
    }
</script>
@endpush
@endsection
