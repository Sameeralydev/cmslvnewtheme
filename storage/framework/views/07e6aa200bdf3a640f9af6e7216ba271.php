<?php $__env->startSection('title', 'Chart of Accounts'); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('admin.account.coa._styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="legacy-coa">
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-legacy-tab><i class="fa fa-list"></i> List View</a></li>
                            <li><a href="#tab_2" data-legacy-tab><i class="fa fa-file-invoice"></i> Details View</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active table-responsive no-padding" id="tab_1">
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
                                <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Account Head</th>
                                            <th>Account Type</th>
                                            <th>Account Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $chartRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr>
                                                <td><?php echo e($row->account_head); ?></td>
                                                <td><?php echo e($row->account_type); ?></td>
                                                <td style="text-align:left !important;"><?php echo e($row->account_code); ?>. <?php echo e($row->account_name); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr>
                                                <td colspan="3">No chart of accounts records found, or the legacy tables are not available in this environment.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="tab-pane" id="tab_2">
                                <div class="panel-group" id="accordion1">
                                    <?php $__empty_1 = true; $__currentLoopData = $hierarchy; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $headIndex => $head): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a href="#collapse<?php echo e($headIndex); ?>" data-panel-toggle><?php echo e($head->code); ?>. <?php echo e($head->name); ?></a>
                                                </h4>
                                            </div>
                                            <div id="collapse<?php echo e($headIndex); ?>" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <div class="panel-group">
                                                        <?php $__currentLoopData = $head->newaccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $typeIndex => $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <div class="panel">
                                                                <a href="#collapse<?php echo e($headIndex); ?><?php echo e($typeIndex); ?>" data-panel-toggle><?php echo e($type->code); ?>. <?php echo e($type->name); ?> &raquo;</a>
                                                                <div id="collapse<?php echo e($headIndex); ?><?php echo e($typeIndex); ?>" class="panel-collapse collapse">
                                                                    <div class="panel-body">
                                                                        <div class="panel-group">
                                                                            <?php $__currentLoopData = $type->accountshead; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                <?php echo e($account->code); ?>. <?php echo e($account->name); ?><br>
                                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <div class="panel panel-default">
                                            <div class="panel-body">No chart of accounts detail records found.</div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[data-legacy-tab]').forEach(function (tabLink) {
                tabLink.addEventListener('click', function (event) {
                    event.preventDefault();
                    document.querySelectorAll('.legacy-coa .nav-tabs li').forEach(function (tab) {
                        tab.classList.remove('active');
                    });
                    document.querySelectorAll('.legacy-coa .tab-pane').forEach(function (pane) {
                        pane.classList.remove('active');
                    });
                    tabLink.parentElement.classList.add('active');
                    document.querySelector(tabLink.getAttribute('href')).classList.add('active');
                });
            });

            document.querySelectorAll('[data-panel-toggle]').forEach(function (toggle) {
                toggle.addEventListener('click', function (event) {
                    event.preventDefault();
                    var panel = document.querySelector(toggle.getAttribute('href'));

                    if (panel) {
                        panel.classList.toggle('in');
                    }
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\TNT\cmslv_git_clone2\resources\views\admin\account\coa\chart-of-accounts.blade.php ENDPATH**/ ?>