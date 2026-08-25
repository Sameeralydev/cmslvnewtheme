<?php $__env->startSection('title', $title); ?>

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

                        <form action="<?php echo e($account ? route('admin.account.accounts.newaccounts.update', $account->id, false) : route('admin.account.accounts.newaccounts.store', absolute: false)); ?>" method="post" accept-charset="utf-8">
                            <?php echo csrf_field(); ?>
                            <div class="box-body">
                                <?php if(session('success')): ?>
                                    <div class="alert alert-success text-left"><?php echo e(session('success')); ?></div>
                                <?php endif; ?>

                                <?php if($account): ?>
                                    <input type="hidden" name="id" value="<?php echo e($account->id); ?>">
                                <?php endif; ?>

                                <div class="form-group">
                                    <label>Accounts Head</label><small class="req"> *</small>
                                    <select id="accounts_type_id" name="accounts_type_id" class="form-control selectval">
                                        <option value="">Select</option>
                                        <?php $__currentLoopData = $accountTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $accountType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($accountType->id); ?>" <?php if((string) old('accounts_type_id', $account->accounts_type_id ?? '') === (string) $accountType->id): echo 'selected'; endif; ?>><?php echo e($accountType->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['accounts_type_id'];
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
                                    <label>Account Type Name</label> <small class="req"> *</small>
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

                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"><?php echo e(old('description', $account->note ?? '')); ?></textarea>
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
                            <h3 class="box-title titlefix">Accounts Type List</h3>
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
                                        <th>Account Type</th>
                                        <th>Account Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $hierarchy; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td class="mailbox-name"><?php echo e($type->code); ?>. <?php echo e($type->name); ?></td>
                                            <td class="mailbox-name"></td>
                                            <td class="mailbox-name"></td>
                                        </tr>
                                        <?php $__currentLoopData = $type->newaccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $newAccount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td class="mailbox-name"></td>
                                                <td class="mailbox-name"><?php echo e($newAccount->code); ?>. <?php echo e($newAccount->name); ?></td>
                                                <td class="mailbox-date text-right">
                                                    <?php if (! ((bool) ($newAccount->is_system ?? false))): ?>
                                                        <a href="<?php echo e(route('admin.account.accounts.newaccounts.edit', $newAccount->id, false)); ?>" class="btn btn-primary btn-xs" title="Edit">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="3">No account type records found, or the legacy tables are not available in this environment.</td>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\TNT\cmslv_git_clone2\resources\views\admin\account\coa\newaccounts.blade.php ENDPATH**/ ?>