<?php $__env->startSection('title', 'Staff Disable Directory'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .disable-directory-page { color:#173052 !important; font:12px Arial,Helvetica,sans-serif !important; font-weight:400 !important; }
    .disable-directory-card { overflow:hidden; margin-bottom:14px; border:1px solid #ccd5e3; border-radius:0 0 8px 8px; background:#fff; box-shadow:0 1px 2px rgba(0,0,0,.08); }
    .disable-directory-title { padding:8px 13px; border-bottom:1px solid #ccd5e3; font-size:19px !important; font-weight:400 !important; line-height:25px; }
    .disable-directory-form { padding:12px 18px 11px; }
    .disable-filter-grid { display:grid; grid-template-columns:minmax(190px,1.2fr) minmax(190px,1.2fr) minmax(120px,.65fr) minmax(330px,2fr); gap:12px; align-items:end; }
    .disable-field { display:block !important; min-width:0; font-size:13px !important; font-weight:400 !important; }
    .disable-field span { font-weight:400 !important; }
    .disable-field select,.disable-field input { box-sizing:border-box; display:block; width:100%; height:34px; margin-top:5px; padding:5px 9px; border:1px solid #cfd6e0; border-radius:0; background:#fff; color:#173052; font:13px Arial,Helvetica,sans-serif !important; font-weight:400 !important; }
    .disable-search { display:flex; height:34px; margin-top:5px; }
    .disable-search select { width:105px; flex:0 0 105px; margin:0; border-right:0; background:#f7f7f7; }
    .disable-search input { flex:1; min-width:0; margin:0; }
    .disable-search button,.disable-submit { height:34px; border:1px solid #222; border-radius:4px; background:#62666b; color:#fff; font-size:11px !important; font-weight:400 !important; white-space:nowrap; }
    .disable-search button { padding:0 12px; }
    .disable-submit-wrap { margin-top:13px; text-align:center; }
    .disable-submit { padding:0 14px; }
    .disable-table-wrap { overflow-x:auto; padding:13px 18px 10px; }
    .disable-table { width:100%; border-collapse:collapse; table-layout:fixed; font-size:12px; }
    .disable-table th { height:32px; padding:6px 9px; border:1px solid #fff; background:#626262; color:#fff; text-align:left; font-size:11px !important; font-weight:500 !important; white-space:nowrap; }
    .disable-table td { height:32px; padding:7px 9px; border:1px solid #ccd5e3; background:#fff; color:#173052; font-size:11px !important; font-weight:400 !important; vertical-align:middle; }
    .disable-table th:nth-child(1),.disable-table td:nth-child(1) { width:9%; }
    .disable-table th:nth-child(2),.disable-table td:nth-child(2) { width:9%; }
    .disable-table th:nth-child(3),.disable-table td:nth-child(3) { width:18%; }
    .disable-table th:nth-child(4),.disable-table td:nth-child(4) { width:15%; }
    .disable-table th:nth-child(5),.disable-table td:nth-child(5) { width:15%; }
    .disable-table th:nth-child(6),.disable-table td:nth-child(6) { width:15%; }
    .disable-table th:nth-child(7),.disable-table td:nth-child(7) { width:11%; }
    .disable-table th:nth-child(8),.disable-table td:nth-child(8) { width:105px; }
    .disable-action { display:flex; gap:4px; }
    .disable-action a,.disable-action button { display:inline-flex; width:28px; height:28px; align-items:center; justify-content:center; border:1px solid #62666b; border-radius:4px; background:#fff; color:#62666b; cursor:pointer; }
    .disable-directory-page .pagination { margin:8px 0 0; }
    @media (max-width:1050px) { .disable-filter-grid { grid-template-columns:1fr 1fr; } .disable-search-field { grid-column:1 / -1; } }
    @media (max-width:640px) { .disable-filter-grid { grid-template-columns:1fr; } .disable-search-field { grid-column:auto; } }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="disable-directory-page">
    <section class="disable-directory-card">
        <div class="disable-directory-title">Select Criteria</div>
        <form method="GET" action="<?php echo e(route('admin.hrms.staff-disable-directory.index', absolute: false)); ?>" class="disable-directory-form">
            <div class="disable-filter-grid">
                <label class="disable-field">Branch <span class="text-red-500">*</span>
                    <select name="brc_id" onchange="this.form.submit()" class="mt-1 h-9 w-full border border-[#cfd6e0] bg-white px-2">
                        <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($branch->id); ?>" <?php if((int)$selectedBranchId === (int)$branch->id): echo 'selected'; endif; ?>><?php echo e($branch->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </label>
                <label class="disable-field">Role
                    <select name="role" class="mt-1 h-9 w-full border border-[#cfd6e0] bg-white px-2">
                        <option value="">Select</option>
                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($role['id']); ?>" <?php if((int)$selectedRoleId === (int)$role['id']): echo 'selected'; endif; ?>><?php echo e($role['name']); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </label>
                <label class="disable-field">Branch <span class="text-red-500">*</span>
                    <select name="brc_id_display" disabled class="mt-1 h-9 w-full border border-[#cfd6e0] bg-white px-2"><option><?php echo e($branches->firstWhere('id', $selectedBranchId)?->name ?? 'Main'); ?></option></select>
                </label>
                <label class="disable-field disable-search-field">Search By Keyword <span class="text-red-500">*</span>
                    <div class="disable-search">
                        <select name="selected_value_staff_dis">
                            <option value="staff_id" <?php if($selectedSearchField === 'staff_id'): echo 'selected'; endif; ?>>Staff ID</option><option value="name" <?php if($selectedSearchField === 'name'): echo 'selected'; endif; ?>>Name</option><option value="role" <?php if($selectedSearchField === 'role'): echo 'selected'; endif; ?>>Role</option>
                        </select>
                        <input name="text_staff_dis" value="<?php echo e($searchText); ?>" placeholder="Search By Staff ID, Name, Role etc...">
                        <button class="bg-[#62666b] px-3 font-semibold text-white"><i class="fa fa-search"></i> Search</button>
                    </div>
                </label>
            </div>
            <div class="disable-submit-wrap"><button class="disable-submit"><i class="fa fa-search"></i> Search</button></div>
        </form>
    </section>

    <?php if(session('success')): ?><div class="mb-3 border border-green-200 bg-green-50 px-3 py-2 text-green-700"><?php echo e(session('success')); ?></div><?php endif; ?>
    <section class="disable-directory-card">
        <div class="disable-directory-title"><?php echo e($searchText !== '' ? 'Search Details: '.$searchText : 'Disable Staff List'); ?></div>
        <div class="disable-table-wrap"><table class="disable-table"><thead><tr><th>Campus</th><th>Staff ID</th><th>Name</th><th>Role</th><th>Department</th><th>Designation</th><th>Mobile No.</th><th>Action</th></tr></thead><tbody>
            <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $staff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><tr><td><?php echo e($staff->branch_name ?: '-'); ?></td><td><?php echo e($staff->employee_id ?: '-'); ?></td><td><a style="color:#173d83;text-decoration:underline" href="<?php echo e(route('admin.hrms.staff.profile', $staff->id, absolute: false)); ?>"><?php echo e(trim($staff->name.' '.($staff->surname ?? ''))); ?></a></td><td><?php echo e($staff->role_name ?: '-'); ?></td><td><?php echo e($staff->department_name ?: '-'); ?></td><td><?php echo e($staff->designation_name ?: '-'); ?></td><td><?php echo e($staff->contact_no ?: '-'); ?></td><td><div class="disable-action"><a title="View" href="<?php echo e(route('admin.hrms.staff.profile', $staff->id, absolute: false)); ?>"><i class="fa fa-eye"></i></a><form method="POST" action="<?php echo e(route('admin.hrms.staff-disable-directory.enable', $staff->id, absolute: false)); ?>" onsubmit="return confirm('Enable this staff member?')"><?php echo csrf_field(); ?><button title="Enable"><i class="fa fa-check"></i></button></form></div></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="8" style="text-align:center">No record found</td></tr><?php endif; ?>
        </tbody></table><div><?php echo e($records->links()); ?></div></div>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\TNT\cmslv_git_clone2\resources\views/admin/hrms/staff-disable-directory/index.blade.php ENDPATH**/ ?>