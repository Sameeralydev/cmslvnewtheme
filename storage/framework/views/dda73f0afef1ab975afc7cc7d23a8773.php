<?php $__env->startSection('title', $title); ?>

<?php
    $selectedHeadId = old('accounts_head_id', $account->accounts_head_id ?? '');
    $selectedTypeId = old('account_type_id', $account->new_accounts_id ?? '');
    $openingAmount = old('opening_balance_amount', $openingBalance->debit_amount ?? $openingBalance->credit_amount ?? '');
?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('admin.account.coa._styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="legacy-coa">
        <section class="content">
            <div class="row">
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo e($title); ?></h3>
                        </div>

                        <form action="<?php echo e($account ? route('admin.account.accounts.accountshead.update', ['account' => $account->id, 'branch' => $branchId], false) : route('admin.account.accounts.accountshead.store', ['branch' => $branchId], false)); ?>" method="post" accept-charset="utf-8">
                            <?php echo csrf_field(); ?>
                            <div class="box-body">
                                <?php if(session('success')): ?>
                                    <div class="alert alert-success text-left"><?php echo e(session('success')); ?></div>
                                <?php endif; ?>

                                <?php if($account): ?>
                                    <input type="hidden" name="id" value="<?php echo e($account->id); ?>">
                                <?php endif; ?>

                                <?php if($branches !== []): ?>
                                    <div class="form-group">
                                        <label>Branch</label><small class="req"> *</small>
                                        <select id="brc_id" name="brc_id" class="form-control selectval brc_id" onchange="getBranchByID(this.value);">
                                            <option value="">Select</option>
                                            <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($branch->id); ?>" <?php if((int) $branchId === (int) $branch->id): echo 'selected'; endif; ?>><?php echo e($branch->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                <?php endif; ?>

                                <div class="form-group">
                                    <label>Account Head</label><small class="req"> *</small>
                                    <select id="accounts_head_id" name="accounts_head_id" class="form-control">
                                        <option value="">Select</option>
                                        <?php $__currentLoopData = $accountTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $accountType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($accountType->id); ?>" <?php if((string) $selectedHeadId === (string) $accountType->id): echo 'selected'; endif; ?>><?php echo e($accountType->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['accounts_head_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="form-group">
                                    <label>Account Type</label><small class="req"> *</small>
                                    <select id="account_type_id" name="account_type_id" class="form-control selectval">
                                        <option value="">Select</option>
                                        <?php $__currentLoopData = $newAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $newAccount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($newAccount->id); ?>" <?php if((string) $selectedTypeId === (string) $newAccount->id): echo 'selected'; endif; ?>><?php echo e($newAccount->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['account_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div id="ooa" style="display:none;">
                                    <div class="form-group">
                                        <label>Account Name</label> <small class="req"> *</small>
                                        <input autofocus id="name" name="name" type="text" class="form-control" value="<?php echo e(old('name', $account->name ?? '')); ?>">
                                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="text-danger"><?php echo e($message); ?></span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <div id="ob" style="display:none;">
                                        <div class="form-group">
                                            <label>Staff</label>
                                            <select id="staff_id" name="staff_id" class="form-control">
                                                <option value="">Select</option>
                                                <?php $__currentLoopData = $staffList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $staff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($staff->staff_id ?? $staff->id); ?>" <?php if((string) old('staff_id', $account->staff_id ?? '') === (string) ($staff->staff_id ?? $staff->id)): echo 'selected'; endif; ?>>
                                                        <?php echo e($staff->employee_id); ?> - <?php echo e(trim(($staff->name ?? '').' '.($staff->surname ?? ''))); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Opening Balance Date</label>
                                            <input id="date" name="date" type="date" class="form-control date" value="<?php echo e(old('date', isset($openingBalance->date) ? \Illuminate\Support\Carbon::parse($openingBalance->date)->toDateString() : now()->toDateString())); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Opening Balance Amount</label>
                                            <input id="opening_balance_amount" name="opening_balance_amount" type="text" class="form-control" value="<?php echo e($openingAmount); ?>" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="3"><?php echo e(old('description', $account->note ?? '')); ?></textarea>
                                    </div>
                                </div>

                                <div id="ooamsg" style="display:none;">
                                    <div class="alert alert-danger text-left trevd" style="display:none;">Please add "trade receivable" in the "Student Admission" menu from "Admission Process" tab.</div>
                                    <div class="alert alert-danger text-left trpayabl" style="display:none;">Please add "trade Payable" in the "Supplier" menu from "Inventory Process" tab.</div>
                                    <div class="alert alert-danger text-left invt" style="display:none;">Please add "Inventories" in the "Product/Service" menu from "Inventory Process" tab.</div>
                                    <div class="alert alert-danger text-left salaies" style="display:none;">Please add " Staff Directory" in the "Employees" menu from "Staff Recruitment" tab.</div>
                                    <div class="alert alert-danger text-left sales" style="display:none;">"Sales" accounts cannot be created here. They are automatically generated when adding new products / services.</div>
                                    <div class="alert alert-danger text-left salesreturn" style="display:none;">"Sales Return" accounts cannot be created here. They are automatically generated when adding new products / services</div>
                                    <div class="alert alert-danger text-left purchases" style="display:none;">"Purchases" accounts cannot be created here. They are automatically generated when adding new products / services</div>
                                    <div class="alert alert-danger text-left purchasesreturn" style="display:none;">"Purchases Return" accounts cannot be created here. They are automatically generated when adding new products / services</div>
                                    <div class="alert alert-danger text-left costofsales" style="display:none;">"Cost of Sales" accounts cannot be created here. They are automatically generated when adding new products / services</div>
                                </div>
                            </div>

                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary pull-right">Save</button>
                                <div style="clear:both;"></div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="box box-primary">
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix">Accounts Head List</h3>
                        </div>
                        <div class="box-body">
                            <div class="legacy-datatable-toolbar">
                                <input type="search" placeholder="Search...">
                                <div class="legacy-datatable-icons">
                                    <span><i class="fa fa-copy"></i></span>
                                    <span><i class="fa fa-file-csv"></i></span>
                                    <span><i class="fa fa-file-text"></i></span>
                                    <span><i class="fa fa-file-pdf"></i></span>
                                    <span><i class="fa fa-print"></i></span>
                                    <span><i class="fa fa-table-list"></i></span>
                                </div>
                            </div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th>Account Head</th>
                                        <th>Account Type</th>
                                        <th>Account Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $hierarchy; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $head): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($head->code); ?>. <?php echo e($head->name); ?></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <?php $__currentLoopData = $head->newaccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td></td>
                                                <td><?php echo e($type->code); ?>. <?php echo e($type->name); ?></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <?php $__currentLoopData = $type->accountshead; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $accountHead): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td><?php echo e($accountHead->code); ?>. <?php echo e($accountHead->name); ?></td>
                                                    <td class="mailbox-date text-right">
                                                        <?php if (! ((bool) ($accountHead->is_system ?? false))): ?>
                                                            <button onclick="changestatuspost('<?php echo e($accountHead->id); ?>')" type="button" class="btn <?php echo e((int) ($accountHead->is_posted ?? 0) === 1 ? 'btn-success' : 'btn-danger'); ?> btn-xs" title="<?php echo e((int) ($accountHead->is_posted ?? 0) === 1 ? 'Is Posted' : 'Is Post'); ?>"><i class="fa fa-plus"></i></button>
                                                            <a href="<?php echo e(route('admin.account.accounts.accountshead.edit', ['account' => $accountHead->id, 'branch' => $accountHead->brc_id ?: $branchId], false)); ?>" class="btn btn-primary btn-xs" title="Edit">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                            <button onclick="changestatus('<?php echo e($accountHead->id); ?>')" type="button" class="btn <?php echo e(($accountHead->is_active ?? 'yes') === 'yes' ? 'btn-success' : 'btn-danger'); ?> btn-xs" title="<?php echo e(($accountHead->is_active ?? 'yes') === 'yes' ? 'Active' : 'In Active'); ?>"><i class="fa <?php echo e(($accountHead->is_active ?? 'yes') === 'yes' ? 'fa-check' : 'fa-remove'); ?>"></i></button>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="4">No accounts head records found, or the legacy tables are not available in this environment.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        function getBranchByID(val) {
            if (val) {
                window.location.href = '<?php echo e(url('/admin/account/accounts/accountshead')); ?>/' + val;
            }
        }

        function setAccountTypeVisibility(value) {
            var blocked = {
                3: 'trevd',
                23: 'invt',
                13: 'trpayabl',
                33: 'sales',
                34: 'salesreturn',
                35: 'purchases',
                36: 'purchasesreturn',
                37: 'costofsales'
            };
            document.querySelectorAll('#ooamsg .alert').forEach(function (element) {
                element.style.display = 'none';
            });

            if (blocked[value]) {
                document.getElementById('ooa').style.display = 'none';
                document.getElementById('ooamsg').style.display = 'block';
                document.querySelector('.' + blocked[value]).style.display = 'block';

                return;
            }

            document.getElementById('ooamsg').style.display = 'none';
            document.getElementById('ooa').style.display = 'block';
        }

        function setOpeningBalanceVisibility(headId) {
            document.getElementById('ob').style.display = ['1', '2', '3'].includes(String(headId)) ? 'block' : 'none';
        }

        function loadAccountTypes(headId, selectedTypeId) {
            var target = document.getElementById('account_type_id');
            target.innerHTML = '<option value="">Select</option>';

            if (!headId) {
                setAccountTypeVisibility('');
                setOpeningBalanceVisibility('');

                return;
            }

            fetch('<?php echo e(route('admin.account.accounts.newaccounts.by-head', absolute: false)); ?>?accounts_head_id=' + encodeURIComponent(headId), {
                headers: {'X-Requested-With': 'XMLHttpRequest'}
            })
                .then(function (response) { return response.json(); })
                .then(function (items) {
                    items.forEach(function (item) {
                        var option = document.createElement('option');
                        option.value = item.id;
                        option.textContent = item.name;

                        if (String(selectedTypeId || '') === String(item.id)) {
                            option.selected = true;
                        }

                        target.appendChild(option);
                    });

                    setAccountTypeVisibility(target.value);
                    setOpeningBalanceVisibility(headId);
                });
        }

        function postStatus(url, id) {
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({id: id})
            })
                .then(function (response) { return response.json(); })
                .then(function (data) {
                    if (data.status === 'success') {
                        window.location.reload();
                    } else {
                        alert((data.error || ['Unable to update record.']).join(' '));
                    }
                });
        }

        function changestatus(id) {
            postStatus('<?php echo e(route('admin.account.accounts.change-status', absolute: false)); ?>', id);
        }

        function changestatuspost(id) {
            postStatus('<?php echo e(route('admin.account.accounts.change-status-post', absolute: false)); ?>', id);
        }

        document.addEventListener('DOMContentLoaded', function () {
            var headSelect = document.getElementById('accounts_head_id');
            var typeSelect = document.getElementById('account_type_id');

            headSelect.addEventListener('change', function () {
                loadAccountTypes(this.value, '');
            });
            typeSelect.addEventListener('change', function () {
                setAccountTypeVisibility(this.value);
            });
            loadAccountTypes(headSelect.value, '<?php echo e($selectedTypeId); ?>');
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\TNT\cmslv_git_clone2\resources\views\admin\account\coa\accountshead.blade.php ENDPATH**/ ?>