<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Structure Report - {{ $sessionLabel ?? ($current_session_name ?? '') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/themes/default/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dist/css/font-awesome.min.css') }}">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
            color: #333333;
            background-color: #f4f6f9;
            padding: 20px;
        }

        .report-page {
            max-width: 900px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #dde4eb;
            border-radius: 6px;
            padding: 30px 40px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .no-print-toolbar {
            max-width: 900px;
            margin: 0 auto 15px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 4px;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-print {
            background-color: #1e3a8a;
            color: #ffffff;
        }

        .btn-print:hover {
            background-color: #172554;
        }

        .btn-close {
            background-color: #e2e8f0;
            color: #334155;
        }

        .btn-close:hover {
            background-color: #cbd5e1;
        }

        .report-header {
            text-align: center;
            border-bottom: 2px solid #1e3a8a;
            padding-bottom: 16px;
            margin-bottom: 20px;
        }

        .report-header h1 {
            font-size: 24px;
            color: #1e3a8a;
            margin-bottom: 4px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .report-header h2 {
            font-size: 16px;
            color: #475569;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .report-meta {
            display: flex;
            justify-content: space-between;
            font-size: 12.5px;
            color: #64748b;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px dashed #e2e8f0;
        }

        .fee-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 12.5px;
        }

        .fee-table th {
            background-color: #1e3a8a;
            color: #ffffff;
            font-weight: 600;
            padding: 9px 12px;
            border: 1px solid #1e3a8a;
            text-align: left;
        }

        .fee-table th.text-right,
        .fee-table td.text-right {
            text-align: right;
        }

        .fee-table td {
            padding: 8px 12px;
            border: 1px solid #cbd5e1;
            color: #334155;
            vertical-align: middle;
        }

        .class-group-header td {
            background-color: #f1f5f9;
            font-weight: 700;
            color: #1e293b;
            border-top: 2px solid #94a3b8;
            font-size: 13px;
        }

        .class-total-row td {
            background-color: #f8fafc;
            font-weight: 700;
            color: #0f172a;
            border-top: 1px solid #64748b;
            border-bottom: 2px solid #64748b;
        }

        .report-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            font-size: 11.5px;
            color: #64748b;
        }

        .signatures-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 30px;
            margin-top: 50px;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #94a3b8;
            padding-top: 6px;
            font-size: 12px;
            font-weight: 600;
            color: #334155;
        }

        @media print {
            body {
                background-color: #ffffff;
                padding: 0;
            }

            .no-print, .no-print-toolbar {
                display: none !important;
            }

            .report-page {
                border: none;
                box-shadow: none;
                padding: 0;
                max-width: 100%;
            }

            .fee-table th {
                background-color: #1e3a8a !important;
                color: #ffffff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .class-group-header td,
            .class-total-row td {
                background-color: #f1f5f9 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="no-print-toolbar no-print">
        <button type="button" class="btn-action btn-print" onclick="window.print()">
            <i class="fa fa-print"></i> Print / Save PDF
        </button>
        <button type="button" class="btn-action btn-close" onclick="window.close()">
            <i class="fa fa-times"></i> Close
        </button>
    </div>

    <div class="report-page">
        <div class="report-header">
            <h1>Fee Structure Report</h1>
            <h2>{{ $branch->name ?? 'Main Campus' }}</h2>
            <div class="report-meta">
                <span><strong>Session:</strong> {{ $sessionLabel ?? ($current_session_name ?? 'Current Session') }}</span>
                <span><strong>Generated On:</strong> {{ date('d M, Y h:i A') }}</span>
            </div>
        </div>

        @php
            $grandTotal = 0;
            $totalCount = 0;
        @endphp

        <table class="fee-table">
            <thead>
                <tr>
                    <th style="width: 20%;">Branch</th>
                    <th style="width: 20%;">Class</th>
                    <th style="width: 40%;">Fee Head / Description</th>
                    <th class="text-right" style="width: 20%;">Amount ({{ $currency_symbol ?? 'Rs.' }})</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($feeGroups as $group)
                    <tr class="class-group-header">
                        <td>{{ $group->branch_name ?? ($branch->name ?? 'Main Campus') }}</td>
                        <td>{{ $group->class_name ?? 'Class' }}</td>
                        <td colspan="2"></td>
                    </tr>

                    @php
                        $groupTotal = 0;
                    @endphp

                    @if (!empty($group->feetypes))
                        @foreach ($group->feetypes as $feeItem)
                            @php
                                $totalCount++;
                                $groupTotal += (float) $feeItem->amount;
                                $grandTotal += (float) $feeItem->amount;
                                $monthLabel = !empty($feeItem->month_count) ? ' - ' . $feeItem->month_count . ' Month' : '';
                                $feeTitle = ($feeItem->type ?? 'Fee') . ' ( ' . ($feeItem->frequency ?? 'Monthly') . $monthLabel . ' )';
                            @endphp
                            <tr>
                                <td></td>
                                <td></td>
                                <td>
                                    <strong>{{ $feeTitle }}</strong>
                                    @if (!empty($feeItem->note))
                                        <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                                            {{ $feeItem->note }}
                                        </div>
                                    @endif
                                </td>
                                <td class="text-right">
                                    {{ number_format((float) $feeItem->amount, 2) }}
                                </td>
                            </tr>
                        @endforeach

                        <tr class="class-total-row">
                            <td colspan="2"></td>
                            <td class="text-right">Class Total:</td>
                            <td class="text-right">{{ number_format($groupTotal, 2) }}</td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 25px; color: #64748b;">
                            No fee structure records found for this branch.
                        </td>
                    </tr>
                @endforelse

                @if ($totalCount > 0)
                    <tr style="background-color: #1e3a8a; color: #ffffff; font-weight: 700; font-size: 13.5px;">
                        <td colspan="2" style="background-color: #1e3a8a; color: #ffffff; border: 1px solid #1e3a8a;">
                            Grand Total ({{ $totalCount }} items)
                        </td>
                        <td class="text-right" style="background-color: #1e3a8a; color: #ffffff; border: 1px solid #1e3a8a;">
                            Total Amount:
                        </td>
                        <td class="text-right" style="background-color: #1e3a8a; color: #ffffff; border: 1px solid #1e3a8a;">
                            {{ $currency_symbol ?? 'Rs.' }} {{ number_format($grandTotal, 2) }}
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div class="signatures-grid">
            <div class="signature-line">Prepared By</div>
            <div class="signature-line">Checked By</div>
            <div class="signature-line">Principal / Director</div>
        </div>

        <div class="report-footer">
            <span>TNT School Management System</span>
            <span>Page 1 of 1</span>
        </div>
    </div>

    @if (!empty($autoPrint))
        <script>
            window.addEventListener('DOMContentLoaded', function() {
                setTimeout(function() {
                    window.print();
                }, 500);
            });
        </script>
    @endif
</body>
</html>
