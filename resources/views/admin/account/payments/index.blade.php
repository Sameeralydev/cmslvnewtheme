@extends('admin.layouts.app')

@section('title', 'Payment Vouchers List')

@section('content')
<div class="feevoucher-container">
    {{-- Main Outer Card --}}
    <div class="box-card">
        {{-- Card Header --}}
        <div class="box-card-header">
            <h3 class="box-card-title">Payment Vouchers List</h3>
            <button type="button" class="btn-cmsc-primary" onclick="openAddPaymentModal()">
                <i class="fa fa-plus"></i> Add Payment Voucher
            </button>
        </div>

        {{-- Filters Body --}}
        <div class="box-card-body">
            <form id="paymentFilterForm" action="{{ route('admin.account.payments.index') }}" method="GET">
                {{-- 4 Columns Filter Row --}}
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 12px;">
                    {{-- Branch --}}
                    <div class="form-group">
                        <label for="brc_id" style="font-weight: 500; font-size: 13px; color: #374151; margin-bottom: 4px; display: block;">Branch <span style="color: #ef4444;">*</span></label>
                        <select id="brc_id" name="brc_id" class="form-control-cmsc" style="width: 100%; height: 34px; padding: 4px 10px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 13px;">
                            @foreach ($branchlist as $brc)
                                <option value="{{ $brc->id }}" {{ (string)$brc_id === (string)$brc->id ? 'selected' : '' }}>
                                    {{ $brc->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Accounts Name --}}
                    <div class="form-group">
                        <label for="accounts_id" style="font-weight: 500; font-size: 13px; color: #374151; margin-bottom: 4px; display: block;">Accounts Name</label>
                        <select id="accounts_id" name="accounts_id" class="form-control-cmsc" style="width: 100%; height: 34px; padding: 4px 10px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 13px;">
                            <option value="">Select</option>
                            @foreach ($acclist as $acc)
                                <option value="{{ $acc->id }}" {{ (string)$accounts_id === (string)$acc->id ? 'selected' : '' }}>
                                    {{ $acc->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Supplier --}}
                    <div class="form-group">
                        <label for="supplier_id" style="font-weight: 500; font-size: 13px; color: #374151; margin-bottom: 4px; display: block;">Supplier</label>
                        <select id="supplier_id" name="supplier_id" class="form-control-cmsc" style="width: 100%; height: 34px; padding: 4px 10px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 13px;">
                            <option value="">Select</option>
                            @foreach ($supplierlist as $sup)
                                <option value="{{ $sup->id }}" {{ (string)$supplier_id === (string)$sup->id ? 'selected' : '' }}>
                                    {{ $sup->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Staff --}}
                    <div class="form-group">
                        <label for="staff_id" style="font-weight: 500; font-size: 13px; color: #374151; margin-bottom: 4px; display: block;">Staff</label>
                        <select id="staff_id" name="staff_id" class="form-control-cmsc" style="width: 100%; height: 34px; padding: 4px 10px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 13px;">
                            <option value="">Select</option>
                            @foreach ($stafflist as $stf)
                                <option value="{{ $stf->id }}" {{ (string)$staff_id === (string)$stf->id ? 'selected' : '' }}>
                                    {{ $stf->name }} {{ $stf->surname }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Row 2: Period & Search button --}}
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; align-items: flex-end;">
                    {{-- Period --}}
                    <div class="form-group">
                        <label for="period" style="font-weight: 500; font-size: 13px; color: #374151; margin-bottom: 4px; display: block;">Period <span style="color: #ef4444;">*</span></label>
                        <select id="period" name="period" class="form-control-cmsc" onchange="toggleCustomDates(this.value)" style="width: 100%; height: 34px; padding: 4px 10px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 13px;">
                            <option value="all" {{ $period === 'all' ? 'selected' : '' }}>Select</option>
                            <option value="today" {{ $period === 'today' ? 'selected' : '' }}>Today</option>
                            <option value="this_week" {{ $period === 'this_week' ? 'selected' : '' }}>This Week</option>
                            <option value="this_month" {{ $period === 'this_month' ? 'selected' : '' }}>This Month</option>
                            <option value="last_month" {{ $period === 'last_month' ? 'selected' : '' }}>Last Month</option>
                            <option value="this_year" {{ $period === 'this_year' ? 'selected' : '' }}>This Year</option>
                            <option value="period" {{ $period === 'period' ? 'selected' : '' }}>Period (Custom)</option>
                        </select>
                    </div>

                    {{-- Custom Date From --}}
                    <div id="customDateFromGroup" class="form-group" style="display: {{ $period === 'period' ? 'block' : 'none' }};">
                        <label for="start_date" style="font-weight: 500; font-size: 13px; color: #374151; margin-bottom: 4px; display: block;">From Date</label>
                        <input type="text" id="start_date" name="start_date" class="form-control-cmsc" value="{{ $startDate }}" placeholder="DD/MM/YYYY" style="width: 100%; height: 34px; padding: 4px 10px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 13px;">
                    </div>

                    {{-- Custom Date To --}}
                    <div id="customDateToGroup" class="form-group" style="display: {{ $period === 'period' ? 'block' : 'none' }};">
                        <label for="end_date" style="font-weight: 500; font-size: 13px; color: #374151; margin-bottom: 4px; display: block;">To Date</label>
                        <input type="text" id="end_date" name="end_date" class="form-control-cmsc" value="{{ $endDate }}" placeholder="DD/MM/YYYY" style="width: 100%; height: 34px; padding: 4px 10px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 13px;">
                    </div>

                    {{-- Search Button --}}
                    <div style="grid-column: {{ $period === 'period' ? '4 / 5' : '4 / 5' }}; display: flex; justify-content: flex-end; align-items: flex-end; margin-bottom: 2px;">
                        <button type="submit" class="btn-cmsc-primary" style="background-color: #1e3a8a; color: #ffffff; padding: 6px 18px; border-radius: 4px; font-size: 13px; font-weight: 500; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.1);">
                            <i class="fa fa-search"></i> Search
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="box-card" style="margin-top: 16px;">
        {{-- Table Toolbar Header (Right Corner Grouped Exact Match) --}}
        <div style="padding: 14px 18px; display: flex; justify-content: flex-end; align-items: center; flex-wrap: wrap; gap: 14px; border-bottom: 1px solid #f3f4f6;">
            {{-- 100 Dropdown --}}
            <div style="display: flex; align-items: center;">
                <select id="perPageSelect" onchange="changePerPage(this.value)" style="height: 36px; padding: 4px 10px; font-size: 14px; font-weight: 500; color: #1f2937; background: transparent; border: none; border-bottom: 1px solid #d1d5db; outline: none; cursor: pointer;">
                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                    <option value="250" {{ $perPage == 250 ? 'selected' : '' }}>250</option>
                    <option value="500" {{ $perPage == 500 ? 'selected' : '' }}>500</option>
                </select>
            </div>

            {{-- Live Search Input (Before Buttons) --}}
            <div>
                <input type="text" id="tableSearchInput" placeholder="Search..." onkeyup="filterPaymentTable(this.value)" class="dt-search-input" style="height: 36px; width: 220px; border-radius: 6px; border: 1px solid #d1d5db; padding: 4px 10px; font-size: 13.5px; outline: none;">
            </div>

            {{-- 6 Navy Export Buttons (Far Right Corner) --}}
            <div class="dt-buttons-group" style="position: relative; display: inline-flex; align-items: center; gap: 4px;">
                    <button type="button" onclick="exportTableCopy()" title="Copy to Clipboard" style="background-color: #1e3a8a; color: #ffffff; border: none; width: 32px; height: 32px; border-radius: 4px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 13px; transition: background-color 0.15s ease;" onmouseover="this.style.backgroundColor='#172554'" onmouseout="this.style.backgroundColor='#1e3a8a'">
                        <i class="fa fa-clone"></i>
                    </button>
                    <button type="button" onclick="exportTableExcel()" title="Export Excel" style="background-color: #1e3a8a; color: #ffffff; border: none; width: 32px; height: 32px; border-radius: 4px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 13px; transition: background-color 0.15s ease;" onmouseover="this.style.backgroundColor='#172554'" onmouseout="this.style.backgroundColor='#1e3a8a'">
                        <i class="fa fa-file-excel"></i>
                    </button>
                    <button type="button" onclick="exportTableCSV()" title="Export CSV" style="background-color: #1e3a8a; color: #ffffff; border: none; width: 32px; height: 32px; border-radius: 4px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 13px; transition: background-color 0.15s ease;" onmouseover="this.style.backgroundColor='#172554'" onmouseout="this.style.backgroundColor='#1e3a8a'">
                        <i class="fa fa-file-alt"></i>
                    </button>
                    <button type="button" onclick="exportTablePDF()" title="Export PDF" style="background-color: #1e3a8a; color: #ffffff; border: none; width: 32px; height: 32px; border-radius: 4px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 13px; transition: background-color 0.15s ease;" onmouseover="this.style.backgroundColor='#172554'" onmouseout="this.style.backgroundColor='#1e3a8a'">
                        <i class="fa fa-file-pdf"></i>
                    </button>
                    <button type="button" onclick="exportTablePrint()" title="Print" style="background-color: #1e3a8a; color: #ffffff; border: none; width: 32px; height: 32px; border-radius: 4px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 13px; transition: background-color 0.15s ease;" onmouseover="this.style.backgroundColor='#172554'" onmouseout="this.style.backgroundColor='#1e3a8a'">
                        <i class="fa fa-print"></i>
                    </button>
                    <div style="position: relative; display: inline-block;">
                        <button type="button" onclick="toggleColumnVisibilityMenu(event)" title="Column Visibility" style="background-color: #1e3a8a; color: #ffffff; border: none; width: 32px; height: 32px; border-radius: 4px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 13px; transition: background-color 0.15s ease;" onmouseover="this.style.backgroundColor='#172554'" onmouseout="this.style.backgroundColor='#1e3a8a'">
                            <i class="fa fa-columns"></i>
                        </button>
                        <div id="colVisibilityDropdown" style="display: none; position: absolute; right: 0; top: 100%; z-index: 1050; min-width: 170px; background: #ffffff; border: 1px solid #d1d5db; border-radius: 4px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); padding: 8px 12px; margin-top: 4px;">
                            <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; margin-bottom: 6px; cursor: pointer;">
                                <input type="checkbox" checked onchange="toggleTableCol(0, this.checked)"> Sr.#
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; margin-bottom: 6px; cursor: pointer;">
                                <input type="checkbox" checked onchange="toggleTableCol(1, this.checked)"> Date
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; margin-bottom: 6px; cursor: pointer;">
                                <input type="checkbox" checked onchange="toggleTableCol(2, this.checked)"> Document No
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; margin-bottom: 6px; cursor: pointer;">
                                <input type="checkbox" checked onchange="toggleTableCol(3, this.checked)"> Accounts Name
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; margin-bottom: 6px; cursor: pointer;">
                                <input type="checkbox" checked onchange="toggleTableCol(4, this.checked)"> Supplier
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; margin-bottom: 6px; cursor: pointer;">
                                <input type="checkbox" checked onchange="toggleTableCol(5, this.checked)"> Staff
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; margin-bottom: 6px; cursor: pointer;">
                                <input type="checkbox" checked onchange="toggleTableCol(6, this.checked)"> Description
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; margin-bottom: 0; cursor: pointer;">
                                <input type="checkbox" checked onchange="toggleTableCol(7, this.checked)"> Amount
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table Content --}}
        <div style="overflow-x: auto; overflow-y: visible; min-height: 220px;">
            <table id="paymentVouchersTable" style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13.5px; border: 1px solid #e5e7eb;">
                <thead>
                    <tr style="background-color: #1e3a8a; color: #ffffff; font-weight: 600; font-size: 13.5px;">
                        <th style="padding: 10px 14px; border: 1px solid #2a4fa8; cursor: pointer;" onclick="sortTable(0)">
                            Sr.# <span style="font-size: 11px; opacity: 0.8;">▾</span>
                        </th>
                        <th style="padding: 10px 14px; border: 1px solid #2a4fa8; cursor: pointer;" onclick="sortTable(1)">
                            Date <span style="font-size: 11px; opacity: 0.8;">▾</span>
                        </th>
                        <th style="padding: 10px 14px; border: 1px solid #2a4fa8; cursor: pointer;" onclick="sortTable(2)">
                            Document No <span style="font-size: 11px; opacity: 0.8;">▾</span>
                        </th>
                        <th style="padding: 10px 14px; border: 1px solid #2a4fa8; cursor: pointer;" onclick="sortTable(3)">
                            Accounts Name <span style="font-size: 11px; opacity: 0.8;">▾</span>
                        </th>
                        <th style="padding: 10px 14px; border: 1px solid #2a4fa8; cursor: pointer;" onclick="sortTable(4)">
                            Supplier <span style="font-size: 11px; opacity: 0.8;">▾</span>
                        </th>
                        <th style="padding: 10px 14px; border: 1px solid #2a4fa8; cursor: pointer;" onclick="sortTable(5)">
                            Staff <span style="font-size: 11px; opacity: 0.8;">▾</span>
                        </th>
                        <th style="padding: 10px 14px; border: 1px solid #2a4fa8; cursor: pointer;" onclick="sortTable(6)">
                            Description <span style="font-size: 11px; opacity: 0.8;">▾</span>
                        </th>
                        <th style="padding: 10px 14px; border: 1px solid #2a4fa8; cursor: pointer;" onclick="sortTable(7)">
                            Amount <span style="font-size: 11px; opacity: 0.8;">▾</span>
                        </th>
                        <th style="padding: 10px 14px; border: 1px solid #2a4fa8; text-align: left; width: 120px;">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody style="color: #374151;">
                    @forelse ($records as $index => $row)
                        @php
                            $srNo = ($records instanceof \Illuminate\Pagination\LengthAwarePaginator) ? ($records->currentPage() - 1) * $records->perPage() + $index + 1 : $index + 1;
                            $formattedDate = !empty($row->date) ? date('d/m/Y', strtotime($row->date)) : '';
                            $docNo = $row->document_no ?: ($row->id ? str_pad($row->id, 5, '0', STR_PAD_LEFT) : '');
                            $amt = (float)($row->amount ?: 0);
                        @endphp
                        <tr style="border-bottom: 1px solid #e5e7eb; transition: background-color 0.15s ease;" onmouseover="this.style.backgroundColor='#f9fafb'" onmouseout="this.style.backgroundColor='transparent'">
                            <td style="padding: 10px 14px; border: 1px solid #e5e7eb;">{{ $srNo }}</td>
                            <td style="padding: 10px 14px; border: 1px solid #e5e7eb;">{{ $formattedDate }}</td>
                            <td style="padding: 10px 14px; border: 1px solid #e5e7eb;">
                                <a href="{{ route('admin.account.payments.print', $row->id) }}" target="_blank" style="color: #0284c7; text-decoration: none; font-weight: 500;">
                                    {{ $docNo }}
                                </a>
                            </td>
                            <td style="padding: 10px 14px; border: 1px solid #e5e7eb;">{{ $row->accounts_name ?: '-' }}</td>
                            <td style="padding: 10px 14px; border: 1px solid #e5e7eb;">{{ $row->supplier_name ?: '' }}</td>
                            <td style="padding: 10px 14px; border: 1px solid #e5e7eb;">{{ trim($row->staff_name) ?: '' }}</td>
                            <td style="padding: 10px 14px; border: 1px solid #e5e7eb;">{{ $row->description }}</td>
                            <td style="padding: 10px 14px; border: 1px solid #e5e7eb; font-weight: 500;">{{ number_format($amt, 2) }}</td>
                            <td style="padding: 8px 14px; border: 1px solid #e5e7eb; text-align: right;">
                                <div style="position: relative; display: inline-block;">
                                    <button type="button" onclick="toggleActionMenu(event, 'actionMenu_{{ $row->id }}')" style="background-color: #1e3a8a; color: #ffffff; border: 1px solid #1e3a8a; padding: 4px 12px; font-size: 13px; font-weight: 600; border-radius: 4px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.12);" onfocus="this.style.backgroundColor='#000000'" onblur="this.style.backgroundColor='#1e3a8a'">
                                        Action <i class="fa fa-caret-down" style="font-size: 11px;"></i>
                                    </button>
                                    <div id="actionMenu_{{ $row->id }}" class="action-menu-dropdown" style="display: none; position: absolute; right: 0; top: 100%; z-index: 1050; min-width: 110px; background: #ffffff; border: 1px solid #e5e7eb; border-radius: 4px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); margin-top: 2px; text-align: left; padding: 4px 0;">
                                        <a href="{{ route('admin.account.payments.print', $row->id) }}" target="_blank" style="display: flex; align-items: center; gap: 10px; padding: 6px 14px; font-size: 13px; color: #4b5563; text-decoration: none; transition: background-color 0.15s ease;" onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor='transparent'">
                                            <i class="fa fa-download" style="color: #6b7280; width: 14px; text-align: center;"></i> <span>Bill</span>
                                        </a>
                                        <a href="javascript:void(0)" onclick="openEditPaymentModal({{ $row->id }})" style="display: flex; align-items: center; gap: 10px; padding: 6px 14px; font-size: 13px; color: #4b5563; text-decoration: none; transition: background-color 0.15s ease;" onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor='transparent'">
                                            <i class="fa fa-pencil" style="color: #6b7280; width: 14px; text-align: center;"></i> <span>Edit</span>
                                        </a>
                                        <a href="javascript:void(0)" onclick="deletePayment({{ $row->id }})" style="display: flex; align-items: center; gap: 10px; padding: 6px 14px; font-size: 13px; color: #4b5563; text-decoration: none; transition: background-color 0.15s ease;" onmouseover="this.style.backgroundColor='#fef2f2'; this.style.color='#ef4444';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#4b5563';">
                                            <i class="fa fa-trash" style="color: #6b7280; width: 14px; text-align: center;"></i> <span>Delete</span>
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="padding: 24px; text-align: center; color: #6b7280; border: 1px solid #e5e7eb;">No payment voucher records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Table Footer / Pagination Info --}}
        <div style="padding: 14px 18px; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #f3f4f6; font-size: 13px; color: #6b7280;">
            <div>
                @if ($records instanceof \Illuminate\Pagination\LengthAwarePaginator && $records->total() > 0)
                    Records: {{ $records->firstItem() }} to {{ $records->lastItem() }} of {{ $records->total() }}
                @else
                    Records: 0 to 0 of 0
                @endif
            </div>
            <div>
                @if ($records instanceof \Illuminate\Pagination\LengthAwarePaginator && $records->hasPages())
                    {{ $records->links() }}
                @else
                    <div style="display: inline-flex; align-items: center; gap: 6px; font-size: 14px;">
                        <span style="color: #9ca3af; cursor: not-allowed; padding: 2px 4px;">&lsaquo;</span>
                        <span style="display: inline-flex; align-items: center; justify-content: center; min-width: 26px; height: 26px; background: #e5e7eb; color: #1e3a8a; font-weight: 600; border-radius: 2px; font-size: 13px;">1</span>
                        <span style="color: #9ca3af; cursor: not-allowed; padding: 2px 4px;">&rsaquo;</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Add Payment Voucher Modal --}}
<div id="addPaymentModal" style="display: none; position: fixed; inset: 0; z-index: 9999; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; padding: 16px;">
    <div style="background: #ffffff; border-radius: 8px; max-width: 580px; width: 100%; box-shadow: 0 10px 25px rgba(0,0,0,0.2); overflow: hidden; animation: modalFadeIn 0.2s ease;">
        <div style="background-color: #1e3a8a; color: #ffffff; padding: 14px 20px; display: flex; justify-content: space-between; align-items: center;">
            <h4 style="margin: 0; font-size: 16px; font-weight: 600;"><i class="fa fa-plus-circle"></i> Add Payment Voucher</h4>
            <button type="button" onclick="closeAddPaymentModal()" style="background: transparent; border: none; color: #ffffff; font-size: 20px; cursor: pointer; line-height: 1;">&times;</button>
        </div>
        <form id="addPaymentForm" action="{{ route('admin.account.payments.store') }}" method="POST" onsubmit="submitAddPaymentForm(event)">
            @csrf
            <div style="padding: 20px; max-height: calc(100vh - 200px); overflow-y: auto;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                    <div class="form-group">
                        <label style="font-weight: 500; font-size: 13px; color: #374151; margin-bottom: 4px; display: block;">Branch <span style="color: #ef4444;">*</span></label>
                        <select name="brc_id" required class="form-control-cmsc" style="width: 100%; height: 34px; padding: 4px 10px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 13px;">
                            @foreach ($branchlist as $brc)
                                <option value="{{ $brc->id }}" {{ (string)$brc_id === (string)$brc->id ? 'selected' : '' }}>{{ $brc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 500; font-size: 13px; color: #374151; margin-bottom: 4px; display: block;">Date <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="date" required value="{{ date('d/m/Y') }}" class="form-control-cmsc" placeholder="DD/MM/YYYY" style="width: 100%; height: 34px; padding: 4px 10px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 13px;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-top: 12px;">
                    <div class="form-group">
                        <label style="font-weight: 500; font-size: 13px; color: #374151; margin-bottom: 4px; display: block;">Accounts Name (Payment From) <span style="color: #ef4444;">*</span></label>
                        <select name="par_acc_head_id" required class="form-control-cmsc" style="width: 100%; height: 34px; padding: 4px 10px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 13px;">
                            <option value="">Select Account</option>
                            @foreach ($acclist as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 500; font-size: 13px; color: #374151; margin-bottom: 4px; display: block;">Payment To <span style="color: #ef4444;">*</span></label>
                        <select id="add_payment_to" name="payment_to" onchange="toggleAddPayeeFields(this.value)" class="form-control-cmsc" style="width: 100%; height: 34px; padding: 4px 10px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 13px;">
                            <option value="2" selected>Supplier</option>
                            <option value="3">Staff</option>
                            <option value="1">Direct Account</option>
                        </select>
                    </div>
                </div>

                {{-- Payee Selection Fields --}}
                <div id="add_supplier_group" class="form-group" style="margin-top: 12px;">
                    <label style="font-weight: 500; font-size: 13px; color: #374151; margin-bottom: 4px; display: block;">Select Supplier <span style="color: #ef4444;">*</span></label>
                    <select name="supplier_id" id="add_supplier_id" class="form-control-cmsc" style="width: 100%; height: 34px; padding: 4px 10px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 13px;">
                        <option value="">Select Supplier</option>
                        @foreach ($supplierlist as $sup)
                            <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="add_staff_group" class="form-group" style="margin-top: 12px; display: none;">
                    <label style="font-weight: 500; font-size: 13px; color: #374151; margin-bottom: 4px; display: block;">Select Staff <span style="color: #ef4444;">*</span></label>
                    <select name="staff_id" id="add_staff_id" class="form-control-cmsc" style="width: 100%; height: 34px; padding: 4px 10px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 13px;">
                        <option value="">Select Staff</option>
                        @foreach ($stafflist as $stf)
                            <option value="{{ $stf->id }}">{{ $stf->name }} {{ $stf->surname }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="add_acc_head_group" class="form-group" style="margin-top: 12px; display: none;">
                    <label style="font-weight: 500; font-size: 13px; color: #374151; margin-bottom: 4px; display: block;">Select Account Head <span style="color: #ef4444;">*</span></label>
                    <select name="acc_head_id" id="add_acc_head_id" class="form-control-cmsc" style="width: 100%; height: 34px; padding: 4px 10px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 13px;">
                        <option value="">Select Account Head</option>
                        @foreach ($acclist as $acc)
                            <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="margin-top: 12px;">
                    <label style="font-weight: 500; font-size: 13px; color: #374151; margin-bottom: 4px; display: block;">Amount <span style="color: #ef4444;">*</span></label>
                    <input type="number" step="0.01" name="amount" required class="form-control-cmsc" placeholder="0.00" style="width: 100%; height: 34px; padding: 4px 10px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 13px;">
                </div>

                <div class="form-group" style="margin-top: 12px;">
                    <label style="font-weight: 500; font-size: 13px; color: #374151; margin-bottom: 4px; display: block;">Description / Note</label>
                    <textarea name="note" rows="2" class="form-control-cmsc" placeholder="Enter description..." style="width: 100%; padding: 6px 10px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 13px;"></textarea>
                </div>
            </div>
            <div style="background: #f9fafb; padding: 12px 20px; display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #e5e7eb;">
                <button type="button" onclick="closeAddPaymentModal()" style="background: #e5e7eb; color: #374151; border: none; padding: 6px 14px; border-radius: 4px; font-size: 13px; cursor: pointer;">Cancel</button>
                <button type="submit" id="btnAddPaymentSave" style="background-color: #1e3a8a; color: #ffffff; border: none; padding: 6px 18px; border-radius: 4px; font-size: 13px; font-weight: 500; cursor: pointer;">Save Voucher</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Payment Voucher Modal --}}
<div id="editPaymentModal" style="display: none; position: fixed; inset: 0; z-index: 9999; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; padding: 16px;">
    <div style="background: #ffffff; border-radius: 8px; max-width: 580px; width: 100%; box-shadow: 0 10px 25px rgba(0,0,0,0.2); overflow: hidden; animation: modalFadeIn 0.2s ease;">
        <div style="background-color: #1e3a8a; color: #ffffff; padding: 14px 20px; display: flex; justify-content: space-between; align-items: center;">
            <h4 style="margin: 0; font-size: 16px; font-weight: 600;"><i class="fa fa-pencil"></i> Edit Payment Voucher</h4>
            <button type="button" onclick="closeEditPaymentModal()" style="background: transparent; border: none; color: #ffffff; font-size: 20px; cursor: pointer; line-height: 1;">&times;</button>
        </div>
        <form id="editPaymentForm" method="POST" onsubmit="submitEditPaymentForm(event)">
            @csrf
            <input type="hidden" id="edit_payment_id" name="id">
            <div style="padding: 20px; max-height: calc(100vh - 200px); overflow-y: auto;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                    <div class="form-group">
                        <label style="font-weight: 500; font-size: 13px; color: #374151; margin-bottom: 4px; display: block;">Date <span style="color: #ef4444;">*</span></label>
                        <input type="text" id="edit_date" name="date" required class="form-control-cmsc" placeholder="DD/MM/YYYY" style="width: 100%; height: 34px; padding: 4px 10px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 13px;">
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 500; font-size: 13px; color: #374151; margin-bottom: 4px; display: block;">Accounts Name (Payment From) <span style="color: #ef4444;">*</span></label>
                        <select id="edit_par_acc_head_id" name="par_acc_head_id" required class="form-control-cmsc" style="width: 100%; height: 34px; padding: 4px 10px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 13px;">
                            <option value="">Select Account</option>
                            @foreach ($acclist as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 12px;">
                    <label style="font-weight: 500; font-size: 13px; color: #374151; margin-bottom: 4px; display: block;">Payment To</label>
                    <select id="edit_payment_to" name="payment_to" onchange="toggleEditPayeeFields(this.value)" class="form-control-cmsc" style="width: 100%; height: 34px; padding: 4px 10px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 13px;">
                        <option value="2">Supplier</option>
                        <option value="3">Staff</option>
                        <option value="1">Direct Account</option>
                    </select>
                </div>

                <div id="edit_supplier_group" class="form-group" style="margin-top: 12px;">
                    <label style="font-weight: 500; font-size: 13px; color: #374151; margin-bottom: 4px; display: block;">Select Supplier</label>
                    <select name="supplier_id" id="edit_supplier_id" class="form-control-cmsc" style="width: 100%; height: 34px; padding: 4px 10px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 13px;">
                        <option value="">Select Supplier</option>
                        @foreach ($supplierlist as $sup)
                            <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="edit_staff_group" class="form-group" style="margin-top: 12px; display: none;">
                    <label style="font-weight: 500; font-size: 13px; color: #374151; margin-bottom: 4px; display: block;">Select Staff</label>
                    <select name="staff_id" id="edit_staff_id" class="form-control-cmsc" style="width: 100%; height: 34px; padding: 4px 10px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 13px;">
                        <option value="">Select Staff</option>
                        @foreach ($stafflist as $stf)
                            <option value="{{ $stf->id }}">{{ $stf->name }} {{ $stf->surname }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="edit_acc_head_group" class="form-group" style="margin-top: 12px; display: none;">
                    <label style="font-weight: 500; font-size: 13px; color: #374151; margin-bottom: 4px; display: block;">Select Account Head</label>
                    <select name="acc_head_id" id="edit_acc_head_id" class="form-control-cmsc" style="width: 100%; height: 34px; padding: 4px 10px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 13px;">
                        <option value="">Select Account Head</option>
                        @foreach ($acclist as $acc)
                            <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="margin-top: 12px;">
                    <label style="font-weight: 500; font-size: 13px; color: #374151; margin-bottom: 4px; display: block;">Amount <span style="color: #ef4444;">*</span></label>
                    <input type="number" step="0.01" id="edit_amount" name="amount" required class="form-control-cmsc" placeholder="0.00" style="width: 100%; height: 34px; padding: 4px 10px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 13px;">
                </div>

                <div class="form-group" style="margin-top: 12px;">
                    <label style="font-weight: 500; font-size: 13px; color: #374151; margin-bottom: 4px; display: block;">Description / Note</label>
                    <textarea id="edit_note" name="note" rows="2" class="form-control-cmsc" placeholder="Enter description..." style="width: 100%; padding: 6px 10px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 13px;"></textarea>
                </div>
            </div>
            <div style="background: #f9fafb; padding: 12px 20px; display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #e5e7eb;">
                <button type="button" onclick="closeEditPaymentModal()" style="background: #e5e7eb; color: #374151; border: none; padding: 6px 14px; border-radius: 4px; font-size: 13px; cursor: pointer;">Cancel</button>
                <button type="submit" id="btnEditPaymentSave" style="background-color: #1e3a8a; color: #ffffff; border: none; padding: 6px 18px; border-radius: 4px; font-size: 13px; font-weight: 500; cursor: pointer;">Update Voucher</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleCustomDates(periodVal) {
    var fromGrp = document.getElementById('customDateFromGroup');
    var toGrp = document.getElementById('customDateToGroup');
    var isPeriod = (periodVal === 'period');
    if (fromGrp) fromGrp.style.display = isPeriod ? 'block' : 'none';
    if (toGrp) toGrp.style.display = isPeriod ? 'block' : 'none';
}

function changePerPage(val) {
    var url = new URL(window.location.href);
    url.searchParams.set('per_page', val);
    url.searchParams.set('page', '1');
    window.location.href = url.toString();
}

function filterPaymentTable(query) {
    query = (query || '').toLowerCase().trim();
    var table = document.getElementById('paymentVouchersTable');
    if (!table) return;
    var rows = table.querySelectorAll('tbody tr');
    rows.forEach(function(row) {
        if (row.cells.length <= 1) return;
        var text = row.innerText.toLowerCase();
        row.style.display = text.includes(query) ? '' : 'none';
    });
}

function sortTable(n) {
    var table = document.getElementById('paymentVouchersTable');
    if (!table) return;
    var tbody = table.querySelector('tbody');
    var rows = Array.from(tbody.querySelectorAll('tr'));
    if (rows.length <= 1) return;

    var currentDir = table.getAttribute('data-sort-dir-' + n) || 'asc';
    var newDir = currentDir === 'asc' ? 'desc' : 'asc';
    table.setAttribute('data-sort-dir-' + n, newDir);

    var headers = table.querySelectorAll('thead th');
    headers.forEach(function(th, idx) {
        var span = th.querySelector('span');
        if (span) span.innerHTML = (idx === n) ? (newDir === 'asc' ? '▲' : '▼') : '▾';
    });

    rows.sort(function(a, b) {
        var aCell = a.cells[n] ? a.cells[n].innerText.trim() : '';
        var bCell = b.cells[n] ? b.cells[n].innerText.trim() : '';

        // Date detection DD/MM/YYYY
        if (aCell.match(/^\d{2}\/\d{2}\/\d{4}$/) && bCell.match(/^\d{2}\/\d{2}\/\d{4}$/)) {
            var aParts = aCell.split('/');
            var bParts = bCell.split('/');
            var aDate = new Date(aParts[2], aParts[1] - 1, aParts[0]);
            var bDate = new Date(bParts[2], bParts[1] - 1, bParts[0]);
            return newDir === 'asc' ? aDate - bDate : bDate - aDate;
        }

        // Numeric or currency detection
        var aNum = parseFloat(aCell.replace(/,/g, ''));
        var bNum = parseFloat(bCell.replace(/,/g, ''));
        if (!isNaN(aNum) && !isNaN(bNum)) {
            return newDir === 'asc' ? aNum - bNum : bNum - aNum;
        }

        // Text comparison
        return newDir === 'asc' ? aCell.localeCompare(bCell) : bCell.localeCompare(aCell);
    });

    rows.forEach(function(r) { tbody.appendChild(r); });
}

function toggleActionMenu(e, menuId) {
    if (e && e.stopPropagation) e.stopPropagation();
    var allMenus = document.querySelectorAll('.action-menu-dropdown');
    allMenus.forEach(function(m) { if (m.id !== menuId) m.style.display = 'none'; });
    var menu = document.getElementById(menuId);
    if (menu) {
        var isHidden = menu.style.display === 'none' || menu.style.display === '';
        if (isHidden) {
            menu.style.display = 'block';
            var rect = menu.getBoundingClientRect();
            if (rect.bottom > window.innerHeight - 10) {
                menu.style.top = 'auto';
                menu.style.bottom = '100%';
                menu.style.marginBottom = '4px';
                menu.style.marginTop = '0';
            } else {
                menu.style.top = '100%';
                menu.style.bottom = 'auto';
                menu.style.marginTop = '2px';
                menu.style.marginBottom = '0';
            }
        } else {
            menu.style.display = 'none';
        }
    }
}

document.addEventListener('click', function() {
    document.querySelectorAll('.action-menu-dropdown').forEach(function(m) { m.style.display = 'none'; });
});

function exportTableCopy() {
    var table = document.getElementById('paymentVouchersTable');
    if (!table) return;
    var lines = [];
    var allRows = table.querySelectorAll('tr');
    for (var r = 0; r < allRows.length; r++) {
        if (allRows[r].style.display === 'none') continue;
        var cells = allRows[r].querySelectorAll('th,td');
        var rowArr = [];
        for (var c = 0; c < cells.length && c < 8; c++) rowArr.push(cells[c].innerText.trim());
        lines.push(rowArr.join('\t'));
    }
    var fullText = lines.join('\n');
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(fullText).then(function() { showToast('Table data copied to clipboard!', 'success'); }).catch(function() { _fallbackCopy(fullText); });
    } else { _fallbackCopy(fullText); }
}

function _fallbackCopy(text) {
    var ta = document.createElement('textarea');
    ta.value = text; ta.style.cssText = 'position:fixed;left:-9999px;top:-9999px;';
    document.body.appendChild(ta); ta.focus(); ta.select();
    try { document.execCommand('copy'); showToast('Table data copied to clipboard!', 'success'); } catch(e) { prompt('Copy:', text); }
    document.body.removeChild(ta);
}

function exportTableExcel() {
    var table = document.getElementById('paymentVouchersTable');
    if (!table) return;
    var html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><meta charset="utf-8"><\/head><body><table>';
    var allRows = table.querySelectorAll('tr');
    for (var r = 0; r < allRows.length; r++) {
        if (allRows[r].style.display === 'none') continue;
        html += '<tr>';
        var cells = allRows[r].querySelectorAll('th,td');
        for (var c = 0; c < cells.length && c < 8; c++) {
            var tag = cells[c].tagName.toLowerCase();
            var st = tag === 'th' ? 'background-color:#1e3a8a;color:#fff;font-weight:bold;border:1px solid #000;' : 'border:1px solid #ccc;';
            html += '<' + tag + ' style="' + st + '">' + cells[c].innerText.trim() + '<\/' + tag + '>';
        }
        html += '<\/tr>';
    }
    html += '<\/table><\/body><\/html>';
    var blob = new Blob([html], { type: 'application/vnd.ms-excel;charset=utf-8;' });
    var link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'Payment_Vouchers_' + new Date().toISOString().slice(0,10) + '.xls';
    document.body.appendChild(link); link.click(); document.body.removeChild(link);
}

function exportTableCSV() {
    var table = document.getElementById('paymentVouchersTable');
    if (!table) return;
    var csv = '\uFEFF';
    var allRows = table.querySelectorAll('tr');
    for (var r = 0; r < allRows.length; r++) {
        if (allRows[r].style.display === 'none') continue;
        var cells = allRows[r].querySelectorAll('th,td');
        var rowArr = [];
        for (var c = 0; c < cells.length && c < 8; c++) rowArr.push('"' + cells[c].innerText.trim().replace(/"/g, '""') + '"');
        csv += rowArr.join(',') + '\r\n';
    }
    var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    var link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'Payment_Vouchers_' + new Date().toISOString().slice(0,10) + '.csv';
    document.body.appendChild(link); link.click(); document.body.removeChild(link);
}

function exportTablePDF() { exportTablePrint(); }

function exportTablePrint() {
    var table = document.getElementById('paymentVouchersTable');
    if (!table) return;
    var clone = table.cloneNode(true);
    for (var i = 0; i < clone.rows.length; i++) {
        if (clone.rows[i].cells.length > 8) clone.rows[i].deleteCell(-1);
    }
    var w = window.open('', '_blank', 'width=1050,height=700');
    var str = '<!DOCTYPE html><html><head><title>Payment Vouchers Report<\/title>';
    str += '<style>@page{size:A4 landscape;margin:10mm}body{font-family:Arial,sans-serif;font-size:13px;color:#333;padding:20px}';
    str += 'h2{text-align:center;color:#1e3a8a;margin-bottom:4px;text-transform:uppercase}';
    str += 'p{text-align:center;color:#666;margin-top:0;font-size:12px}';
    str += 'table{width:100%;border-collapse:collapse;margin-top:15px}';
    str += 'th{background-color:#1e3a8a!important;color:#fff!important;padding:8px 10px;text-align:left;border:1px solid #1e3a8a;-webkit-print-color-adjust:exact;print-color-adjust:exact}';
    str += 'td{padding:8px 10px;border:1px solid #ddd}tr:nth-child(even) td{background-color:#f9f9f9}<\/style><\/head><body>';
    str += '<h2>PAYMENT VOUCHERS REPORT<\/h2><p>Generated: ' + new Date().toLocaleString() + '<\/p>';
    str += clone.outerHTML + '<\/body><\/html>';
    w.document.write(str); w.document.close(); w.focus();
    setTimeout(function() { w.print(); w.close(); }, 400);
}

function toggleColumnVisibilityMenu(e) {
    if (e && e.stopPropagation) e.stopPropagation();
    var menu = document.getElementById('colVisibilityDropdown');
    if (menu) menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
}

function toggleTableCol(colIndex, isVisible) {
    var table = document.getElementById('paymentVouchersTable');
    if (!table) return;
    for (var i = 0; i < table.rows.length; i++) {
        if (table.rows[i].cells[colIndex]) table.rows[i].cells[colIndex].style.display = isVisible ? '' : 'none';
    }
}

document.addEventListener('click', function(e) {
    var colMenu = document.getElementById('colVisibilityDropdown');
    if (colMenu && !colMenu.contains(e.target)) colMenu.style.display = 'none';
});

function toggleAddPayeeFields(val) {
    val = parseInt(val);
    document.getElementById('add_supplier_group').style.display = (val === 2) ? 'block' : 'none';
    document.getElementById('add_staff_group').style.display = (val === 3) ? 'block' : 'none';
    document.getElementById('add_acc_head_group').style.display = (val === 1) ? 'block' : 'none';
}

function toggleEditPayeeFields(val) {
    val = parseInt(val);
    document.getElementById('edit_supplier_group').style.display = (val === 2) ? 'block' : 'none';
    document.getElementById('edit_staff_group').style.display = (val === 3) ? 'block' : 'none';
    document.getElementById('edit_acc_head_group').style.display = (val === 1) ? 'block' : 'none';
}

function openAddPaymentModal() {
    var m = document.getElementById('addPaymentModal');
    if (m) { m.style.display = 'flex'; document.body.style.overflow = 'hidden'; }
}

function closeAddPaymentModal() {
    var m = document.getElementById('addPaymentModal');
    if (m) { m.style.display = 'none'; document.body.style.overflow = ''; }
}

function submitAddPaymentForm(e) {
    e.preventDefault();
    var form = document.getElementById('addPaymentForm');
    var fd = new FormData(form);
    var btn = document.getElementById('btnAddPaymentSave');
    btn.disabled = true; btn.innerHTML = 'Saving...';
    fetch(form.action, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(function(r) { return r.json(); })
    .then(function(d) {
        btn.disabled = false; btn.innerHTML = 'Save Voucher';
        if (d.status === 'success') {
            closeAddPaymentModal();
            var brcVal = document.getElementById('brc_id') ? document.getElementById('brc_id').value : '1';
            window.location.href = '{{ route("admin.account.payments.index") }}?brc_id=' + brcVal + '&period=all';
        } else {
            alert(d.message || 'Error saving.');
        }
    })
    .catch(function() { btn.disabled = false; btn.innerHTML = 'Save Voucher'; form.submit(); });
}

function openEditPaymentModal(id) {
    fetch('{{ url("admin/account/payments") }}/' + id, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(function(r) { return r.json(); })
    .then(function(res) {
        if (res.status === 'success') {
            var d = res.data;
            document.getElementById('edit_payment_id').value = d.id;
            document.getElementById('edit_date').value = d.date || '';
            document.getElementById('edit_par_acc_head_id').value = d.par_acc_head_id || '';
            document.getElementById('edit_amount').value = d.debit_amount || '';
            document.getElementById('edit_note').value = d.note || '';

            var payToVal = d.supplier_id ? 2 : (d.staff_id ? 3 : 1);
            document.getElementById('edit_payment_to').value = payToVal;
            toggleEditPayeeFields(payToVal);
            if (d.supplier_id) document.getElementById('edit_supplier_id').value = d.supplier_id;
            if (d.staff_id) document.getElementById('edit_staff_id').value = d.staff_id;
            if (d.acc_head_id) document.getElementById('edit_acc_head_id').value = d.acc_head_id;

            document.getElementById('editPaymentForm').action = '{{ url("admin/account/payments") }}/' + d.id;
            var m = document.getElementById('editPaymentModal');
            m.style.display = 'flex'; document.body.style.overflow = 'hidden';
        } else alert('Error loading data.');
    });
}

function closeEditPaymentModal() {
    var m = document.getElementById('editPaymentModal');
    if (m) { m.style.display = 'none'; document.body.style.overflow = ''; }
}

function submitEditPaymentForm(e) {
    e.preventDefault();
    var form = document.getElementById('editPaymentForm');
    var fd = new FormData(form);
    var btn = document.getElementById('btnEditPaymentSave');
    btn.disabled = true; btn.innerHTML = 'Updating...';
    fetch(form.action, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(function(r) { return r.json(); })
    .then(function(d) {
        btn.disabled = false; btn.innerHTML = 'Update Voucher';
        if (d.status === 'success') {
            closeEditPaymentModal();
            var brcVal = document.getElementById('brc_id') ? document.getElementById('brc_id').value : '1';
            window.location.href = '{{ route("admin.account.payments.index") }}?brc_id=' + brcVal + '&period=all';
        } else {
            alert(d.message || 'Error updating.');
        }
    })
    .catch(function() { btn.disabled = false; btn.innerHTML = 'Update Voucher'; form.submit(); });
}

function numberToWords(num) {
    var a = ['','One ','Two ','Three ','Four ', 'Five ','Six ','Seven ','Eight ','Nine ','Ten ','Eleven ','Twelve ','Thirteen ','Fourteen ','Fifteen ','Sixteen ','Seventeen ','Eighteen ','Nineteen '];
    var b = ['', '', 'Twenty','Thirty','Forty','Fifty', 'Sixty','Seventy','Eighty','Ninety'];
    num = parseInt(num);
    if (isNaN(num) || num === 0) return 'Zero Rupees Only';
    function inWords(n) {
        if ((n = n.toString()).length > 9) return 'overflow';
        var n_array = ('000000000' + n).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
        if (!n_array) return '';
        var str = '';
        str += (Number(n_array[1]) != 0) ? (a[Number(n_array[1])] || b[n_array[1][0]] + ' ' + a[n_array[1][1]]) + 'Crore ' : '';
        str += (Number(n_array[2]) != 0) ? (a[Number(n_array[2])] || b[n_array[2][0]] + ' ' + a[n_array[2][1]]) + 'Lakh ' : '';
        str += (Number(n_array[3]) != 0) ? (a[Number(n_array[3])] || b[n_array[3][0]] + ' ' + a[n_array[3][1]]) + 'Thousand ' : '';
        str += (Number(n_array[4]) != 0) ? (a[Number(n_array[4])] || b[n_array[4][0]] + ' ' + a[n_array[4][1]]) + 'Hundred ' : '';
        str += (Number(n_array[5]) != 0) ? ((str != '') ? 'and ' : '') + (a[Number(n_array[5])] || b[n_array[5][0]] + ' ' + a[n_array[5][1]]) : '';
        return str;
    }
    return inWords(num).trim() + ' Rupees Only';
}

function printSinglePayment(id) {
    fetch('{{ url("admin/account/payments") }}/' + id, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(function(r) { return r.json(); })
    .then(function(res) {
        if (res.status === 'success') {
            var d = res.data;
            var docNo = d.invoice_no || d.id;
            var formattedDate = d.date ? (d.date.indexOf('-') > -1 ? d.date.split('-').reverse().join('/') : d.date) : '';
            var payee = d.supplier_name || (d.staff_name ? d.staff_name.trim() : (d.accounts_name || 'abc'));
            var particulars = d.note || (d.accounts_name ? d.accounts_name : 'Payment');
            var amt = Number(d.debit_amount || 0);
            var formattedAmt = amt.toLocaleString('en-US');
            var amtInWords = numberToWords(amt);
            var branchName = d.branch_name || 'Tnt Sol';

            var renderVoucherSide = function(copyType, isOffice) {
                var sigSection = isOffice ? 
                    '<div style="margin-top: 30px;">' +
                        '<div style="display: flex; justify-content: space-between; font-size: 11px; font-weight: bold;">' +
                            '<div><span style="border-top: 1px dashed #000; padding-top: 2px;">Paid to:</span></div>' +
                            '<div><span style="border-top: 1px dashed #000; padding-top: 2px;">Prepared By:</span></div>' +
                            '<div><span style="border-top: 1px dashed #000; padding-top: 2px;">Account Manager:</span></div>' +
                        '</div>' +
                        '<div style="display: flex; justify-content: space-between; font-size: 11px; font-weight: bold; margin-top: 25px;">' +
                            '<div><span style="border-top: 1px dashed #000; padding-top: 2px;">Director Finance:</span></div>' +
                            '<div><span style="border-top: 1px dashed #000; padding-top: 2px;">Audit By:</span></div>' +
                        '</div>' +
                    '</div>' :
                    '<div style="margin-top: 65px;">' +
                        '<div style="display: flex; justify-content: space-between; font-size: 11px; font-weight: bold;">' +
                            '<div><span style="border-top: 1px dashed #000; padding-top: 2px;">Paid to:</span></div>' +
                            '<div><span style="border-top: 1px dashed #000; padding-top: 2px;">Cashier\'s / Accountant\'s</span></div>' +
                        '</div>' +
                    '</div>';

                return '<div style="width: 48.5%; border: 1px solid #000; padding: 8px 10px; box-sizing: border-box;">' +
                    '<div style="text-align: center; font-size: 11px; font-weight: bold; margin-bottom: 2px;">' + copyType + '</div>' +
                    '<div style="display: flex; align-items: center; border-bottom: 1px solid #000; padding-bottom: 6px; margin-bottom: 6px;">' +
                        '<div style="width: 25%; text-align: left;">' +
                            '<div style="font-family: Arial, sans-serif; font-weight: 900; font-size: 18px; color: #1e3a8a; line-height: 1;">TNT</div>' +
                            '<div style="font-size: 8px; font-weight: bold; color: #555; letter-spacing: 0.5px;">SOLUTIONS</div>' +
                            '<div style="font-size: 7px; color: #777;">Road To Technology</div>' +
                        '</div>' +
                        '<div style="width: 75%; text-align: center;">' +
                            '<div style="font-size: 15px; font-weight: bold; color: #000; text-transform: uppercase;">' + branchName + '</div>' +
                            '<div style="font-size: 11px; font-weight: bold; color: #000;">Gujranwala</div>' +
                            '<div style="font-size: 11px; font-weight: bold; color: #000;">923466049180</div>' +
                            '<div style="font-size: 11px; font-weight: bold; color: #000; margin-top: 1px;">Cash Expense Bill</div>' +
                        '</div>' +
                    '</div>' +
                    '<div style="display: flex; justify-content: space-between; font-size: 12px; font-weight: bold; border-bottom: 1px solid #000; padding: 3px 0;">' +
                        '<div>Date:- ' + formattedDate + '</div>' +
                        '<div>Bill No#:- ' + docNo + '</div>' +
                    '</div>' +
                    '<div style="font-size: 12px; font-weight: bold; border-bottom: 1px solid #000; padding: 4px 0;">' +
                        'Paid to:- ' + payee +
                    '</div>' +
                    '<table style="width: 100%; border-collapse: collapse; margin-top: 6px; border: 1px solid #000; font-size: 12px;">' +
                        '<thead>' +
                            '<tr>' +
                                '<th style="border: 1px solid #000; padding: 3px 6px; width: 12%; text-align: left;">Sr#</th>' +
                                '<th style="border: 1px solid #000; padding: 3px 6px; width: 63%; text-align: left;">Particulars</th>' +
                                '<th style="border: 1px solid #000; padding: 3px 6px; width: 25%; text-align: right;">Amount</th>' +
                            '</tr>' +
                        '</thead>' +
                        '<tbody>' +
                            '<tr>' +
                                '<td style="border: 1px solid #000; padding: 4px 6px; text-align: left;">1</td>' +
                                '<td style="border: 1px solid #000; padding: 4px 6px; text-align: left;">' + particulars + '</td>' +
                                '<td style="border: 1px solid #000; padding: 4px 6px; text-align: right;">' + formattedAmt + '</td>' +
                            '</tr>' +
                            '<tr>' +
                                '<td colspan="2" style="border: 1px solid #000; padding: 4px 6px; text-align: right; font-weight: bold;">Total Amount:-</td>' +
                                '<td style="border: 1px solid #000; padding: 4px 6px; text-align: right; font-weight: bold;">' + formattedAmt + '</td>' +
                            '</tr>' +
                        '</tbody>' +
                    '</table>' +
                    '<div style="display: flex; align-items: flex-end; margin-top: 10px; font-size: 11.5px;">' +
                        '<div style="white-space: nowrap;">Rupees In Words:</div>' +
                        '<div style="flex: 1; border-bottom: 1px solid #000; margin-left: 6px; font-size: 11px; padding-left: 4px;">' + amtInWords + '</div>' +
                    '</div>' +
                    '<div style="margin-top: 6px; font-size: 11.5px;">' +
                        '<strong>Note:</strong> ' + (d.note || '') +
                    '</div>' +
                    sigSection +
                '</div>';
            };

            var w = window.open('', '_blank', 'width=1050,height=700');
            var s = '<!DOCTYPE html><html><head><title>Bill #' + docNo + '<\/title>';
            s += '<style>@page{size:A4 landscape;margin:8mm}body{font-family:Arial,sans-serif;font-size:12px;color:#000;margin:0;padding:0;background:#fff}';
            s += '.voucher-container{display:flex;width:100%;justify-content:space-between;box-sizing:border-box}';
            s += '.voucher-separator{width:0;border-right:1px dashed #555;margin:0 4px}';
            s += '<\/style><\/head><body>';
            s += '<div class="voucher-container">';
            s += renderVoucherSide('Office Copy', true);
            s += '<div class="voucher-separator"></div>';
            s += renderVoucherSide('Payee Copy', false);
            s += '</div><\/body><\/html>';
            w.document.write(s); w.document.close(); w.focus();
            setTimeout(function() { w.print(); w.close(); }, 400);
        }
    });
}

function deletePayment(id) {
    if (!confirm('Are you sure you want to delete this Payment Voucher?')) return;
    var token = document.querySelector('input[name=_token]') ? document.querySelector('input[name=_token]').value : '';
    fetch('{{ url("admin/account/payments/delete") }}/' + id, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': token }
    })
    .then(function(r) { return r.json(); })
    .then(function(d) {
        if (d.status === 'success') {
            showToast(d.message || 'Payment Voucher deleted successfully!', 'success');
            var brcVal = document.getElementById('brc_id') ? document.getElementById('brc_id').value : '1';
            setTimeout(function() {
                window.location.href = '{{ route("admin.account.payments.index") }}?brc_id=' + brcVal + '&period=all';
            }, 600);
        } else {
            showToast(d.message || 'Error deleting.', 'error');
        }
    })
    .catch(function() { window.location.reload(); });
}

function showToast(message, type) {
    type = type || 'success';
    var existing = document.getElementById('appToast');
    if (existing) existing.remove();

    var toastConfig = {
        success: { bg: 'bg-green-100', border: 'border-green-500', text: 'text-green-700', prog: 'bg-green-500', title: 'Success', icon: '✓' },
        error:   { bg: 'bg-red-100',   border: 'border-red-500',   text: 'text-red-700',   prog: 'bg-red-500',   title: 'Error',   icon: '×' },
        warning: { bg: 'bg-yellow-100',border: 'border-yellow-500',text: 'text-yellow-700',prog: 'bg-yellow-500',title: 'Warning', icon: '!' },
        info:    { bg: 'bg-blue-100',  border: 'border-blue-500',  text: 'text-blue-700',  prog: 'bg-blue-500',  title: 'Info',    icon: 'i' }
    };
    var cfg = toastConfig[type] || toastConfig.success;

    var toast = document.createElement('div');
    toast.id = 'appToast';
    toast.className = 'fixed top-3.5 right-3.5 z-[9999] w-[240px] max-w-[calc(100vw-2rem)] overflow-hidden rounded-md ' + cfg.bg + ' border-l-[3px] ' + cfg.border + ' ' + cfg.text + ' shadow-md toast-slide-in';
    
    toast.innerHTML = '<div class="flex items-start gap-2 px-2.5 py-1.5">' +
        '<div class="flex h-4 w-4 mt-0.5 shrink-0 items-center justify-center rounded-full ' + cfg.prog + ' text-white text-[9px] font-bold leading-none">' + cfg.icon + '</div>' +
        '<div class="flex-1 min-w-0">' +
            '<p class="text-[11px] font-bold leading-tight">' + cfg.title + '</p>' +
            '<p class="text-[10px] leading-tight mt-0.5 opacity-90 truncate">' + message + '</p>' +
        '</div>' +
        '<button type="button" onclick="hideToast()" class="text-xs font-bold opacity-60 hover:opacity-100 transition leading-none px-0.5">&times;</button>' +
    '</div>' +
    '<div class="toast-progress-track"><div id="toastProgress" class="toast-progress ' + cfg.prog + '"></div></div>';

    document.body.appendChild(toast);

    setTimeout(function() {
        if (typeof hideToast === 'function') hideToast();
    }, 3000);
}
</script>
@endpush
