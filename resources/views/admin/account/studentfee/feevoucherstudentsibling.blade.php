@extends('admin.layouts.app')

@section('title', 'Fee Voucher Student & Sibling')

@section('content')
<div class="feevoucher-sibling-container">
    <div class="page-header-flex">
        <h2 class="main-box-title">Fee Voucher Student & Sibling</h2>
    </div>

    {{-- Outer Card wrapping Tabs & Content --}}
    <div class="tabs-outer-card">
        {{-- Tabs navigation --}}
        <div class="nav-tabs-cmsc">
            <a href="javascript:void(0)" onclick="switchTab('student')" id="tabBtnStudent" class="nav-tab-item {{ $active_tab === 'student' ? 'active' : '' }}">
                <i class="fa fa-newspaper-o"></i> Student Wise Fee Voucher
            </a>
            <a href="javascript:void(0)" onclick="switchTab('sibling')" id="tabBtnSibling" class="nav-tab-item {{ $active_tab === 'sibling' ? 'active' : '' }}">
                <i class="fa fa-newspaper-o"></i> Sibling Wise Fee Voucher
            </a>
        </div>

        {{-- TAB 1: Student Wise Fee Voucher --}}
        <div id="tabContentStudent" class="{{ $active_tab === 'student' ? '' : 'hidden' }}">
            <div class="feevoucher-split-grid">
                {{-- Left Side: Student Information --}}
                <div class="box-card">
                    <div class="box-card-header">
                        <h3 class="box-card-title">Student Information</h3>
                    </div>

                    <form id="studentWiseForm" action="{{ url('admin/account/studentfee/feevoucherstudentsibling/' . $brc_id . '/1') }}" method="POST">
                        @csrf
                        <div class="box-card-body">
                            {{-- Row 1: Branch & Admission No --}}
                            <div class="grid-2-col">
                                <div class="form-group-item">
                                    <label for="brc_id_std">Branch <span class="req">*</span></label>
                                    <select id="brc_id_std" name="brc_id" class="custom-select" onchange="changeBranch(this.value, 1)">
                                        @foreach ($branchlist as $brc)
                                            <option value="{{ $brc->id }}" {{ (string)$brc_id === (string)$brc->id ? 'selected' : '' }}>
                                                {{ $brc->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group-item">
                                    <label for="student_id">Admission No <span class="req">*</span></label>
                                    <select id="student_id" name="student_id" class="custom-select" onchange="calculateStudentFee()" required>
                                        <option value="">Select</option>
                                        @foreach ($studentdrop as $std)
                                            <option value="{{ $std->student_id }}" {{ (string)old('student_id', $student_detail->student_id ?? '') === (string)$std->student_id ? 'selected' : '' }}>
                                                {{ $std->admission_no }} - {{ $std->firstname }} {{ $std->lastname }} {{ $std->father_name ? '('.$std->father_name.')' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Row 2: Issue Date & Due Date --}}
                            <div class="grid-2-col">
                                <div class="form-group-item">
                                    <label for="issue_date_std">Issue Date <span class="req">*</span></label>
                                    <input type="text" id="issue_date_std" name="issue_date" class="custom-input" value="{{ old('issue_date', $issue_date) }}" required>
                                </div>

                                <div class="form-group-item">
                                    <label for="due_date_std">Due Date <span class="req">*</span></label>
                                    <input type="text" id="due_date_std" name="due_date" class="custom-input" value="{{ old('due_date', $due_date) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="box-card-footer">
                            <div>
                                <span class="total-fee-label">Total Fee:- <span id="total_student_fee_display">{{ !empty($totalfee) ? number_format($totalfee, 0, '.', ',') : '' }}</span></span>
                            </div>

                            <button type="submit" name="search" value="search" class="btn-generate">
                                <i class="fa fa-address-card"></i> Generate Fee Voucher
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Right Side: Generated Fee Voucher --}}
                @if (!empty($student_detail))
                    <div class="box-card">
                        <div class="box-card-header">
                            <h3 class="box-card-title">Generated Fee Voucher</h3>
                            <a href="javascript:void(0)" onclick="window.print()" class="btn-generate btn-sm">
                                <i class="fa fa-print"></i> Print Fee Voucher
                            </a>
                        </div>
                        <div class="box-card-body p-0 cmsc-table-wrap">
                            <table class="table-results">
                                <thead>
                                    <tr>
                                        <th>Branch</th>
                                        <th>Admission No</th>
                                        <th>Class</th>
                                        <th>Student Name</th>
                                        <th>Father Name</th>
                                        <th>Father Phone</th>
                                        <th class="text-right">Generated Fee</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $student_detail->branch_name ?? 'Main Campus' }}</td>
                                        <td class="font-medium">{{ $student_detail->admission_no }}</td>
                                        <td>{{ $student_detail->class }} {{ $student_detail->section ? '('.$student_detail->section.')' : '' }}</td>
                                        <td class="font-medium">{{ $student_detail->firstname }} {{ $student_detail->lastname }}</td>
                                        <td>{{ $student_detail->father_name }}</td>
                                        <td>{{ $student_detail->father_phone }}</td>
                                        <td class="text-right font-bold text-success">{{ number_format($totalfee, 0, '.', ',') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Hidden on Screen, Rendered on Print --}}
                    <div class="print-only-voucher-area">
                        <div class="voucher-sheet">
                            @php
                                $copies = ['School Copy', 'Parents Copy', 'Bank Copy'];
                                $formattedIssueDate = !empty($issue_date) ? date('d M, Y', strtotime($issue_date)) : date('d M, Y');
                                $formattedDueDate = !empty($due_date) ? date('d M, Y', strtotime($due_date)) : date('d M, Y');
                                $stdFullName = trim(($student_detail->firstname ?? '') . ' ' . ($student_detail->lastname ?? ''));
                                $stdClass = ($student_detail->class ?? '') . (!empty($student_detail->section) ? ' - ' . $student_detail->section : '');
                                $schoolName = 'Tnt Sol';
                                $branchName = $student_detail->branch_name ?? 'Main Campus';
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
                                            <span><strong>Bill No:</strong> {{ $student_detail->admission_no }}</span>
                                            <span><strong>Issue Date:</strong> {{ $formattedIssueDate }}</span>
                                        </div>
                                        <div><strong>Name:</strong> {{ strtoupper($stdFullName) }}</div>
                                        <div class="meta-flex-row">
                                            <span><strong>Class:</strong> {{ $stdClass }}</span>
                                            <span><strong>Admission No:</strong> {{ $student_detail->admission_no }}</span>
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
                                                <td class="amt-col">{{ number_format(max(0, $totalfee - 12000), 0, '.', ',') }}</td>
                                            </tr>
                                            <tr class="total-summary-row">
                                                <td colspan="2" style="text-align: right;">Total Amount:</td>
                                                <td class="amt-col">{{ number_format($totalfee, 0, '.', ',') }}</td>
                                            </tr>
                                            <tr class="total-summary-row">
                                                <td colspan="2" style="text-align: right;">Payable within Due Date:</td>
                                                <td class="amt-col">{{ number_format($totalfee, 0, '.', ',') }}</td>
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
                    </div>
                @endif
            </div>
        </div>

        {{-- TAB 2: Sibling Wise Fee Voucher --}}
        <div id="tabContentSibling" class="{{ $active_tab === 'sibling' ? '' : 'hidden' }}">
            <div class="feevoucher-split-grid">
                {{-- Left Side: Sibling Information --}}
                <div class="box-card">
                    <div class="box-card-header">
                        <h3 class="box-card-title">Sibling Information</h3>
                    </div>

                    <form id="siblingWiseForm" action="{{ url('admin/account/studentfee/feevoucherstudentsibling/' . $brc_id . '/2') }}" method="POST">
                        @csrf
                        <div class="box-card-body">
                            {{-- Row 1: Branch & Sibling Code --}}
                            <div class="grid-2-col">
                                <div class="form-group-item">
                                    <label for="brc_id_sib">Branch <span class="req">*</span></label>
                                    <select id="brc_id_sib" name="brc_id" class="custom-select" onchange="changeBranch(this.value, 2)">
                                        @foreach ($branchlist as $brc)
                                            <option value="{{ $brc->id }}" {{ (string)$brc_id === (string)$brc->id ? 'selected' : '' }}>
                                                {{ $brc->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group-item">
                                    <label for="sibling_id">Sibling Code <span class="req">*</span></label>
                                    <select id="sibling_id" name="sibling_id" class="custom-select" onchange="calculateSiblingFee()" required>
                                        <option value="">Select</option>
                                        @foreach ($siblingdrop as $sib)
                                            <option value="{{ $sib->sibling_id ?? $sib->id }}" {{ (string)old('sibling_id') === (string)($sib->sibling_id ?? $sib->id) ? 'selected' : '' }}>
                                                {{ $sib->sibling_code ?? $sib->code ?? $sib->admission_no }} - {{ $sib->sibling_name ?? $sib->name ?? $sib->father_name }} {{ !empty($sib->sibling_phone) ? '('.$sib->sibling_phone.')' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Row 2: Issue Date & Due Date --}}
                            <div class="grid-2-col">
                                <div class="form-group-item">
                                    <label for="issue_date_sib">Issue Date <span class="req">*</span></label>
                                    <input type="text" id="issue_date_sib" name="issue_date" class="custom-input" value="{{ old('issue_date', $issue_date) }}" required>
                                </div>

                                <div class="form-group-item">
                                    <label for="due_date_sib">Due Date <span class="req">*</span></label>
                                    <input type="text" id="due_date_sib" name="due_date" class="custom-input" value="{{ old('due_date', $due_date) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="box-card-footer">
                            <div>
                                <span class="total-fee-label">Total Fee:- <span id="total_sibling_fee_display">{{ !empty($siblingtotalfee) ? number_format($siblingtotalfee, 0, '.', ',') : '' }}</span></span>
                            </div>

                            <button type="submit" name="search" value="sibling" class="btn-generate">
                                <i class="fa fa-address-card"></i> Generate Fee Voucher
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Right Side: Generated Sibling Fee Vouchers --}}
                @if (!empty($sibling_detail) && count($sibling_detail) > 0)
                    <div class="box-card">
                        <div class="box-card-header">
                            <h3 class="box-card-title">Generated Sibling Fee Vouchers ({{ count($sibling_detail) }} Students)</h3>
                            <a href="javascript:void(0)" onclick="window.print()" class="btn-generate btn-sm">
                                <i class="fa fa-print"></i> Print All Sibling Vouchers
                            </a>
                        </div>
                        <div class="box-card-body p-0 cmsc-table-wrap">
                            <table class="table-results">
                                <thead>
                                    <tr>
                                        <th>Admission No</th>
                                        <th>Class</th>
                                        <th>Student Name</th>
                                        <th>Father Name</th>
                                        <th>Father Phone</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sibling_detail as $std)
                                        <tr>
                                            <td class="font-medium">{{ $std->admission_no }}</td>
                                            <td>{{ $std->class }} {{ $std->section ? '('.$std->section.')' : '' }}</td>
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

                    {{-- Hidden on Screen, Rendered on Print for Siblings --}}
                    <div class="print-only-voucher-area">
                        @foreach ($sibling_detail as $sibStd)
                            <div class="voucher-sheet">
                                @php
                                    $copies = ['School Copy', 'Parents Copy', 'Bank Copy'];
                                    $formattedIssueDate = !empty($issue_date) ? date('d M, Y', strtotime($issue_date)) : date('d M, Y');
                                    $formattedDueDate = !empty($due_date) ? date('d M, Y', strtotime($due_date)) : date('d M, Y');
                                    $stdFullName = trim(($sibStd->firstname ?? '') . ' ' . ($sibStd->lastname ?? ''));
                                    $stdClass = ($sibStd->class ?? '') . (!empty($sibStd->section) ? ' - ' . $sibStd->section : '');
                                    $schoolName = 'Tnt Sol';
                                    $branchName = $sibStd->branch_name ?? 'Main Campus';
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
                                                <span><strong>Bill No:</strong> {{ $sibStd->admission_no }}</span>
                                                <span><strong>Issue Date:</strong> {{ $formattedIssueDate }}</span>
                                            </div>
                                            <div><strong>Name:</strong> {{ strtoupper($stdFullName) }}</div>
                                            <div class="meta-flex-row">
                                                <span><strong>Class:</strong> {{ $stdClass }}</span>
                                                <span><strong>Admission No:</strong> {{ $sibStd->admission_no }}</span>
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
        </div>
    </div>
</div>

@push('scripts')
<script>
    function switchTab(tab) {
        var studentTabBtn = document.getElementById('tabBtnStudent');
        var siblingTabBtn = document.getElementById('tabBtnSibling');
        var studentContent = document.getElementById('tabContentStudent');
        var siblingContent = document.getElementById('tabContentSibling');

        if (tab === 'student') {
            studentTabBtn.classList.add('active');
            siblingTabBtn.classList.remove('active');
            studentContent.classList.remove('hidden');
            siblingContent.classList.add('hidden');
        } else {
            siblingTabBtn.classList.add('active');
            studentTabBtn.classList.remove('active');
            siblingContent.classList.remove('hidden');
            studentContent.classList.add('hidden');
        }
    }

    function changeBranch(brcId, tab) {
        if (brcId) {
            window.location.href = "{{ url('admin/account/studentfee/feevoucherstudentsibling') }}/" + brcId + "/" + tab;
        }
    }

    function calculateStudentFee() {
        var studentId = document.getElementById('student_id').value;
        if (!studentId) {
            document.getElementById('total_student_fee_display').innerText = '';
            return;
        }

        var url = "{{ url('admin/account/studentfee/getStudentFeeSummary') }}?student_id=" + studentId;
        fetch(url)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data && data.total_fee !== undefined) {
                    document.getElementById('total_student_fee_display').innerText = Number(data.total_fee).toLocaleString();
                }
            })
            .catch(function(err) {
                console.error(err);
            });
    }

    function calculateSiblingFee() {
        var siblingId = document.getElementById('sibling_id').value;
        var brcId = document.getElementById('brc_id_sib').value;
        if (!siblingId) {
            document.getElementById('total_sibling_fee_display').innerText = '';
            return;
        }

        var url = "{{ url('admin/account/studentfee/getSiblingFeeSummary') }}?sibling_id=" + siblingId + "&brc_id=" + brcId;
        fetch(url)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data && data.total_fee !== undefined) {
                    document.getElementById('total_sibling_fee_display').innerText = Number(data.total_fee).toLocaleString();
                }
            })
            .catch(function(err) {
                console.error(err);
            });
    }

    function directPrintFeeVoucher(url) {
        var oldFrame = document.getElementById('printFeeVoucherIframe');
        if (oldFrame) {
            oldFrame.parentNode.removeChild(oldFrame);
        }

        var iframe = document.createElement('iframe');
        iframe.id = 'printFeeVoucherIframe';
        iframe.name = 'printFeeVoucherIframe';
        iframe.style.position = 'fixed';
        iframe.style.top = '0';
        iframe.style.left = '-99999px';
        iframe.style.width = '1024px';
        iframe.style.height = '768px';
        iframe.style.border = 'none';
        iframe.style.opacity = '0.01';
        iframe.style.zIndex = '-9999';
        document.body.appendChild(iframe);

        iframe.onload = function() {
            setTimeout(function() {
                try {
                    iframe.contentWindow.focus();
                    iframe.contentWindow.print();
                } catch (e) {
                    console.warn('Iframe print error, falling back to window.open:', e);
                    window.open(url, '_blank');
                }
            }, 350);
        };

        iframe.src = url;
    }

    document.addEventListener('DOMContentLoaded', function() {
        var studentId = document.getElementById('student_id').value;
        if (studentId) {
            calculateStudentFee();
        }
        var siblingId = document.getElementById('sibling_id').value;
        if (siblingId) {
            calculateSiblingFee();
        }
    });
</script>
@endpush
@endsection
