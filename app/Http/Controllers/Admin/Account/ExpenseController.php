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

class ExpenseController extends BaseAccountController
{
    /**
     * Display the Expense Bill List matching the exact UI criteria and layout.
     */
    public function index(Request $request): View
    {
        $brc_id = $this->resolveBranchId($request);
        $head_id = $request->input('expenses_head_id', '');
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

        // 2. Expense Heads from accountshead
        $expenseHeads = collect();
        if (Schema::hasTable('accountshead')) {
            $expenseHeads = DB::table('accountshead')
                ->where(function ($q) use ($brc_id) {
                    $q->whereNull('brc_id')->orWhere('brc_id', $brc_id);
                })
                ->orderBy('name', 'asc')
                ->get();
        }

        // 3. Query expenses_bill
        $query = DB::table('expenses_bill')
            ->select([
                'expenses_bill.id',
                'expenses_bill.invoice_no as bill_no',
                'expenses_bill.date',
                'expenses_bill.note as description',
                'expenses_bill.grand_total as amount',
                'expenses_bill.paid_to',
                'expenses_bill.brc_id',
                'expenses_bill.grand_total as total_amount'
            ]);

        // Branch filter
        if ($brc_id > 0) {
            $query->where('expenses_bill.brc_id', $brc_id);
        }

        // Expense Head filter (using whereExists for clean pagination without grouping)
        if (!empty($head_id)) {
            $query->whereExists(function ($sub) use ($head_id) {
                $sub->select(DB::raw(1))
                    ->from('expenses_bill_items')
                    ->whereColumn('expenses_bill_items.expenses_bill_id', 'expenses_bill.id')
                    ->where('expenses_bill_items.acc_head_id', $head_id);
            });
        }

        // Period filter
        $now = Carbon::now();
        if ($period === 'today') {
            $query->whereDate('expenses_bill.date', $now->toDateString());
        } elseif ($period === 'this_week') {
            $query->whereBetween('expenses_bill.date', [$now->startOfWeek()->toDateString(), $now->endOfWeek()->toDateString()]);
        } elseif ($period === 'this_month') {
            $query->whereYear('expenses_bill.date', $now->year)->whereMonth('expenses_bill.date', $now->month);
        } elseif ($period === 'last_month') {
            $lastMonth = Carbon::now()->subMonth();
            $query->whereYear('expenses_bill.date', $lastMonth->year)->whereMonth('expenses_bill.date', $lastMonth->month);
        } elseif ($period === 'this_year') {
            $query->whereYear('expenses_bill.date', $now->year);
        } elseif ($period === 'period' && !empty($startDate) && !empty($endDate)) {
            $s = Carbon::parse(str_replace('/', '-', $startDate))->format('Y-m-d');
            $e = Carbon::parse(str_replace('/', '-', $endDate))->format('Y-m-d');
            $query->whereBetween('expenses_bill.date', [$s, $e]);
        }

        // Search keyword
        if (!empty($searchKeyword)) {
            $query->where(function ($q) use ($searchKeyword) {
                $q->where('expenses_bill.invoice_no', 'like', "%{$searchKeyword}%")
                  ->orWhere('expenses_bill.note', 'like', "%{$searchKeyword}%")
                  ->orWhere('expenses_bill.paid_to', 'like', "%{$searchKeyword}%");
            });
        }

        // Order & paginate
        $records = $query->orderBy('expenses_bill.date', 'desc')
            ->orderBy('expenses_bill.id', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.account.expenses.index', [
            'brc_id' => $brc_id,
            'branchlist' => $branchlist,
            'expenseHeads' => $expenseHeads,
            'head_id' => $head_id,
            'period' => $period,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'searchKeyword' => $searchKeyword,
            'perPage' => $perPage,
            'records' => $records,
        ]);
    }

    /**
     * Store a newly created Expense Bill.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'brc_id' => 'required',
            'acc_head_id' => 'required',
            'date' => 'required',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $brcId = (int) $request->input('brc_id');
        $headId = (int) $request->input('acc_head_id');
        $date = Carbon::parse(str_replace('/', '-', $request->input('date', date('Y-m-d'))))->format('Y-m-d');
        $amount = (float) $request->input('amount', 0);
        $paidTo = $request->input('paid_to', '');
        $description = $request->input('note', $request->input('description', ''));
        $paymentMode = $request->input('payment_mode', 'cash');
        $userId = $request->user() ? $request->user()->id : 1;

        // Auto-generate Bill No (e.g. 26071)
        $lastBill = DB::table('expenses_bill')->orderBy('id', 'desc')->first();
        $billNo = $lastBill ? (int) $lastBill->invoice_no + 1 : 26071;
        if ($billNo < 26000) {
            $billNo = 26000 + ($lastBill ? $lastBill->id + 1 : 1);
        }

        DB::beginTransaction();
        try {
            $insertData = [
                'brc_id' => $brcId,
                'invoice_no' => (string) $billNo,
                'date' => $date,
                'grand_total' => $amount,
            ];

            if (Schema::hasColumn('expenses_bill', 'paid_to')) {
                $insertData['paid_to'] = $paidTo;
            }
            if (Schema::hasColumn('expenses_bill', 'note')) {
                $insertData['note'] = $description;
            }
            if (Schema::hasColumn('expenses_bill', 'payment_mode')) {
                $insertData['payment_mode'] = $paymentMode;
            }
            if (Schema::hasColumn('expenses_bill', 'par_acc_head_id')) {
                $insertData['par_acc_head_id'] = $paymentMode === 'bank' ? 2 : 1;
            }
            if (Schema::hasColumn('expenses_bill', 'voucher_type')) {
                $insertData['voucher_type'] = 3;
            }
            if (Schema::hasColumn('expenses_bill', 'voucher_type_id')) {
                $insertData['voucher_type_id'] = 1;
            }
            if (Schema::hasColumn('expenses_bill', 'bill_type')) {
                $insertData['bill_type'] = 1;
            }
            if (Schema::hasColumn('expenses_bill', 'profit_acc_head_id')) {
                $insertData['profit_acc_head_id'] = 108;
            }
            if (Schema::hasColumn('expenses_bill', 'is_active')) {
                $insertData['is_active'] = 'yes';
            }
            if (Schema::hasColumn('expenses_bill', 'created_by')) {
                $insertData['created_by'] = $userId;
            }
            if (Schema::hasColumn('expenses_bill', 'created_at')) {
                $insertData['created_at'] = now();
            }
            if (Schema::hasColumn('expenses_bill', 'updated_at')) {
                $insertData['updated_at'] = now();
            }

            $expenseId = DB::table('expenses_bill')->insertGetId($insertData);

            if (Schema::hasTable('expenses_bill_items')) {
                $itemData = [
                    'expenses_bill_id' => $expenseId,
                    'acc_head_id' => $headId,
                    'date' => $date,
                ];
                if (Schema::hasColumn('expenses_bill_items', 'brc_id')) {
                    $itemData['brc_id'] = $brcId;
                }
                if (Schema::hasColumn('expenses_bill_items', 'invoice_no')) {
                    $itemData['invoice_no'] = (string) $billNo;
                }
                if (Schema::hasColumn('expenses_bill_items', 'note')) {
                    $itemData['note'] = $description;
                }
                if (Schema::hasColumn('expenses_bill_items', 'debit_amount')) {
                    $itemData['debit_amount'] = $amount;
                }
                if (Schema::hasColumn('expenses_bill_items', 'credit_amount')) {
                    $itemData['credit_amount'] = 0;
                }
                if (Schema::hasColumn('expenses_bill_items', 'paid_to')) {
                    $itemData['paid_to'] = $paidTo;
                }
                if (Schema::hasColumn('expenses_bill_items', 'par_acc_head_id')) {
                    $itemData['par_acc_head_id'] = $paymentMode === 'bank' ? 2 : 1;
                }
                if (Schema::hasColumn('expenses_bill_items', 'voucher_type_id')) {
                    $itemData['voucher_type_id'] = 1;
                }
                if (Schema::hasColumn('expenses_bill_items', 'profit_acc_head_id')) {
                    $itemData['profit_acc_head_id'] = 108;
                }
                if (Schema::hasColumn('expenses_bill_items', 'created_by')) {
                    $itemData['created_by'] = $userId;
                }
                if (Schema::hasColumn('expenses_bill_items', 'created_at')) {
                    $itemData['created_at'] = now();
                }
                if (Schema::hasColumn('expenses_bill_items', 'updated_at')) {
                    $itemData['updated_at'] = now();
                }

                DB::table('expenses_bill_items')->insert($itemData);
            }

            DB::commit();

            session()->flash('toast_message', 'Expense Bill #' . $billNo . ' added successfully!');
            session()->flash('toast_type', 'success');

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Expense Bill #' . $billNo . ' added successfully!',
                    'bill_no' => $billNo,
                    'id' => $expenseId,
                ]);
            }

            return redirect()->route('admin.account.expenses.index', ['brc_id' => $brcId, 'period' => 'all'])
                ->with('success', 'Expense Bill #' . $billNo . ' added successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Error saving expense bill: ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Error saving expense bill: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show Bill Voucher Details for view/edit/print.
     */
    public function show(int $id): JsonResponse
    {
        $bill = DB::table('expenses_bill')
            ->leftJoin('expenses_bill_items', 'expenses_bill_items.expenses_bill_id', '=', 'expenses_bill.id')
            ->leftJoin('accountshead', 'accountshead.id', '=', 'expenses_bill_items.acc_head_id')
            ->leftJoin('branch', 'branch.id', '=', 'expenses_bill.brc_id')
            ->where('expenses_bill.id', $id)
            ->select([
                'expenses_bill.*',
                'expenses_bill_items.acc_head_id',
                'accountshead.name as head_name',
                'branch.name as branch_name'
            ])
            ->first();

        if (!$bill) {
            return response()->json(['status' => 'fail', 'message' => 'Bill not found'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $bill,
        ]);
    }

    /**
     * Update an existing Expense Bill.
     */
    public function update(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $bill = DB::table('expenses_bill')->where('id', $id)->first();
        if (!$bill) {
            return response()->json(['status' => 'fail', 'message' => 'Record not found'], 404);
        }

        $date = Carbon::parse(str_replace('/', '-', $request->input('date', $bill->date)))->format('Y-m-d');
        $amount = (float) $request->input('amount', $bill->grand_total);
        $paidTo = $request->input('paid_to', $bill->paid_to ?? '');
        $description = $request->input('note', $request->input('description', $bill->note ?? ''));
        $headId = (int) $request->input('acc_head_id', 0);
        $paymentMode = $request->input('payment_mode', 'cash');

        DB::beginTransaction();
        try {
            $updateData = [
                'date' => $date,
                'grand_total' => $amount,
            ];
            if (Schema::hasColumn('expenses_bill', 'paid_to')) {
                $updateData['paid_to'] = $paidTo;
            }
            if (Schema::hasColumn('expenses_bill', 'note')) {
                $updateData['note'] = $description;
            }
            if (Schema::hasColumn('expenses_bill', 'payment_mode')) {
                $updateData['payment_mode'] = $paymentMode;
            }
            if (Schema::hasColumn('expenses_bill', 'par_acc_head_id')) {
                $updateData['par_acc_head_id'] = $paymentMode === 'bank' ? 2 : 1;
            }
            if (Schema::hasColumn('expenses_bill', 'updated_at')) {
                $updateData['updated_at'] = now();
            }

            DB::table('expenses_bill')->where('id', $id)->update($updateData);

            if ($headId > 0 && Schema::hasTable('expenses_bill_items')) {
                $itemUpdateData = [
                    'acc_head_id' => $headId,
                    'date' => $date,
                ];
                if (Schema::hasColumn('expenses_bill_items', 'note')) {
                    $itemUpdateData['note'] = $description;
                }
                if (Schema::hasColumn('expenses_bill_items', 'debit_amount')) {
                    $itemUpdateData['debit_amount'] = $amount;
                }
                if (Schema::hasColumn('expenses_bill_items', 'credit_amount')) {
                    $itemUpdateData['credit_amount'] = 0;
                }
                if (Schema::hasColumn('expenses_bill_items', 'paid_to')) {
                    $itemUpdateData['paid_to'] = $paidTo;
                }
                if (Schema::hasColumn('expenses_bill_items', 'par_acc_head_id')) {
                    $itemUpdateData['par_acc_head_id'] = $paymentMode === 'bank' ? 2 : 1;
                }
                if (Schema::hasColumn('expenses_bill_items', 'updated_at')) {
                    $itemUpdateData['updated_at'] = now();
                }

                DB::table('expenses_bill_items')->where('expenses_bill_id', $id)->update($itemUpdateData);
            }

            DB::commit();

            session()->flash('toast_message', 'Expense Bill updated successfully!');
            session()->flash('toast_type', 'success');

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Expense Bill updated successfully!',
                ]);
            }

            return redirect()->route('admin.account.expenses.index', ['period' => 'all'])->with('success', 'Expense Bill updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete an Expense Bill.
     */
    public function destroy(int $id): JsonResponse|RedirectResponse
    {
        try {
            DB::table('expenses_bill_items')->where('expenses_bill_id', $id)->delete();
            DB::table('expenses_bill')->where('id', $id)->delete();

            session()->flash('toast_message', 'Expense Bill deleted successfully!');
            session()->flash('toast_type', 'success');

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Expense Bill deleted successfully!',
                ]);
            }

            return redirect()->route('admin.account.expenses.index', ['period' => 'all'])->with('success', 'Expense Bill deleted successfully!');
        } catch (\Exception $e) {
            return response()->json(['status' => 'fail', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Print / Download Expense Bill (2-Up format: Office Copy & Payee Copy).
     */
    public function printBill(Request $request, int $id)
    {
        $bill = DB::table('expenses_bill')
            ->leftJoin('branch', 'branch.id', '=', 'expenses_bill.brc_id')
            ->leftJoin('expenses_bill_items', 'expenses_bill_items.expenses_bill_id', '=', 'expenses_bill.id')
            ->leftJoin('accountshead', 'accountshead.id', '=', 'expenses_bill_items.acc_head_id')
            ->where('expenses_bill.id', $id)
            ->select([
                'expenses_bill.*',
                'branch.name as branch_name',
                'accountshead.name as head_name',
            ])
            ->first();

        if (!$bill) {
            abort(404, 'Expense Bill not found');
        }

        $items = DB::table('expenses_bill_items')
            ->leftJoin('accountshead', 'accountshead.id', '=', 'expenses_bill_items.acc_head_id')
            ->where('expenses_bill_items.expenses_bill_id', $id)
            ->select([
                'expenses_bill_items.*',
                'accountshead.name as head_name',
            ])
            ->get();

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

        $viewContent = view('admin.account.expenses.print', [
            'bill' => $bill,
            'items' => $items,
            'schSetting' => $schSetting,
            'logoBase64' => $logoBase64,
        ])->render();

        $docNo = $bill->invoice_no ?: $bill->id;
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

