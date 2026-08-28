@extends('admin.layouts.app')

@section('title', 'Custom Fee Voucher')

@section('content')
<div class="customfeevoucher-page-container">
    {{-- Outer Card matching screenshot --}}
    <div class="box-card">
        <div class="box-card-header">
            <h3 class="box-card-title">Custom Fee Voucher</h3>
        </div>

        <form id="customfeevoucherForm" action="{{ url('admin/account/studentfee/customfeevoucher/' . $brc_id) }}" method="POST">
            @csrf
            <div class="box-card-body">
                {{-- Row 1: Branch | Class | Section | Fee Type --}}
                <div class="grid-4-col">
                    <div class="form-group-item">
                        <label for="brc_id">Branch <span class="req">*</span></label>
                        <select id="brc_id" name="brc_id" class="custom-select" onchange="changeBranch(this.value)">
                            @foreach ($branchlist as $brc)
                                <option value="{{ $brc->id }}" {{ (string)$brc_id === (string)$brc->id ? 'selected' : '' }}>
                                    {{ $brc->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group-item">
                        <label for="class_id">Class</label>
                        <select id="class_id" name="class_id" class="custom-select" onchange="loadSectionsForClass(this.value, '')">
                            <option value="">Select</option>
                            @foreach ($classlist as $cls)
                                <option value="{{ $cls->id }}" {{ (string)old('class_id', $class_id) === (string)$cls->id ? 'selected' : '' }}>
                                    {{ $cls->class }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group-item">
                        <label for="section_id">Section</label>
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

                    <div class="form-group-item">
                        <label for="feetype_id">Fee Type <span class="req">*</span></label>
                        <select id="feetype_id" name="feetype_id[]" class="custom-select">
                            <option value="">Select Choose</option>
                            @foreach ($feetypeList as $ft)
                                <option value="{{ $ft->id }}" {{ in_array($ft->id, (array)$selected_feetypes) ? 'selected' : '' }}>
                                    {{ $ft->type }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Row 2: Issue Date | Due Date | Search Type | (Optional End Date) --}}
                <div class="grid-4-col mt-4">
                    <div class="form-group-item">
                        <label for="issue_date">Issue Date <span class="req">*</span></label>
                        <input type="text" id="issue_date" name="issue_date" class="custom-input" value="{{ old('issue_date', $issue_date ?: date('d/m/Y')) }}" required>
                    </div>

                    <div class="form-group-item">
                        <label for="due_date">Due Date <span class="req">*</span></label>
                        <input type="text" id="due_date" name="due_date" class="custom-input" value="{{ old('due_date', $due_date ?: date('d/m/Y')) }}" required>
                    </div>

                    <div class="form-group-item">
                        <label for="search_type">Search Type <span class="req">*</span></label>
                        <select id="search_type" name="search_type" class="custom-select" onchange="togglePeriodDates(this.value)">
                            <option value="this_month" {{ $search_type === 'this_month' ? 'selected' : '' }}>This Month</option>
                            <option value="period" {{ $search_type === 'period' ? 'selected' : '' }}>Period</option>
                        </select>
                    </div>

                    <div class="form-group-item {{ $search_type === 'period' ? '' : 'hidden' }}" id="periodCol">
                        <label for="end_date">End Date</label>
                        <input type="text" id="end_date" name="end_date" class="custom-input" value="{{ old('end_date', $end_date) }}">
                    </div>
                </div>
            </div>

            <div class="box-card-footer footer-end">
                <button type="submit" name="search" value="search_filter" class="btn-generate">
                    <i class="fa fa-address-card"></i> Generate Fee Voucher
                </button>
            </div>
        </form>
    </div>

    {{-- Results Table after Generation --}}
    @if (!empty($resultlist) && count($resultlist) > 0)
        <div class="box-card mt-5">
            <div class="box-card-header flex-between">
                <h3 class="box-card-title">Generated Fee Vouchers ({{ count($resultlist) }} Students)</h3>
                <a href="javascript:void(0)" onclick="window.print()" class="btn-generate btn-sm">
                    <i class="fa fa-print"></i> Print All Vouchers
                </a>
            </div>
            <div class="box-card-body p-0 cmsc-table-wrap">
                <table class="table-results">
                    <thead>
                        <tr>
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
                                <td class="font-medium">{{ $std->admission_no }}</td>
                                <td>{{ $std->class }} {{ $std->section ? '(' . $std->section . ')' : '' }}</td>
                                <td class="font-medium">{{ $std->firstname }} {{ $std->lastname }}</td>
                                <td>{{ $std->father_name }}</td>
                                <td>{{ $std->father_phone }}</td>
                                <td class="text-center">
                                    <a href="javascript:void(0)" onclick="window.print()" class="btn-table-action-sm">
                                        <i class="fa fa-print"></i> Print
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Hidden on Screen, Rendered on Print for Custom Vouchers --}}
        <div class="print-only-voucher-area">
            @foreach ($resultlist as $custStd)
                <div class="voucher-sheet">
                    @php
                        $copies = ['School Copy', 'Parents Copy', 'Bank Copy'];
                        $formattedIssueDate = !empty($issue_date) ? date('d M, Y', strtotime($issue_date)) : date('d M, Y');
                        $formattedDueDate = !empty($due_date) ? date('d M, Y', strtotime($due_date)) : date('d M, Y');
                        $stdFullName = trim(($custStd->firstname ?? '') . ' ' . ($custStd->lastname ?? ''));
                        $stdClass = ($custStd->class ?? '') . (!empty($custStd->section) ? ' - ' . $custStd->section : '');
                        $schoolName = 'Tnt Sol';
                        $branchName = $custStd->branch_name ?? 'Main Campus';
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
                                    <span><strong>Bill No:</strong> {{ $custStd->admission_no }}</span>
                                    <span><strong>Issue Date:</strong> {{ $formattedIssueDate }}</span>
                                </div>
                                <div><strong>Name:</strong> {{ strtoupper($stdFullName) }}</div>
                                <div class="meta-flex-row">
                                    <span><strong>Class:</strong> {{ $stdClass }}</span>
                                    <span><strong>Admission No:</strong> {{ $custStd->admission_no }}</span>
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

@push('scripts')
<script>
    function changeBranch(brcId) {
        if (brcId) {
            window.location.href = "{{ url('admin/account/studentfee/customfeevoucher') }}/" + brcId;
        }
    }

    function loadSectionsForClass(classId, selectedSectionId) {
        var secSelect = document.getElementById('section_id');
        secSelect.innerHTML = '<option value="">Select</option>';
        if (!classId) return;

        var url = "{{ url('admin/account/studentfee/get-sections') }}/" + classId;
        fetch(url)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (Array.isArray(data) && data.length > 0) {
                    data.forEach(function(s) {
                        var opt = document.createElement('option');
                        opt.value = s.section_id || s.id;
                        opt.text = s.section || s.name;
                        if (selectedSectionId && String(opt.value) === String(selectedSectionId)) {
                            opt.selected = true;
                        }
                        secSelect.appendChild(opt);
                    });
                } else {
                    // Fallback to setting/sections/getByClass
                    fetch("{{ url('setting/sections/getByClass') }}?class_id=" + classId)
                        .then(function(res) { return res.json(); })
                        .then(function(fallbackData) {
                            if (Array.isArray(fallbackData)) {
                                fallbackData.forEach(function(s) {
                                    var opt = document.createElement('option');
                                    opt.value = s.section_id || s.id;
                                    opt.text = s.section || s.name;
                                    if (selectedSectionId && String(opt.value) === String(selectedSectionId)) {
                                        opt.selected = true;
                                    }
                                    secSelect.appendChild(opt);
                                });
                            }
                        });
                }
            })
            .catch(function(err) {
                console.error('Error loading sections:', err);
            });
    }

    function togglePeriodDates(val) {
        var periodCol = document.getElementById('periodCol');
        if (val === 'period') {
            periodCol.classList.remove('hidden');
        } else {
            periodCol.classList.add('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        var classSelect = document.getElementById('class_id');
        if (classSelect && classSelect.value) {
            loadSectionsForClass(classSelect.value, "{{ $section_id }}");
        }
    });
</script>
@endpush
@endsection
