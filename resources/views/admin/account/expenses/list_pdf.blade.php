<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Bills List Report - {{ $branch->name ?? 'TNT SOL' }}</title>
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
            max-width: 950px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #dde4eb;
            border-radius: 6px;
            padding: 30px 40px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .no-print-toolbar {
            max-width: 950px;
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
            font-size: 22px;
            color: #1e3a8a;
            margin-bottom: 4px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .report-header h2 {
            font-size: 15px;
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

        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 12.5px;
        }

        .report-table th {
            background-color: #1e3a8a;
            color: #ffffff;
            font-weight: 600;
            padding: 9px 12px;
            border: 1px solid #1e3a8a;
            text-align: left;
        }

        .report-table td {
            padding: 8px 12px;
            border: 1px solid #cbd5e1;
            color: #334155;
            vertical-align: middle;
        }

        .report-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }

        .total-row td {
            background-color: #f1f5f9 !important;
            font-weight: 700;
            color: #0f172a;
            font-size: 13.5px;
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

            .no-print-toolbar {
                display: none !important;
            }

            .report-page {
                border: none;
                box-shadow: none;
                padding: 0;
                max-width: 100%;
            }

            .report-table th {
                background-color: #1e3a8a !important;
                color: #ffffff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .total-row td {
                background-color: #f1f5f9 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="no-print-toolbar">
        <button type="button" onclick="window.print()" class="btn-action btn-print">
            <i class="fa fa-print"></i> Print
        </button>
        <button type="button" onclick="window.close()" class="btn-action btn-close">
            <i class="fa fa-times"></i> Close
        </button>
    </div>

    <div class="report-page">
        <div class="report-header">
            <h1>{{ $branch->name ?? 'TNT SOL' }}</h1>
            <h2>EXPENSE BILLS LIST REPORT</h2>
            <div class="report-meta">
                <span><strong>Branch:</strong> {{ $branch->name ?? 'Main Branch' }}</span>
                <span><strong>Generated:</strong> {{ date('d/m/Y h:i A') }}</span>
                <span><strong>Total Records:</strong> {{ count($records) }}</span>
            </div>
        </div>

        <table class="report-table">
            <thead>
                <tr>
                    <th style="width: 6%;">Sr.#</th>
                    <th style="width: 12%;">Date</th>
                    <th style="width: 12%;">Bill No</th>
                    <th style="width: 22%;">Expense Head</th>
                    <th style="width: 18%;">Paid To</th>
                    <th style="width: 18%;">Description</th>
                    <th style="width: 12%; text-align: right;">Amount (Rs.)</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp
                @forelse ($records as $i => $r)
                    @php
                        $amt = (float)($r->total_amount ?: ($r->amount ?: 0));
                        $grandTotal += $amt;
                    @endphp
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ !empty($r->date) ? date('d/m/Y', strtotime($r->date)) : '' }}</td>
                        <td><strong>#{{ $r->bill_no ?: $r->id }}</strong></td>
                        <td>{{ $r->head_name ?: 'General Expense' }}</td>
                        <td>{{ $r->paid_to ?: '---' }}</td>
                        <td>{{ $r->description ?: '---' }}</td>
                        <td style="text-align: right; font-weight: 600;">{{ number_format($amt, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: #6b7280; padding: 15px;">No expense records found.</td>
                    </tr>
                @endforelse
                <tr class="total-row">
                    <td colspan="6" style="text-align: right; text-transform: uppercase;">Total Expense:</td>
                    <td style="text-align: right; color: #1e3a8a; font-weight: bold;">Rs. {{ number_format($grandTotal, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="signatures-grid">
            <div class="signature-line">Prepared By</div>
            <div class="signature-line">Checked By</div>
            <div class="signature-line">Approved By</div>
        </div>
    </div>

    @if ($autoPrint ?? false)
        <script>
            window.onload = function() {
                setTimeout(function() { window.print(); }, 300);
            };
        </script>
    @endif
</body>
</html>
