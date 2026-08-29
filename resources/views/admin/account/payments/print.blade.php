<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Bill #{{ $voucher->invoice_no ?: $voucher->id }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 6mm 8mm;
        }
        * {
            box-sizing: border-box;
            font-family: Helvetica, Arial, sans-serif;
        }
        body {
            margin: 0;
            padding: 0;
            background: #ffffff;
            color: #000000;
        }
        .outer-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .copy-cell {
            width: 48.5%;
            vertical-align: top;
            padding: 0;
        }
        .sep-cell {
            width: 3%;
            vertical-align: top;
            border-right: 1px dashed #000000;
        }
        .voucher-border {
            border: 1px solid #000000;
            padding: 8px 10px;
        }
        .copy-title {
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 2px;
        }
        .header-tbl {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 1px solid #000000;
            margin-bottom: 4px;
            padding-bottom: 4px;
        }
        .logo-box {
            width: 28%;
            vertical-align: middle;
            text-align: left;
            padding-right: 4px;
        }
        .brand-img-logo {
            width: 65px;
            height: auto;
            display: block;
        }
        .logo-container {
            display: inline-block;
            text-align: left;
        }
        .logo-text-title {
            font-weight: bold;
            font-style: italic;
            font-size: 24px;
            color: #0284c7;
            line-height: 0.9;
            letter-spacing: -1px;
        }
        .logo-text-sub {
            font-weight: bold;
            font-size: 8.5px;
            color: #1e293b;
            letter-spacing: 0.6px;
            margin-top: 2px;
            line-height: 1;
        }
        .logo-text-tag {
            font-weight: bold;
            font-size: 7.5px;
            color: #0369a1;
            margin-top: 2px;
            line-height: 1;
        }
        .comp-info {
            width: 72%;
            vertical-align: middle;
            text-align: center;
        }
        .comp-name {
            font-size: 15px;
            font-weight: bold;
            text-transform: capitalize;
            line-height: 1.1;
        }
        .comp-sub {
            font-size: 10.5px;
            font-weight: bold;
            line-height: 1.2;
        }
        .voucher-type {
            font-size: 10.5px;
            font-weight: bold;
            line-height: 1.2;
        }
        .meta-tbl {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 1px solid #000000;
            margin-bottom: 3px;
        }
        .meta-tbl td {
            font-size: 11.5px;
            font-weight: bold;
            padding: 3px 0;
        }
        .paid-to-box {
            font-size: 11.5px;
            font-weight: bold;
            border-bottom: 1px solid #000000;
            padding: 3px 0;
            margin-bottom: 4px;
        }
        .items-tbl {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000000;
            margin-top: 4px;
            margin-bottom: 4px;
        }
        .items-tbl th {
            border: 1px solid #000000;
            padding: 4px 6px;
            font-size: 11px;
            font-weight: bold;
            text-align: left;
        }
        .items-tbl td {
            border: 1px solid #000000;
            padding: 4px 6px;
            font-size: 11px;
        }
        .words-tbl {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }
        .words-lbl {
            width: 28%;
            font-size: 11px;
            font-weight: normal;
            vertical-align: bottom;
        }
        .words-val {
            width: 72%;
            font-size: 10.5px;
            font-weight: bold;
            border-bottom: 1px solid #000000;
            vertical-align: bottom;
            padding-left: 4px;
        }
        .note-box {
            font-size: 11px;
            margin-top: 4px;
            margin-bottom: 10px;
        }
        .sig-tbl {
            width: 100%;
            border-collapse: collapse;
        }
        .sig-tbl td {
            font-size: 10.5px;
            font-weight: bold;
            vertical-align: top;
            padding: 0 2px;
        }
        .sig-line {
            border-top: 1px dashed #000000;
            display: inline-block;
            padding-top: 2px;
        }
    </style>
</head>
<body>

@php
    $docNo = $voucher->invoice_no ?: $voucher->id;
    $dateFormatted = !empty($voucher->date) ? date('d/m/Y', strtotime($voucher->date)) : '';
    $payee = $voucher->supplier_name ?: ($voucher->staff_name ? trim($voucher->staff_name) : ($voucher->accounts_name ?: 'abc'));
    $particulars = $voucher->note ?: ($voucher->accounts_name ?: 'Payment');
    $amount = (float)($voucher->debit_amount ?: 0);
    $amountFormatted = number_format($amount, 0, '.', ',');

    $branchName = $voucher->branch_name ?: ($schSetting->name ?? 'Tnt Sol');
    $branchAddress = $schSetting->address ?? 'Gujranwala';
    $branchPhone = $schSetting->phone ?? '923466049180';

    if (!function_exists('convertNumberToWords')) {
        function convertNumberToWords($number) {
            $hyphen      = ' ';
            $dictionary  = array(
                0 => 'Zero', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four', 5 => 'Five',
                6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine', 10 => 'Ten',
                11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
                16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen', 19 => 'Nineteen', 20 => 'Twenty',
                30 => 'Thirty', 40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty', 70 => 'Seventy',
                80 => 'Eighty', 90 => 'Ninety', 100 => 'Hundred', 1000 => 'Thousand',
                100000 => 'Lakh', 10000000 => 'Crore'
            );
            if (!is_numeric($number)) return '';
            $number = (int)$number;
            if ($number == 0) return 'Zero Rupees Only';
            $string = '';
            if ($number >= 10000000) {
                $crores = (int)($number / 10000000);
                $string .= convertNumberToWords($crores) . ' Crore ';
                $number %= 10000000;
            }
            if ($number >= 100000) {
                $lakhs = (int)($number / 100000);
                $string .= convertNumberToWords($lakhs) . ' Lakh ';
                $number %= 100000;
            }
            if ($number >= 1000) {
                $thousands = (int)($number / 1000);
                $string .= convertNumberToWords($thousands) . ' Thousand ';
                $number %= 1000;
            }
            if ($number >= 100) {
                $hundreds = (int)($number / 100);
                $string .= $dictionary[$hundreds] . ' ' . $dictionary[100] . ' ';
                $number %= 100;
            }
            if ($number > 0) {
                if ($number < 20) {
                    $string .= $dictionary[$number];
                } else {
                    $tens  = ((int) ($number / 10)) * 10;
                    $units = $number % 10;
                    $string .= $dictionary[$tens];
                    if ($units) {
                        $string .= $hyphen . $dictionary[$units];
                    }
                }
            }
            return trim($string);
        }
    }
    $inWords = convertNumberToWords($amount) . ' Rupees Only';
@endphp

<table class="outer-table">
    <tr>
        <!-- LEFT: Office Copy -->
        <td class="copy-cell">
            <div class="voucher-border">
                <div class="copy-title">Office Copy</div>
                <table class="header-tbl">
                    <tr>
                        <td class="logo-box">
                            @if(!empty($logoBase64))
                                <img src="{{ $logoBase64 }}" class="brand-img-logo" alt="TNT Solutions" />
                            @else
                                <div class="logo-text-title">TNT</div>
                                <div class="logo-text-sub">SOLUTIONS</div>
                                <div class="logo-text-tag">Road To Technology</div>
                            @endif
                        </td>
                        <td class="comp-info">
                            <div class="comp-name">{{ $branchName }}</div>
                            <div class="comp-sub">{{ $branchAddress }}</div>
                            <div class="comp-sub">{{ $branchPhone }}</div>
                            <div class="voucher-type">Cash Expense Bill</div>
                        </td>
                    </tr>
                </table>

                <table class="meta-tbl">
                    <tr>
                        <td style="text-align: left; width: 50%;">Date:- {{ $dateFormatted }}</td>
                        <td style="text-align: right; width: 50%;">Bill No#:- {{ $docNo }}</td>
                    </tr>
                </table>

                <div class="paid-to-box">
                    Paid to:- {{ $payee }}
                </div>

                <table class="items-tbl">
                    <thead>
                        <tr>
                            <th style="width: 12%;">Sr#</th>
                            <th style="width: 63%;">Particulars</th>
                            <th style="width: 25%; text-align: right;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>{{ $particulars }}</td>
                            <td style="text-align: right;">{{ $amountFormatted }}</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: right; font-weight: bold;">Total Amount:-</td>
                            <td style="text-align: right; font-weight: bold;">{{ $amountFormatted }}</td>
                        </tr>
                    </tbody>
                </table>

                <table class="words-tbl">
                    <tr>
                        <td class="words-lbl">Rupees In Words:</td>
                        <td class="words-val">{{ $inWords }}</td>
                    </tr>
                </table>

                <div class="note-box">
                    <strong>Note:</strong> {{ $voucher->note }}
                </div>

                <table class="sig-tbl" style="margin-top: 25px;">
                    <tr>
                        <td style="width: 33%; text-align: left;"><span class="sig-line">Paid to:</span></td>
                        <td style="width: 34%; text-align: center;"><span class="sig-line">Prepared By:</span></td>
                        <td style="width: 33%; text-align: right;"><span class="sig-line">Account Manager:</span></td>
                    </tr>
                </table>

                <table class="sig-tbl" style="margin-top: 25px;">
                    <tr>
                        <td style="width: 50%; text-align: left;"><span class="sig-line">Director Finance:</span></td>
                        <td style="width: 50%; text-align: right;"><span class="sig-line">Audit By:</span></td>
                    </tr>
                </table>
            </div>
        </td>

        <!-- Center Dashed Line -->
        <td class="sep-cell"></td>

        <!-- RIGHT: Payee Copy -->
        <td class="copy-cell" style="padding-left: 6px;">
            <div class="voucher-border">
                <div class="copy-title">Payee Copy</div>
                <table class="header-tbl">
                    <tr>
                        <td class="logo-box">
                            @if(!empty($logoBase64))
                                <img src="{{ $logoBase64 }}" class="brand-img-logo" alt="TNT Solutions" />
                            @else
                                <div class="logo-text-title">TNT</div>
                                <div class="logo-text-sub">SOLUTIONS</div>
                                <div class="logo-text-tag">Road To Technology</div>
                            @endif
                        </td>
                        <td class="comp-info">
                            <div class="comp-name">{{ $branchName }}</div>
                            <div class="comp-sub">{{ $branchAddress }}</div>
                            <div class="comp-sub">{{ $branchPhone }}</div>
                            <div class="voucher-type">Cash Expense Bill</div>
                        </td>
                    </tr>
                </table>

                <table class="meta-tbl">
                    <tr>
                        <td style="text-align: left; width: 50%;">Date:- {{ $dateFormatted }}</td>
                        <td style="text-align: right; width: 50%;">Bill No#:- {{ $docNo }}</td>
                    </tr>
                </table>

                <div class="paid-to-box">
                    Paid to:- {{ $payee }}
                </div>

                <table class="items-tbl">
                    <thead>
                        <tr>
                            <th style="width: 12%;">Sr#</th>
                            <th style="width: 63%;">Particulars</th>
                            <th style="width: 25%; text-align: right;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>{{ $particulars }}</td>
                            <td style="text-align: right;">{{ $amountFormatted }}</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: right; font-weight: bold;">Total Amount:-</td>
                            <td style="text-align: right; font-weight: bold;">{{ $amountFormatted }}</td>
                        </tr>
                    </tbody>
                </table>

                <table class="words-tbl">
                    <tr>
                        <td class="words-lbl">Rupees In Words:</td>
                        <td class="words-val">{{ $inWords }}</td>
                    </tr>
                </table>

                <div class="note-box">
                    <strong>Note:</strong> {{ $voucher->note }}
                </div>

                <table class="sig-tbl" style="margin-top: 65px;">
                    <tr>
                        <td style="width: 40%; text-align: left;"><span class="sig-line">Paid to:</span></td>
                        <td style="width: 60%; text-align: right;"><span class="sig-line">Cashier's / Accountant's</span></td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
</table>

</body>
</html>
