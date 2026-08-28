<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Voucher Print</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 4mm 4mm 4mm 4mm;
        }
        * {
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        body {
            margin: 0;
            padding: 0;
            background: #fff;
            color: #000;
            font-size: 10px;
        }
        @media print {
            body {
                background: #fff;
                padding: 0;
                margin: 0;
            }
            .page-break {
                page-break-after: always;
                break-after: page;
            }
            .voucher-sheet {
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
                padding: 0 !important;
                page-break-inside: avoid;
            }
        }
        .voucher-sheet {
            background: #fff;
            width: 100%;
            display: flex;
            border: none;
            padding: 4px 2px;
            margin: 0 auto;
        }
        .voucher-slip {
            flex: 1;
            padding: 4px 6px;
            border-right: 1px dashed #000;
            font-size: 9px;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }
        .voucher-slip:last-child {
            border-right: none;
        }
        .voucher-copy-type {
            text-align: center;
            font-size: 10px;
            font-weight: 700;
            margin-bottom: 3px;
            color: #000;
        }
        .school-header-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-bottom: 4px;
        }
        .school-logo-img {
            width: 28px;
            height: 28px;
            object-fit: contain;
        }
        .school-header-info {
            text-align: center;
        }
        .school-header-title {
            font-size: 13px;
            font-weight: 800;
            line-height: 1.1;
            text-transform: capitalize;
        }
        .school-header-sub {
            font-size: 8px;
            font-weight: 600;
            color: #222;
        }
        .school-header-city {
            font-size: 7.5px;
            color: #333;
        }
        .bank-info-box {
            border: 1.5px solid #000;
            border-radius: 3px;
            padding: 2px 4px;
            text-align: center;
            margin-bottom: 4px;
        }
        .bank-title {
            font-size: 11.5px;
            font-weight: 800;
            line-height: 1.1;
        }
        .bank-branch-desc {
            font-size: 7.5px;
            margin-bottom: 1px;
        }
        .bank-acc-label {
            font-size: 9.5px;
            font-weight: 700;
            line-height: 1.1;
        }
        .bank-acc-number {
            font-size: 11.5px;
            font-weight: 800;
            letter-spacing: 0.5px;
        }
        .student-meta-box {
            margin-bottom: 3px;
            font-size: 8.5px;
            line-height: 1.35;
        }
        .meta-flex-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .session-pill-wrap {
            text-align: center;
            margin: 2px 0 3px 0;
        }
        .session-pill {
            display: inline-block;
            border: 1.5px solid #000;
            border-radius: 12px;
            padding: 0.5px 14px;
            font-size: 9px;
            font-weight: 800;
        }
        .fee-particulars-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 3px;
            font-size: 8px;
        }
        .fee-particulars-table th, 
        .fee-particulars-table td {
            border: 1px solid #000;
            padding: 2px 3px;
        }
        .fee-particulars-table th {
            background-color: #f8fafc;
            font-weight: 700;
            text-align: left;
        }
        .fee-particulars-table th.sr-col,
        .fee-particulars-table td.sr-col {
            text-align: center;
            width: 22px;
        }
        .fee-particulars-table th.amt-col,
        .fee-particulars-table td.amt-col {
            text-align: right;
            width: 65px;
            white-space: nowrap;
        }
        .fee-particulars-table tr.total-summary-row td {
            font-weight: 700;
            background-color: #fff;
        }
        .due-date-container {
            text-align: center;
            font-size: 9.5px;
            font-weight: 800;
            padding: 1px 0;
            margin-bottom: 3px;
        }
        .payment-terms-section {
            font-size: 7px;
            line-height: 1.25;
            color: #111;
            margin-bottom: 4px;
        }
        .terms-heading {
            font-weight: 700;
            margin-bottom: 1px;
        }
        .terms-bullet-list {
            margin: 0;
            padding-left: 12px;
        }
        .depositor-details-box {
            font-size: 7.5px;
            margin-bottom: 6px;
            line-height: 1.55;
        }
        .depositor-field-line {
            white-space: nowrap;
        }
        .accountant-signature-section {
            text-align: right;
            padding-top: 2px;
            font-size: 7.5px;
        }
        .signature-dots {
            letter-spacing: 0.5px;
            color: #000;
        }
        .signature-title {
            font-weight: 600;
            font-size: 7.5px;
            margin-top: 1px;
        }
    </style>
</head>
<body>

    @foreach ($vouchers as $index => $v)
        <div class="voucher-sheet {{ ($index + 1) % 1 === 0 && count($vouchers) > 1 ? 'page-break' : '' }}">
            @php
                $copies = ['School Copy', 'Parents Copy', 'Bank Copy'];
                $formattedIssueDate = !empty($issue_date) ? date('d M, Y', strtotime($issue_date)) : date('d M, Y');
                $formattedDueDate = !empty($due_date) ? date('d M, Y', strtotime($due_date)) : date('d M, Y');
                $stdFullName = trim(($v['student']->firstname ?? '') . ' ' . ($v['student']->lastname ?? ''));
                $stdClass = ($v['student']->class ?? '') . (!empty($v['student']->section) ? ' - ' . $v['student']->section : '');
                $schoolName = $settings->name ?? 'Tnt Sol';
                $branchName = $v['student']->branch_name ?? 'Main Campus';
            @endphp

            @foreach ($copies as $copyName)
                <div class="voucher-slip">
                    {{-- Top Copy Title --}}
                    <div class="voucher-copy-type">{{ $copyName }}</div>

                    {{-- School Header with Logo --}}
                    <div class="school-header-wrap">
                        <img src="{{ asset('assets/images/s_logo.png') }}" class="school-logo-img" alt="Logo" onerror="this.src='{{ asset('assets/themes/default/images/logo.png') }}'; this.onerror=null;">
                        <div class="school-header-info">
                            <div class="school-header-title">{{ $schoolName }}</div>
                            <div class="school-header-sub">{{ $branchName }}</div>
                            <div class="school-header-city">Gujranwala</div>
                        </div>
                    </div>

                    {{-- Bank Info Box --}}
                    <div class="bank-info-box">
                        <div class="bank-title">{{ $bank_name ?: 'AL Habib' }}</div>
                        <div class="bank-branch-desc">{{ $bank_desc ?: '(any branch within Lahore)' }}</div>
                        <div class="bank-acc-label">Current A/C #</div>
                        <div class="bank-acc-number">{{ $account_no ?: '34543145534' }}</div>
                    </div>

                    {{-- Student Information --}}
                    <div class="student-meta-box">
                        <div class="meta-flex-row">
                            <span><strong>Bill No:</strong> {{ $v['student']->admission_no }}</span>
                            <span><strong>Issue Date:</strong> {{ $formattedIssueDate }}</span>
                        </div>
                        <div>
                            <strong>Name:</strong> {{ strtoupper($stdFullName) }}
                        </div>
                        <div class="meta-flex-row">
                            <span><strong>Class:</strong> {{ $stdClass }}</span>
                            <span><strong>Admission No:</strong> {{ $v['student']->admission_no }}</span>
                        </div>
                    </div>

                    {{-- Session Pill --}}
                    <div class="session-pill-wrap">
                        <span class="session-pill">Session: {{ $session_name }}</span>
                    </div>

                    {{-- Particulars Table --}}
                    <table class="fee-particulars-table">
                        <thead>
                            <tr>
                                <th class="sr-col">Sr#</th>
                                <th>Particulars</th>
                                <th class="amt-col">Amount(Rs.)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($v['particulars'] as $pIndex => $p)
                                <tr>
                                    <td class="sr-col">{{ $pIndex + 1 }}</td>
                                    <td>{{ $p['name'] }}</td>
                                    <td class="amt-col">{{ number_format($p['amount'], 0, '.', ',') }}</td>
                                </tr>
                            @endforeach
                            <tr class="total-summary-row">
                                <td colspan="2" style="text-align: right;">Total Amount:</td>
                                <td class="amt-col">{{ number_format($v['total_amount'], 0, '.', ',') }}</td>
                            </tr>
                            <tr class="total-summary-row">
                                <td colspan="2" style="text-align: right;">Payable within Due Date:</td>
                                <td class="amt-col">{{ number_format($v['total_amount'], 0, '.', ',') }}</td>
                            </tr>
                        </tbody>
                    </table>

                    {{-- Due Date Prominent Bar --}}
                    <div class="due-date-container">
                        Due Date: {{ $formattedDueDate }}
                    </div>

                    {{-- Payment Terms --}}
                    <div class="payment-terms-section">
                        <div class="terms-heading">Payment Terms:</div>
                        <ul class="terms-bullet-list">
                            <li>Rs. 50/- will be charged in case of Re-Issuance of Challan.</li>
                            <li>Parents must keep their copy for record.</li>
                            <li>Rs 15/day will be charged after due date.</li>
                        </ul>
                    </div>

                    {{-- Depositor Details --}}
                    <div class="depositor-details-box">
                        <div class="depositor-field-line">Depositor Name: _______________________</div>
                        <div class="depositor-field-line">CNIC NO: ______________________________</div>
                        <div class="depositor-field-line">Contact No: ___________________________</div>
                    </div>

                    {{-- Cashier / Accountant Signature --}}
                    <div class="accountant-signature-section">
                        <div class="signature-dots">...................................</div>
                        <div class="signature-title">Cashier's / Accountant's</div>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach

    <script>
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.focus();
                window.print();
            }, 300);
        });
    </script>
</body>
</html>
