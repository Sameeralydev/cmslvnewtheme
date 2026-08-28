@extends('admin.layouts.app')

@section('title', 'Assign Fee Voucher Date Wise')

@section('content')
<div class="feevoucher-datewise-container">
    <h2 class="main-box-title">Assign Fee Voucher Date Wise</h2>

    <div class="card-wrapper">
        <div class="box-card">
            <div class="box-card-header">
                <h3 class="box-card-title">Student Information</h3>
            </div>

            <form id="datewiseForm" action="{{ url('admin/account/studentfee/assignfeevoucherdatewise/' . $brc_id) }}" method="POST">
                @csrf
                <div class="box-card-body">
                    {{-- Row 1: Branch & Admission No --}}
                    <div class="grid-2-col">
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
                            <label for="student_id">Admission No <span class="req">*</span></label>
                            <select id="student_id" name="student_id" class="custom-select" onchange="calculateTotalFee()" required>
                                <option value="">Select</option>
                                @foreach ($studentdrop as $std)
                                    <option value="{{ $std->student_id }}" {{ (string)$student_id === (string)$std->student_id ? 'selected' : '' }}>
                                        {{ $std->admission_no }} - {{ $std->firstname }} {{ $std->lastname }} {{ $std->father_name ? '('.$std->father_name.')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Row 2: From Month & To Month --}}
                    <div class="grid-2-col">
                        <div class="form-group-item">
                            <label for="from_month">From Month <span class="req">*</span></label>
                            <input type="text" id="from_month" name="from_month" class="custom-input" value="{{ $from_month ?: date('d/m/Y') }}" onchange="calculateTotalFee()" required>
                        </div>

                        <div class="form-group-item">
                            <label for="to_month">To Month <span class="req">*</span></label>
                            <input type="text" id="to_month" name="to_month" class="custom-input" value="{{ $to_month ?: date('d/m/Y') }}" onchange="calculateTotalFee()" required>
                        </div>
                    </div>

                    {{-- Row 3: Issue Date & Due Date --}}
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
                </div>

                <div class="box-card-footer">
                    <div>
                        <span class="total-fee-label">Total Fee:- <span id="total_fee_display">{{ !empty($totalfee) ? number_format($totalfee, 0, '.', ',') : '' }}</span></span>
                    </div>

                    <div class="footer-action-group">
                        <label class="checkbox-inline-label">
                            <input type="checkbox" name="notification" value="1" checked> Notification
                        </label>
                        <button type="submit" name="search" value="search" class="btn-generate">
                            <i class="fa fa-address-card"></i> Generate Fee Voucher
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Details after Voucher Generation --}}
    @if (!empty($student_detail))
        <div class="box-card mt-5">
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

@push('scripts')
<script>
    function changeBranch(brcId) {
        if (brcId) {
            window.location.href = "{{ url('admin/account/studentfee/assignfeevoucherdatewise') }}/" + brcId;
        }
    }

    function calculateTotalFee() {
        var studentId = document.getElementById('student_id').value;
        var fromMonth = document.getElementById('from_month').value;
        var toMonth = document.getElementById('to_month').value;

        if (!studentId || !fromMonth || !toMonth) {
            return;
        }

        var url = "{{ url('admin/account/studentfee/getStudentFeeSummary') }}?student_id=" + studentId + "&from_month=" + encodeURIComponent(fromMonth) + "&to_month=" + encodeURIComponent(toMonth);
        fetch(url)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data && data.total_fee !== undefined) {
                    document.getElementById('total_fee_display').innerText = Number(data.total_fee).toLocaleString();
                }
            })
            .catch(function(err) {
                console.error(err);
            });
    }

    document.addEventListener('DOMContentLoaded', function() {
        var studentId = document.getElementById('student_id').value;
        if (studentId) {
            calculateTotalFee();
        }
    });
</script>
@endpush
@endsection
