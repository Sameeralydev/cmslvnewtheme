<?php

namespace App\Http\Controllers\Admin\Account;

use App\Models\Branch;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class PaymentVoucherController extends BaseAccountController
{
    /**
     * Display the Payment Vouchers list matching the exact UI criteria.
     */
    public function index(Request $request): View
    {
        $brc_id = $this->resolveBranchId($request);
        $accounts_id = $request->input('accounts_id', '');
        $supplier_id = $request->input('supplier_id', '');
        $staff_id = $request->input('staff_id', '');
        $period = $request->input('period', 'all');
        $startDate = $request->input('start_date', '');
        $endDate = $request->input('end_date', '');
        $searchKeyword = trim((string) $request->input('search', ''));
        $perPage = (int) $request->input('per_page', 100);
        if (!in_array($perPage, [10, 25, 50, 100, 250, 500])) {
            $perPage = 100;
        }

        // 1. Branches
        $branchlist = Schema::hasTable('branches')
            ? Branch::query()->where('is_active', 'yes')->orWhere('is_active', '1')->orderBy('id', 'asc')->get()
            : (Schema::hasTable('branch') ? DB::table('branch')->orderBy('id', 'asc')->get() : collect());

        // 2. Accounts List (Accounts Head)
        $acclist = collect();
        if (Schema::hasTable('accountshead')) {
            $acclist = DB::table('accountshead')
                ->where(function ($q) use ($brc_id) {
                    $q->whereNull('brc_id')->orWhere('brc_id', 0)->orWhere('brc_id', $brc_id);
                })
                ->orderBy('name', 'asc')
                ->get();
        }

        // 3. Suppliers List
        $supplierlist = collect();
        if (Schema::hasTable('supplier')) {
            $supplierQuery = DB::table('supplier');
            if ($brc_id > 0 && Schema::hasColumn('supplier', 'brc_id')) {
                $supplierQuery->where(function($q) use ($brc_id) {
                    $q->whereNull('brc_id')->orWhere('brc_id', 0)->orWhere('brc_id', $brc_id);
                });
            }
            $supplierlist = $supplierQuery->orderBy('name', 'asc')->get();
        }

        // 4. Staff List
        $stafflist = collect();
        if (Schema::hasTable('staff')) {
            $staffQuery = DB::table('staff');
            if ($brc_id > 0 && Schema::hasColumn('staff', 'brc_id')) {
                $staffQuery->where(function($q) use ($brc_id) {
                    $q->whereNull('brc_id')->orWhere('brc_id', 0)->orWhere('brc_id', $brc_id);
                });
            }
            $stafflist = $staffQuery->orderBy('name', 'asc')->get();
        }

        // 5. Query payments_voucher
        $query = DB::table('payments_voucher')
            ->leftJoin('accountshead', 'accountshead.id', '=', 'payments_voucher.par_acc_head_id')
            ->leftJoin('supplier', 'supplier.id', '=', 'payments_voucher.supplier_id')
            ->leftJoin('staff', 'staff.id', '=', 'payments_voucher.staff_id')
            ->select([
                'payments_voucher.id',
                'payments_voucher.invoice_no as document_no',
                'payments_voucher.date',
                'payments_voucher.note as description',
                'payments_voucher.debit_amount as amount',
                'payments_voucher.par_acc_head_id',
                'payments_voucher.supplier_id',
                'payments_voucher.staff_id',
                'payments_voucher.brc_id',
                'accountshead.name as accounts_name',
                'supplier.name as supplier_name',
                DB::raw("CONCAT(COALESCE(staff.name, ''), ' ', COALESCE(staff.surname, '')) as staff_name")
            ]);

        // Branch filter
        if ($brc_id > 0) {
            $query->where('payments_voucher.brc_id', $brc_id);
        }

        // Accounts filter
        if (!empty($accounts_id)) {
            $query->where('payments_voucher.par_acc_head_id', $accounts_id);
        }

        // Supplier filter
        if (!empty($supplier_id)) {
            $query->where('payments_voucher.supplier_id', $supplier_id);
        }

        // Staff filter
        if (!empty($staff_id)) {
            $query->where('payments_voucher.staff_id', $staff_id);
        }

        // Period filter
        $now = Carbon::now();
        if ($period === 'today') {
            $query->whereDate('payments_voucher.date', $now->toDateString());
        } elseif ($period === 'this_week') {
            $query->whereBetween('payments_voucher.date', [$now->startOfWeek()->toDateString(), $now->endOfWeek()->toDateString()]);
        } elseif ($period === 'this_month') {
            $query->whereYear('payments_voucher.date', $now->year)->whereMonth('payments_voucher.date', $now->month);
        } elseif ($period === 'last_month') {
            $lastMonth = Carbon::now()->subMonth();
            $query->whereYear('payments_voucher.date', $lastMonth->year)->whereMonth('payments_voucher.date', $lastMonth->month);
        } elseif ($period === 'this_year') {
            $query->whereYear('payments_voucher.date', $now->year);
        } elseif ($period === 'period' && !empty($startDate) && !empty($endDate)) {
            $s = Carbon::parse(str_replace('/', '-', $startDate))->format('Y-m-d');
            $e = Carbon::parse(str_replace('/', '-', $endDate))->format('Y-m-d');
            $query->whereBetween('payments_voucher.date', [$s, $e]);
        }

        // Keyword Search
        if (!empty($searchKeyword)) {
            $query->where(function ($q) use ($searchKeyword) {
                $q->where('payments_voucher.invoice_no', 'like', "%{$searchKeyword}%")
                  ->orWhere('payments_voucher.note', 'like', "%{$searchKeyword}%")
                  ->orWhere('accountshead.name', 'like', "%{$searchKeyword}%")
                  ->orWhere('supplier.name', 'like', "%{$searchKeyword}%")
                  ->orWhere('staff.name', 'like', "%{$searchKeyword}%")
                  ->orWhere('staff.surname', 'like', "%{$searchKeyword}%");
            });
        }

        // Order & paginate
        $records = $query->orderBy('payments_voucher.date', 'desc')
            ->orderBy('payments_voucher.id', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.account.payments.index', [
            'brc_id' => $brc_id,
            'branchlist' => $branchlist,
            'acclist' => $acclist,
            'supplierlist' => $supplierlist,
            'stafflist' => $stafflist,
            'accounts_id' => $accounts_id,
            'supplier_id' => $supplier_id,
            'staff_id' => $staff_id,
            'period' => $period,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'searchKeyword' => $searchKeyword,
            'perPage' => $perPage,
            'records' => $records,
        ]);
    }

    /**
     * Store a newly created Payment Voucher.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'brc_id' => 'required',
            'par_acc_head_id' => 'required',
            'date' => 'required',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $brcId = (int) $request->input('brc_id');
        $parAccHeadId = (int) $request->input('par_acc_head_id');
        $rawDate = $request->input('date');
        if (!empty($rawDate)) {
            if (str_contains($rawDate, '/')) {
                $parts = explode('/', $rawDate);
                $date = count($parts) === 3 ? sprintf('%04d-%02d-%02d', (int)$parts[2], (int)$parts[1], (int)$parts[0]) : Carbon::parse($rawDate)->format('Y-m-d');
            } else {
                $date = Carbon::parse($rawDate)->format('Y-m-d');
            }
        } else {
            $date = date('Y-m-d');
        }

        $amount = (float) $request->input('amount', 0);
        $paymentTo = (int) $request->input('payment_to', 2);
        $supplierId = $paymentTo === 2 ? (int) $request->input('supplier_id', 0) : null;
        $staffId = $paymentTo === 3 ? (int) $request->input('staff_id', 0) : null;
        $accHeadId = $paymentTo === 1 ? (int) $request->input('acc_head_id', 0) : null;
        $description = $request->input('note', $request->input('description', ''));
        $voucherTypeId = (int) $request->input('voucher_type_id', 1);
        $userId = $request->user() ? $request->user()->id : 1;

        // Auto-generate Document No (e.g. 26075)
        $lastDoc = DB::table('payments_voucher')->orderBy('id', 'desc')->first();
        $docNo = $lastDoc && is_numeric($lastDoc->invoice_no) ? (int) $lastDoc->invoice_no + 1 : 26071;
        if ($docNo < 26000) {
            $docNo = 26000 + ($lastDoc ? $lastDoc->id + 1 : 1);
        }

        DB::beginTransaction();
        try {
            $insertData = [
                'brc_id' => $brcId,
                'invoice_no' => (string) $docNo,
                'date' => $date,
                'par_acc_head_id' => $parAccHeadId,
                'debit_amount' => $amount,
                'credit_amount' => 0,
            ];

            if (Schema::hasColumn('payments_voucher', 'supplier_id')) {
                $insertData['supplier_id'] = $supplierId;
            }
            if (Schema::hasColumn('payments_voucher', 'staff_id')) {
                $insertData['staff_id'] = $staffId;
            }
            if (Schema::hasColumn('payments_voucher', 'acc_head_id')) {
                $insertData['acc_head_id'] = $accHeadId;
            }
            if (Schema::hasColumn('payments_voucher', 'voucher_type_id')) {
                $insertData['voucher_type_id'] = $voucherTypeId;
            }
            if (Schema::hasColumn('payments_voucher', 'voucher_type')) {
                $insertData['voucher_type'] = $voucherTypeId;
            }
            if (Schema::hasColumn('payments_voucher', 'note')) {
                $insertData['note'] = $description;
            }
            if (Schema::hasColumn('payments_voucher', 'created_by')) {
                $insertData['created_by'] = $userId;
            }
            if (Schema::hasColumn('payments_voucher', 'created_at')) {
                $insertData['created_at'] = now();
            }
            if (Schema::hasColumn('payments_voucher', 'updated_at')) {
                $insertData['updated_at'] = now();
            }

            $voucherId = DB::table('payments_voucher')->insertGetId($insertData);

            DB::commit();

            session()->flash('toast_message', 'Payment Voucher #' . $docNo . ' added successfully!');
            session()->flash('toast_type', 'success');

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Payment Voucher #' . $docNo . ' added successfully!',
                    'document_no' => $docNo,
                    'id' => $voucherId,
                ]);
            }

            return redirect()->route('admin.account.payments.index', ['brc_id' => $brcId, 'period' => 'all'])
                ->with('success', 'Payment Voucher #' . $docNo . ' added successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Error saving payment voucher: ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Error saving payment voucher: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show Payment Voucher Details for view/edit/print.
     */
    public function show(int $id): JsonResponse
    {
        $voucher = DB::table('payments_voucher')
            ->leftJoin('accountshead', 'accountshead.id', '=', 'payments_voucher.par_acc_head_id')
            ->leftJoin('supplier', 'supplier.id', '=', 'payments_voucher.supplier_id')
            ->leftJoin('staff', 'staff.id', '=', 'payments_voucher.staff_id')
            ->leftJoin('branch', 'branch.id', '=', 'payments_voucher.brc_id')
            ->where('payments_voucher.id', $id)
            ->select([
                'payments_voucher.*',
                'accountshead.name as accounts_name',
                'supplier.name as supplier_name',
                'branch.name as branch_name',
                DB::raw("CONCAT(COALESCE(staff.name, ''), ' ', COALESCE(staff.surname, '')) as staff_name")
            ])
            ->first();

        if (!$voucher) {
            return response()->json(['status' => 'fail', 'message' => 'Voucher not found'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $voucher,
        ]);
    }

    /**
     * Update an existing Payment Voucher.
     */
    public function update(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $voucher = DB::table('payments_voucher')->where('id', $id)->first();
        if (!$voucher) {
            return response()->json(['status' => 'fail', 'message' => 'Record not found'], 404);
        }

        $rawDate = $request->input('date', $voucher->date);
        if (!empty($rawDate)) {
            if (str_contains($rawDate, '/')) {
                $parts = explode('/', $rawDate);
                $date = count($parts) === 3 ? sprintf('%04d-%02d-%02d', (int)$parts[2], (int)$parts[1], (int)$parts[0]) : Carbon::parse($rawDate)->format('Y-m-d');
            } else {
                $date = Carbon::parse($rawDate)->format('Y-m-d');
            }
        } else {
            $date = date('Y-m-d');
        }

        $amount = (float) $request->input('amount', $voucher->debit_amount);
        $parAccHeadId = (int) $request->input('par_acc_head_id', $voucher->par_acc_head_id);
        $paymentTo = (int) $request->input('payment_to', 2);
        $supplierId = $paymentTo === 2 ? (int) $request->input('supplier_id', 0) : null;
        $staffId = $paymentTo === 3 ? (int) $request->input('staff_id', 0) : null;
        $accHeadId = $paymentTo === 1 ? (int) $request->input('acc_head_id', 0) : null;
        $description = $request->input('note', $request->input('description', $voucher->note ?? ''));

        DB::beginTransaction();
        try {
            $updateData = [
                'date' => $date,
                'par_acc_head_id' => $parAccHeadId,
                'debit_amount' => $amount,
                'supplier_id' => $supplierId,
                'staff_id' => $staffId,
                'acc_head_id' => $accHeadId,
            ];
            if (Schema::hasColumn('payments_voucher', 'note')) {
                $updateData['note'] = $description;
            }
            if (Schema::hasColumn('payments_voucher', 'updated_at')) {
                $updateData['updated_at'] = now();
            }

            DB::table('payments_voucher')->where('id', $id)->update($updateData);

            DB::commit();

            session()->flash('toast_message', 'Payment Voucher updated successfully!');
            session()->flash('toast_type', 'success');

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Payment Voucher updated successfully!',
                ]);
            }

            return redirect()->route('admin.account.payments.index', ['period' => 'all'])->with('success', 'Payment Voucher updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete a Payment Voucher.
     */
    public function destroy(int $id): JsonResponse|RedirectResponse
    {
        try {
            DB::table('payments_voucher')->where('id', $id)->delete();

            session()->flash('toast_message', 'Payment Voucher deleted successfully!');
            session()->flash('toast_type', 'success');

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Payment Voucher deleted successfully!',
                ]);
            }

            return redirect()->route('admin.account.payments.index', ['period' => 'all'])->with('success', 'Payment Voucher deleted successfully!');
        } catch (\Exception $e) {
            return response()->json(['status' => 'fail', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Print / Download Payment Voucher (2-Up format: Office Copy & Payee Copy) as PDF.
     */
    public function printVoucher(Request $request, int $id)
    {
        $voucher = DB::table('payments_voucher')
            ->leftJoin('accountshead', 'accountshead.id', '=', 'payments_voucher.par_acc_head_id')
            ->leftJoin('supplier', 'supplier.id', '=', 'payments_voucher.supplier_id')
            ->leftJoin('staff', 'staff.id', '=', 'payments_voucher.staff_id')
            ->leftJoin('branch', 'branch.id', '=', 'payments_voucher.brc_id')
            ->where('payments_voucher.id', $id)
            ->select([
                'payments_voucher.*',
                'accountshead.name as accounts_name',
                'supplier.name as supplier_name',
                'branch.name as branch_name',
                DB::raw("CONCAT(COALESCE(staff.name, ''), ' ', COALESCE(staff.surname, '')) as staff_name")
            ])
            ->first();

        if (!$voucher) {
            abort(404, 'Payment Voucher not found');
        }

        $schSetting = Schema::hasTable('sch_settings') ? DB::table('sch_settings')->first() : null;

        $logoBase64 = '';
        if (extension_loaded('gd')) {
            $possibleLogoPaths = [
                public_path('assets/images/s_logo.png'),
                public_path('assets/themes/default/images/logo.png'),
            ];
            foreach ($possibleLogoPaths as $p) {
                if (file_exists($p)) {
                    $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($p));
                    break;
                }
            }
        }

        $viewContent = view('admin.account.payments.print', [
            'voucher' => $voucher,
            'schSetting' => $schSetting,
            'logoBase64' => $logoBase64,
        ])->render();

        $docNo = $voucher->invoice_no ?: $voucher->id;
        $filename = 'Bill_' . $docNo . '.pdf';

        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Helvetica');
        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($viewContent);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        if ($request->has('view') && (string)$request->input('view') === '1') {
            return response($dompdf->output())
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
        }

        return response($dompdf->output())
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Resolve Branch ID helper.
     */
    protected function resolveBranchId(Request $request): int
    {
        if ($request->filled('brc_id')) {
            return (int) $request->input('brc_id');
        }
        if ($request->session()->has('brc_id')) {
            $sessId = (int) $request->session()->get('brc_id');
            if ($sessId > 0) return $sessId;
        }
        $user = $request->user();
        if ($user && !empty($user->brc_id)) {
            return (int) $user->brc_id;
        }
        return 1;
    }
}
