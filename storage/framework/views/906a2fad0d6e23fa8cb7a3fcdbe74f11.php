<?php $__env->startSection('title', 'Edit Staff'); ?>

<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/bootstrap/css/bootstrap.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/dist/css/style-main.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/dist/themes/default/ss-main.css')); ?>">
    <style>
        .legacy-staff-edit .box {
            border: 1px solid #d7dfe3;
            border-top: 0;
            box-shadow: none;
        }

        .legacy-staff-edit .box-header {
            border-bottom: 1px solid #e3e7eb;
        }

        .legacy-staff-edit .pagetitleh2 {
            margin: 0;
            padding: 10px 14px;
            border-bottom: 1px solid #e3e7eb;
            background: #f5f5f5;
            font-size: 14px;
            color: #111;
        }

        .legacy-staff-edit .around10 {
            padding: 14px;
        }

        .legacy-staff-edit label,
        .legacy-staff-edit td,
        .legacy-staff-edit th {
            font-size: 12px;
            color: #333;
        }

        .legacy-staff-edit .form-control {
            height: 34px;
            font-size: 12px;
            border-radius: 0;
        }

        .legacy-staff-edit textarea.form-control {
            height: auto;
        }

        .legacy-staff-edit .table > tbody > tr > td,
        .legacy-staff-edit .table > thead > tr > td,
        .legacy-staff-edit .table > tbody > tr > th,
        .legacy-staff-edit .table > thead > tr > th {
            vertical-align: middle;
        }

        .legacy-staff-edit .btn-xs {
            padding: 2px 7px;
            font-size: 11px;
        }

        .legacy-staff-edit .req {
            color: #f00;
        }

        .legacy-staff-edit .text-danger {
            display: block;
            margin-top: 4px;
            font-size: 11px;
        }

        .legacy-staff-edit .label-tools {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .legacy-staff-edit .option-add-btn {
            border: 1px solid #264796;
            background: #264796;
            color: #fff;
            font-size: 11px;
            line-height: 1;
            padding: 2px 6px;
            border-radius: 2px;
        }

        .legacy-staff-edit .net-salary,
        .legacy-staff-edit .total-amount,
        .legacy-staff-edit .total-dedamount {
            font-weight: 700;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="legacy-staff-edit">
        <script src="<?php echo e(asset('assets/dist/js/webcam.min.js')); ?>"></script>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> Edit Staff</h3>
                        <div class="box-tools pull-right">
                            <a class="btn btn-sm btn-primary" href="<?php echo e(route('admin.hrms.staff.import', ['brc_id' => $selectedBranchId], false)); ?>"><i class="fa fa-plus"></i> Import Staff</a>
                        </div>
                    </div>

                    <form id="form1" action="<?php echo e(route('admin.hrms.staff.update', $staff->id, false)); ?>" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="box-body">
                            <div class="tshadow mb25 bozero">
                                <h4 class="pagetitleh2">Basic Information</h4>
                                <div class="around10">
                                    <?php if(session('success')): ?>
                                        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                                    <?php endif; ?>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Branch</label><small class="req"> *</small>
                                                <select id="brc_id" name="brc_id" class="form-control selectval brc_id">
                                                    <option value="">Select</option>
                                                    <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($branch->id); ?>" <?php if((int) old('brc_id', $staff->brc_id) === (int) $branch->id): echo 'selected'; endif; ?>><?php echo e($branch->name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                <span class="text-danger"><?php echo e($errors->first('brc_id')); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Staff ID</label><small class="req"> *</small>
                                                <input autofocus id="employee_id" name="employee_id" type="text" class="form-control" value="<?php echo e(old('employee_id', $staff->employee_id)); ?>" readonly>
                                                <span class="text-danger"><?php echo e($errors->first('employee_id')); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Category</label><small class="req"> *</small>
                                                <select id="category" name="category" class="form-control selectval">
                                                    <option value="">Select</option>
                                                    <option value="1" <?php if((string) old('category', $staff->category) === '1'): echo 'selected'; endif; ?>>Administration</option>
                                                    <option value="2" <?php if((string) old('category', $staff->category) === '2'): echo 'selected'; endif; ?>>Teaching</option>
                                                    <option value="3" <?php if((string) old('category', $staff->category) === '3'): echo 'selected'; endif; ?>>Allied</option>
                                                </select>
                                                <span class="text-danger"><?php echo e($errors->first('category')); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="label-tools">
                                                    <label>Role <small class="req">*</small></label>
                                                    <button type="button" class="option-add-btn" data-option-target="role" data-option-type="role">+</button>
                                                </div>
                                                <select id="role" name="role" class="form-control selectval">
                                                    <option value="">Select</option>
                                                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($role['id']); ?>" <?php if((string) old('role', old('role_id', $staff->role_id)) === (string) $role['id']): echo 'selected'; endif; ?>><?php echo e($role['name']); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                <span class="text-danger"><?php echo e($errors->first('role_id') ?: $errors->first('role')); ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3"><div class="form-group"><div class="label-tools"><label>Designation</label><button type="button" class="option-add-btn" data-option-target="designation" data-option-type="designation">+</button></div><select id="designation" name="designation" class="form-control selectval"><option value="">Select</option><?php $__currentLoopData = $designations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $designation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($designation->id); ?>" <?php if((string) old('designation', $staff->designation) === (string) $designation->id): echo 'selected'; endif; ?>><?php echo e($designation->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select><span class="text-danger"><?php echo e($errors->first('designation')); ?></span></div></div>
                                        <div class="col-md-3"><div class="form-group"><div class="label-tools"><label>Department</label><button type="button" class="option-add-btn" data-option-target="department" data-option-type="department">+</button></div><select id="department" name="department" class="form-control selectval"><option value="">Select</option><?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($department->id); ?>" <?php if((string) old('department', $staff->department) === (string) $department->id): echo 'selected'; endif; ?>><?php echo e($department->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select><span class="text-danger"><?php echo e($errors->first('department')); ?></span></div></div>
                                        <div class="col-md-3"><div class="form-group"><label>First Name</label><small class="req"> *</small><input id="name" name="name" type="text" class="form-control" value="<?php echo e(old('name', $staff->name)); ?>"><span class="text-danger"><?php echo e($errors->first('name')); ?></span></div></div>
                                        <div class="col-md-3"><div class="form-group"><label>Last Name</label><input id="surname" name="surname" type="text" class="form-control" value="<?php echo e(old('surname', $staff->surname)); ?>"><span class="text-danger"><?php echo e($errors->first('surname')); ?></span></div></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3"><div class="form-group"><label>Father Name</label><input id="father_name" name="father_name" type="text" class="form-control" value="<?php echo e(old('father_name', $staff->father_name)); ?>"><span class="text-danger"><?php echo e($errors->first('father_name')); ?></span></div></div>
                                        <div class="col-md-3"><div class="form-group"><label>CNIC No</label><small class="req"> *</small><input id="cnic" name="cnic" type="text" class="form-control" value="<?php echo e(old('cnic', $staff->cnic)); ?>"><span class="text-danger"><?php echo e($errors->first('cnic')); ?></span></div></div>
                                        <div class="col-md-3"><div class="form-group"><label>Email</label><sup>( Username For Login )</sup><small class="req"> *</small><input id="email" name="username" type="text" class="form-control" value="<?php echo e(old('username', old('email', $staff->email))); ?>"><span class="text-danger"><?php echo e($errors->first('email') ?: $errors->first('username')); ?></span></div></div>
                                        <div class="col-md-3"><div class="form-group"><label>Gender</label><small class="req"> *</small><select class="form-control" name="gender"><option value="">Select</option><?php $__currentLoopData = $genderOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($key); ?>" <?php if(old('gender', $staff->gender) === $key): echo 'selected'; endif; ?>><?php echo e($value); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select><span class="text-danger"><?php echo e($errors->first('gender')); ?></span></div></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3"><div class="form-group"><label>Date of Birth</label><small class="req"> *</small><input id="dob" name="dob" type="date" class="form-control date" value="<?php echo e(old('dob', optional($staff->dob)->format('Y-m-d'))); ?>"><span class="text-danger"><?php echo e($errors->first('dob')); ?></span></div></div>
                                        <div class="col-md-3"><div class="form-group"><label>Date of Joining</label><input id="date_of_joining" name="date_of_joining" type="date" class="form-control date" value="<?php echo e(old('date_of_joining', optional($staff->date_of_joining)->format('Y-m-d'))); ?>"><span class="text-danger"><?php echo e($errors->first('date_of_joining')); ?></span></div></div>
                                        <div class="col-md-3"><div class="form-group"><label>Phone</label><input id="mobileno" name="contactno" type="text" class="form-control" value="<?php echo e(old('contactno', old('contact_no', $staff->contact_no))); ?>"><span class="text-danger"><?php echo e($errors->first('contact_no')); ?></span></div></div>
                                        <div class="col-md-3"><div class="form-group"><label>Emergency Contact Number</label><small class="req"> *</small><input id="emergency_no" name="emergency_no" type="text" class="form-control" value="<?php echo e(old('emergency_no', old('emergency_contact_no', $staff->emergency_contact_no))); ?>"><span class="text-danger"><?php echo e($errors->first('emergency_contact_no')); ?></span></div></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3"><div class="form-group"><label>Whatsapp No</label><small class="req"> *</small><input id="whatsapp_no" name="whatsapp_no" type="text" class="form-control" value="<?php echo e(old('whatsapp_no', $staff->whatsapp_no)); ?>"><span class="text-danger"><?php echo e($errors->first('whatsapp_no')); ?></span></div></div>
                                        <div class="col-md-3"><div class="form-group"><label>Marital Status</label><select class="form-control" name="marital_status"><option value="">Select</option><?php $__currentLoopData = $maritalStatuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($status); ?>" <?php if(old('marital_status', $staff->marital_status) === $status): echo 'selected'; endif; ?>><?php echo e($status); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select><span class="text-danger"><?php echo e($errors->first('marital_status')); ?></span></div></div>
                                        <div class="col-md-3"><div class="form-group"><label>Current Address</label><textarea name="address" class="form-control"><?php echo e(old('address', old('local_address', $staff->local_address))); ?></textarea></div></div>
                                        <div class="col-md-3"><div class="form-group"><label>Permanent Address</label><textarea name="permanent_address" class="form-control"><?php echo e(old('permanent_address', $staff->permanent_address)); ?></textarea></div></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3"><div class="form-group"><label>Photo</label><div><input class="filestyle form-control" type="file" name="file" id="file"></div><span class="text-danger"><?php echo e($errors->first('file')); ?></span></div></div>
                                        <div class="col-md-3" style="padding:0 0 0 5px;">
                                            <div class="form-group">
                                                <label class="col-md-12">Photo Webcam</label>
                                                <button type="button" style="margin: 5px 0 0 11px;" class="btn btn-primary btn-xs showcam">Access Webcam</button>
                                                <div id="my_photo_booth">
                                                    <div id="my_camera" style="display: none; margin-top: 8px;"></div>
                                                    <div id="pre_take_buttons" style="display: none;"><button type="button" class="btn btn-primary btn-xs" onclick="preview_snapshot()">Take Photo</button></div>
                                                    <div id="post_take_buttons" style="display:none"><button type="button" class="btn btn-primary btn-xs" onclick="cancel_preview()">Take Another</button><button type="button" style="margin-top: 5px;" class="btn btn-primary btn-xs" onclick="save_photo()">Save Photo</button></div>
                                                </div>
                                                <input type="hidden" name="image" class="image-tag">
                                                <div id="results" style="display:none;margin-top: 8px;"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3"><div class="form-group"><label>Status</label><select name="is_active" class="form-control"><option value="1" <?php if((int) old('is_active', $staff->is_active) === 1): echo 'selected'; endif; ?>>Active</option><option value="0" <?php if((int) old('is_active', $staff->is_active) === 0): echo 'selected'; endif; ?>>Inactive</option></select><span class="text-danger"><?php echo e($errors->first('is_active')); ?></span></div></div>
                                        <div class="col-md-3"><div class="form-group"><label>Note</label><textarea name="note" class="form-control"><?php echo e(old('note', $staff->note)); ?></textarea></div></div>
                                    </div>
                                </div>
                            </div>

                            <div class="tshadow mb25 bozero">
                                <h4 class="pagetitleh2">Academic Information</h4>
                                <div class="around10">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead><tr><td>University/Board</td><td>From</td><td>To</td><td>Degree/Certificate</td><td>Maximum Marks</td><td>Obtained Marks</td><td>Division/Grade</td><td><button id="btnAddedu" class="btn btn-primary btn-xs" type="button"><i class="fa fa-plus"></i></button></td></tr></thead>
                                        <tbody class="eduwarp">
                                            <?php $__empty_1 = true; $__currentLoopData = $academicRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <tr class="remove_field_warp">
                                                    <td><select name="eduinst[]" class="form-control selectval"><option value="">Select</option><?php $__currentLoopData = $universityBoards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $board): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($board->id); ?>" <?php if((int) $row['ints_id'] === (int) $board->id): echo 'selected'; endif; ?>><?php echo e($board->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></td>
                                                    <td><select name="edufrom[]" class="form-control selectval"><option value="">Select</option><?php $__currentLoopData = $academicYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($year->id); ?>" <?php if((int) $row['from'] === (int) $year->id): echo 'selected'; endif; ?>><?php echo e($year->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></td>
                                                    <td><select name="eduto[]" class="form-control selectval"><option value="">Select</option><?php $__currentLoopData = $academicYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($year->id); ?>" <?php if((int) $row['to'] === (int) $year->id): echo 'selected'; endif; ?>><?php echo e($year->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></td>
                                                    <td><select name="edudegree[]" class="form-control selectval"><option value="">Select</option><?php $__currentLoopData = $degreeCertificates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $degree): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($degree->id); ?>" <?php if((int) $row['degree_id'] === (int) $degree->id): echo 'selected'; endif; ?>><?php echo e($degree->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></td>
                                                    <td><input name="edumaxmark[]" type="text" class="form-control" value="<?php echo e($row['maxmarks']); ?>"></td>
                                                    <td><input name="eduobtmark[]" type="text" class="form-control" value="<?php echo e($row['obtmarks']); ?>"></td>
                                                    <td><input name="edugrd[]" type="text" class="form-control" value="<?php echo e($row['grade']); ?>"></td>
                                                    <td><button class="btn btn-danger btn-xs remove-row" type="button"><i class="fa fa-trash"></i></button></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr><td><select name="eduinst[]" class="form-control selectval"><option value="">Select</option><?php $__currentLoopData = $universityBoards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $board): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($board->id); ?>"><?php echo e($board->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></td><td><select name="edufrom[]" class="form-control selectval"><option value="">Select</option><?php $__currentLoopData = $academicYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($year->id); ?>"><?php echo e($year->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></td><td><select name="eduto[]" class="form-control selectval"><option value="">Select</option><?php $__currentLoopData = $academicYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($year->id); ?>"><?php echo e($year->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></td><td><select name="edudegree[]" class="form-control selectval"><option value="">Select</option><?php $__currentLoopData = $degreeCertificates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $degree): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($degree->id); ?>"><?php echo e($degree->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></td><td><input name="edumaxmark[]" type="text" class="form-control" value=""></td><td><input name="eduobtmark[]" type="text" class="form-control" value=""></td><td><input name="edugrd[]" type="text" class="form-control" value=""></td><td></td></tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tshadow mb25 bozero">
                                <h4 class="pagetitleh2">Professional trainings/certifications/others, (If any)</h4>
                                <div class="around10">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead><tr><td>Institute</td><td>Type of training</td><td>From</td><td>To</td><td>Obtained Marks</td><td>Division/Grade</td><td><button id="btnAddcer" class="btn btn-primary btn-xs" type="button"><i class="fa fa-plus"></i></button></td></tr></thead>
                                        <tbody class="cerwarp">
                                            <?php $__empty_1 = true; $__currentLoopData = $certificationRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <tr class="remove_field_warp">
                                                    <td><select name="cerinst[]" class="form-control selectval"><option value="">Select</option><?php $__currentLoopData = $institutes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $institute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($institute->id); ?>" <?php if((int) $row['inst_id'] === (int) $institute->id): echo 'selected'; endif; ?>><?php echo e($institute->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></td>
                                                    <td><select name="certrining[]" class="form-control selectval"><option value="">Select</option><?php $__currentLoopData = $trainings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $training): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($training->id); ?>" <?php if((int) $row['trining_id'] === (int) $training->id): echo 'selected'; endif; ?>><?php echo e($training->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></td>
                                                    <td><select name="cerfrom[]" class="form-control selectval"><option value="">Select</option><?php $__currentLoopData = $academicYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($year->id); ?>" <?php if((int) $row['from'] === (int) $year->id): echo 'selected'; endif; ?>><?php echo e($year->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></td>
                                                    <td><select name="certo[]" class="form-control selectval"><option value="">Select</option><?php $__currentLoopData = $academicYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($year->id); ?>" <?php if((int) $row['to'] === (int) $year->id): echo 'selected'; endif; ?>><?php echo e($year->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></td>
                                                    <td><input name="cerobtmark[]" type="text" class="form-control" value="<?php echo e($row['obtmarks']); ?>"></td>
                                                    <td><input name="cergrd[]" type="text" class="form-control" value="<?php echo e($row['grade']); ?>"></td>
                                                    <td><button class="btn btn-danger btn-xs remove-row" type="button"><i class="fa fa-trash"></i></button></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr><td><select name="cerinst[]" class="form-control selectval"><option value="">Select</option><?php $__currentLoopData = $institutes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $institute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($institute->id); ?>"><?php echo e($institute->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></td><td><select name="certrining[]" class="form-control selectval"><option value="">Select</option><?php $__currentLoopData = $trainings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $training): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($training->id); ?>"><?php echo e($training->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></td><td><select name="cerfrom[]" class="form-control selectval"><option value="">Select</option><?php $__currentLoopData = $academicYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($year->id); ?>"><?php echo e($year->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></td><td><select name="certo[]" class="form-control selectval"><option value="">Select</option><?php $__currentLoopData = $academicYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($year->id); ?>"><?php echo e($year->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></td><td><input name="cerobtmark[]" type="text" class="form-control" value=""></td><td><input name="cergrd[]" type="text" class="form-control" value=""></td><td></td></tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tshadow mb25 bozero">
                                <h4 class="pagetitleh2">Employment record/professional experience (In reverse chronological order please)</h4>
                                <div class="around10">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead><tr><td>Organization</td><td>Position held</td><td>Contact no</td><td>From</td><td>To</td><td>Salary</td><td>Reason of leaving</td><td><button id="btnAddexp" class="btn btn-primary btn-xs" type="button"><i class="fa fa-plus"></i></button></td></tr></thead>
                                        <tbody class="expwarp">
                                            <?php $__empty_1 = true; $__currentLoopData = $experienceRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <tr class="remove_field_warp">
                                                    <td><select name="exporg[]" class="form-control selectval"><option value="">Select</option><?php $__currentLoopData = $organizations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $organization): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($organization->id); ?>" <?php if((int) $row['org_id'] === (int) $organization->id): echo 'selected'; endif; ?>><?php echo e($organization->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></td>
                                                    <td><select name="exppost[]" class="form-control selectval"><option value="">Select</option><?php $__currentLoopData = $designations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $designation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($designation->id); ?>" <?php if((int) $row['postion_id'] === (int) $designation->id): echo 'selected'; endif; ?>><?php echo e($designation->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></td>
                                                    <td><input name="expcontact[]" type="text" class="form-control" value="<?php echo e($row['contactno']); ?>"></td>
                                                    <td><select name="expfrom[]" class="form-control selectval"><option value="">Select</option><?php $__currentLoopData = $academicYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($year->id); ?>" <?php if((int) $row['from'] === (int) $year->id): echo 'selected'; endif; ?>><?php echo e($year->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></td>
                                                    <td><select name="expto[]" class="form-control selectval"><option value="">Select</option><?php $__currentLoopData = $academicYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($year->id); ?>" <?php if((int) $row['to'] === (int) $year->id): echo 'selected'; endif; ?>><?php echo e($year->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></td>
                                                    <td><input name="expsalary[]" type="text" class="form-control" value="<?php echo e($row['salary']); ?>"></td>
                                                    <td><input name="explereason[]" type="text" class="form-control" value="<?php echo e($row['reason']); ?>"></td>
                                                    <td><button class="btn btn-danger btn-xs remove-row" type="button"><i class="fa fa-trash"></i></button></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr><td><select name="exporg[]" class="form-control selectval"><option value="">Select</option><?php $__currentLoopData = $organizations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $organization): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($organization->id); ?>"><?php echo e($organization->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></td><td><select name="exppost[]" class="form-control selectval"><option value="">Select</option><?php $__currentLoopData = $designations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $designation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($designation->id); ?>"><?php echo e($designation->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></td><td><input name="expcontact[]" type="text" class="form-control" value=""></td><td><select name="expfrom[]" class="form-control selectval"><option value="">Select</option><?php $__currentLoopData = $academicYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($year->id); ?>"><?php echo e($year->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></td><td><select name="expto[]" class="form-control selectval"><option value="">Select</option><?php $__currentLoopData = $academicYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($year->id); ?>"><?php echo e($year->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></td><td><input name="expsalary[]" type="text" class="form-control" value=""></td><td><input name="explereason[]" type="text" class="form-control" value=""></td><td></td></tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tshadow mb25 bozero">
                                <h4 class="pagetitleh2">Contract</h4>
                                <div class="around10">
                                    <div class="row">
                                        <div class="col-md-4"><div class="form-group"><label>Contract Type</label><select class="form-control" name="contract_type"><option value="">Select</option><?php $__currentLoopData = $contractTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($key); ?>" <?php if(old('contract_type', $staff->contract_type) === $key): echo 'selected'; endif; ?>><?php echo e($value); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select><span class="text-danger"><?php echo e($errors->first('contract_type')); ?></span></div></div>
                                        <div class="col-md-4"><div class="form-group"><label>Work Shift</label><select class="form-control" name="shift"><option value="">Select</option><?php $__currentLoopData = $shiftOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($key); ?>" <?php if(old('shift', $staff->shift) === $key): echo 'selected'; endif; ?>><?php echo e($value); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select><span class="text-danger"><?php echo e($errors->first('shift')); ?></span></div></div>
                                        <div class="col-md-4"><div class="form-group"><label>Work Location</label><input id="location" name="location" type="text" class="form-control" value="<?php echo e(old('location', $staff->location)); ?>"><span class="text-danger"><?php echo e($errors->first('location')); ?></span></div></div>
                                    </div>
                                </div>
                            </div>

                            <?php
                                $totalPayAmount = collect($payRows)->sum(fn ($row) => (float) ($row['amount'] ?? 0));
                                $totalDeductionAmount = collect($payDeductionRows)->sum(fn ($row) => (float) ($row['amount'] ?? 0));
                            ?>
                            <div class="tshadow mb25 bozero">
                                <h4 class="pagetitleh2">Payroll</h4>
                                <div class="around10">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="col-md-6"><div class="form-group"><label>Total Security</label><input id="total_security" name="total_security" type="text" class="form-control" value="<?php echo e(old('total_security', $staff->total_security)); ?>"><span class="text-danger"><?php echo e($errors->first('total_security')); ?></span></div></div>
                                            <div class="col-md-6"><div class="form-group"><label>Per Month Security</label><input id="per_month_security" name="per_month_security" type="text" class="form-control" value="<?php echo e(old('per_month_security', $staff->month_security)); ?>"><span class="text-danger"><?php echo e($errors->first('month_security')); ?></span></div></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h4>Salary Details</h4>
                                            <table class="table">
                                                <thead><tr><td>Salary Type</td><td>Frequency</td><td>Amount</td><td><button id="btnAddsalary" class="btn btn-primary btn-xs" type="button"><i class="fa fa-plus"></i></button></td></tr></thead>
                                                <tbody class="salarywarp">
                                                    <?php $__empty_1 = true; $__currentLoopData = $payRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                        <tr class="salary_remove_field_warp">
                                                            <td><select name="salary_type[]" class="form-control selectval"><option value="">Select</option><?php $__currentLoopData = $payTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($payType->id); ?>" <?php if((int) $row['type_id'] === (int) $payType->id): echo 'selected'; endif; ?>><?php echo e($payType->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></td>
                                                            <td><select name="frequency[]" class="form-control"><option value="">Select</option><option value="Basic Pay" <?php if(($row['frequency'] ?? '') === 'Basic Pay'): echo 'selected'; endif; ?>>Basic Pay</option><option value="Allowance" <?php if(($row['frequency'] ?? '') === 'Allowance'): echo 'selected'; endif; ?>>Allowance</option><option value="Increment" <?php if(($row['frequency'] ?? '') === 'Increment'): echo 'selected'; endif; ?>>Increment</option></select></td>
                                                            <td><input type="text" name="salary_amount[]" class="form-control salary_amount" onkeyup="getsalarytotal()" value="<?php echo e((float) ($row['amount'] ?? 0)); ?>"></td>
                                                            <td><button class="btn btn-danger btn-xs btndetlsalary" type="button"><i class="fa fa-trash"></i></button></td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                        <tr><td><select name="salary_type[]" class="form-control selectval"><option value="">Select</option><?php $__currentLoopData = $payTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($payType->id); ?>"><?php echo e($payType->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></td><td><select name="frequency[]" class="form-control"><option value="">Select</option><option value="Basic Pay">Basic Pay</option><option value="Allowance">Allowance</option><option value="Increment">Increment</option></select></td><td><input type="text" name="salary_amount[]" class="form-control salary_amount" onkeyup="getsalarytotal()" value=""></td><td></td></tr>
                                                    <?php endif; ?>
                                                </tbody>
                                                <tbody><tr><td colspan="2" style="text-align:right;"><b>Total Amount</b></td><td class="total-amount"><?php echo e($totalPayAmount); ?></td></tr></tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <h4>Salary Deduction Details</h4>
                                            <table class="table">
                                                <thead><tr><td>Salary Deduction Type</td><td>Amount</td><td><button id="btnAddsalaryded" class="btn btn-primary btn-xs" type="button"><i class="fa fa-plus"></i></button></td></tr></thead>
                                                <tbody class="salarydedwarp">
                                                    <?php $__empty_1 = true; $__currentLoopData = $payDeductionRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                        <tr class="salary_ded_remove_field_warp">
                                                            <td><select name="salary_ded_type[]" class="form-control selectval"><option value="">Select</option><?php $__currentLoopData = $payDeductionTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($payType->id); ?>" <?php if((int) $row['type_id'] === (int) $payType->id): echo 'selected'; endif; ?>><?php echo e($payType->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></td>
                                                            <td><input type="text" name="salary_ded_amount[]" class="form-control salary_ded_amount" onkeyup="getsalarydedtotal()" value="<?php echo e((float) ($row['amount'] ?? 0)); ?>"></td>
                                                            <td><button class="btn btn-danger btn-xs btndetlsalaryded" type="button"><i class="fa fa-trash"></i></button></td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                        <tr><td><select name="salary_ded_type[]" class="form-control selectval"><option value="">Select</option><?php $__currentLoopData = $payDeductionTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($payType->id); ?>"><?php echo e($payType->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></td><td><input type="text" name="salary_ded_amount[]" class="form-control salary_ded_amount" onkeyup="getsalarydedtotal()" value=""></td><td></td></tr>
                                                    <?php endif; ?>
                                                </tbody>
                                                <tbody><tr><td style="text-align:right;"><b>Total Amount</b></td><td class="total-dedamount"><?php echo e($totalDeductionAmount); ?></td></tr></tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row"><div class="col-md-12"><div class="col-md-4"></div><div class="col-md-4"><h5><b>Net Salary :</b> <span class="net-salary"><?php echo e($totalPayAmount - $totalDeductionAmount); ?></span></h5></div><div class="col-md-4"></div></div></div>
                                </div>
                            </div>

                            <div class="tshadow mb25 bozero">
                                <h4 class="pagetitleh2">Bank Account Details</h4>
                                <div class="around10">
                                    <div class="row">
                                        <div class="col-md-4"><div class="form-group"><label>Account Title</label><input id="account_title" name="account_title" type="text" class="form-control" value="<?php echo e(old('account_title', $staff->account_title)); ?>"><span class="text-danger"><?php echo e($errors->first('account_title')); ?></span></div></div>
                                        <div class="col-md-4"><div class="form-group"><label>Bank Account No</label><input id="bank_account_no" name="bank_account_no" type="text" class="form-control" value="<?php echo e(old('bank_account_no', $staff->bank_account_no)); ?>"><span class="text-danger"><?php echo e($errors->first('bank_account_no')); ?></span></div></div>
                                        <div class="col-md-4"><div class="form-group"><label>Bank Name</label><input id="bank_name" name="bank_name" type="text" class="form-control" value="<?php echo e(old('bank_name', $staff->bank_name)); ?>"><span class="text-danger"><?php echo e($errors->first('bank_name')); ?></span></div></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4"><div class="form-group"><label>IBAN Code</label><input id="IBAN_code" name="IBAN_code" type="text" class="form-control" value="<?php echo e(old('IBAN_code', old('iban_code', $staff->IBAN_code))); ?>"><span class="text-danger"><?php echo e($errors->first('iban_code')); ?></span></div></div>
                                        <div class="col-md-4"><div class="form-group"><label>Bank Branch Name</label><input id="bank_branch" name="bank_branch" type="text" class="form-control" value="<?php echo e(old('bank_branch', $staff->bank_branch)); ?>"><span class="text-danger"><?php echo e($errors->first('bank_branch')); ?></span></div></div>
                                    </div>
                                </div>
                            </div>

                            <div class="tshadow mb25 bozero">
                                <h4 class="pagetitleh2">Social Media</h4>
                                <div class="row around10">
                                    <div class="col-md-6"><div class="form-group"><label>Facebook URL</label><input name="facebook" type="text" class="form-control" value="<?php echo e(old('facebook', $staff->facebook)); ?>"><span class="text-danger"><?php echo e($errors->first('facebook')); ?></span></div></div>
                                    <div class="col-md-6"><div class="form-group"><label>Twitter URL</label><input name="twitter" type="text" class="form-control" value="<?php echo e(old('twitter', $staff->twitter)); ?>"><span class="text-danger"><?php echo e($errors->first('twitter')); ?></span></div></div>
                                    <div class="col-md-6"><div class="form-group"><label>Linkedin URL</label><input name="linkedin" type="text" class="form-control" value="<?php echo e(old('linkedin', $staff->linkedin)); ?>"><span class="text-danger"><?php echo e($errors->first('linkedin')); ?></span></div></div>
                                    <div class="col-md-6"><div class="form-group"><label>Instagram URL</label><input id="instagram" name="instagram" type="text" class="form-control" value="<?php echo e(old('instagram', $staff->instagram)); ?>"></div></div>
                                </div>
                            </div>

                            <div class="tshadow bozero">
                                <h4 class="pagetitleh2">Upload Documents</h4>
                                <div class="around10">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table">
                                                <tbody>
                                                    <tr><th style="width: 10px">#</th><th>Title</th><th>Documents</th></tr>
                                                    <tr><td>1.</td><td>Resume <?php if($staff->resume): ?><small>(<?php echo e($staff->resume); ?>)</small><?php endif; ?></td><td><input class="filestyle form-control" type="file" name="first_doc" id="doc1"><span class="text-danger"><?php echo e($errors->first('first_doc')); ?></span></td></tr>
                                                    <tr><td>3.</td><td>Other Documents <?php if($staff->other_document_file): ?><small>(<?php echo e($staff->other_document_file); ?>)</small><?php endif; ?><input type="hidden" name="fourth_title" value="Other Documents"></td><td><input class="filestyle form-control" type="file" name="fourth_doc" id="doc4"><span class="text-danger"><?php echo e($errors->first('fourth_doc')); ?></span></td></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table">
                                                <tbody>
                                                    <tr><th style="width: 10px">#</th><th>Title</th><th>Documents</th></tr>
                                                    <tr><td>2.</td><td>Joining Letter <?php if($staff->joining_letter): ?><small>(<?php echo e($staff->joining_letter); ?>)</small><?php endif; ?></td><td><input class="filestyle form-control" type="file" name="second_doc" id="doc2"><span class="text-danger"><?php echo e($errors->first('second_doc')); ?></span></td></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer">
                            <div class="pull-right">
                                <a href="<?php echo e($cancelUrl); ?>" class="btn btn-default">Cancel</a>
                                <button type="submit" class="btn btn-primary pull-right">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <?php
        $buildOptionHtml = static function ($items): string {
            $html = '<option value="">Select</option>';
            foreach ($items as $item) {
                $html .= '<option value="'.$item['id'].'">'.e($item['name']).'</option>';
            }
            return $html;
        };
    ?>
    <script>
        (() => {
            const csrfToken = <?php echo json_encode(csrf_token(), 15, 512) ?>;
            const optionStoreUrlTemplate = <?php echo json_encode(route('admin.hrms.staff.options.store', ['type' => '__TYPE__'], false)) ?>;
            const universityBoardOptions = `<?php echo addslashes($buildOptionHtml($universityBoards->map(fn ($item) => ['id' => $item->id, 'name' => $item->name])->all())); ?>`;
            const academicYearOptions = `<?php echo addslashes($buildOptionHtml($academicYears->map(fn ($item) => ['id' => $item->id, 'name' => $item->name])->all())); ?>`;
            const degreeOptions = `<?php echo addslashes($buildOptionHtml($degreeCertificates->map(fn ($item) => ['id' => $item->id, 'name' => $item->name])->all())); ?>`;
            const instituteOptions = `<?php echo addslashes($buildOptionHtml($institutes->map(fn ($item) => ['id' => $item->id, 'name' => $item->name])->all())); ?>`;
            const trainingOptions = `<?php echo addslashes($buildOptionHtml($trainings->map(fn ($item) => ['id' => $item->id, 'name' => $item->name])->all())); ?>`;
            const organizationOptions = `<?php echo addslashes($buildOptionHtml($organizations->map(fn ($item) => ['id' => $item->id, 'name' => $item->name])->all())); ?>`;
            const designationOptions = `<?php echo addslashes($buildOptionHtml($designations->map(fn ($item) => ['id' => $item->id, 'name' => $item->name])->all())); ?>`;
            const payTypeOptions = `<?php echo addslashes($buildOptionHtml($payTypes->map(fn ($item) => ['id' => $item->id, 'name' => $item->name])->all())); ?>`;
            const payDeductionOptions = `<?php echo addslashes($buildOptionHtml($payDeductionTypes->map(fn ($item) => ['id' => $item->id, 'name' => $item->name])->all())); ?>`;

            $(document).on('click', '#btnAddedu', function () {
                $('.eduwarp').append(`<tr class="remove_field_warp"><td><select name="eduinst[]" class="form-control selectval">${universityBoardOptions}</select></td><td><select name="edufrom[]" class="form-control selectval">${academicYearOptions}</select></td><td><select name="eduto[]" class="form-control selectval">${academicYearOptions}</select></td><td><select name="edudegree[]" class="form-control selectval">${degreeOptions}</select></td><td><input name="edumaxmark[]" type="text" class="form-control" value=""></td><td><input name="eduobtmark[]" type="text" class="form-control" value=""></td><td><input name="edugrd[]" type="text" class="form-control" value=""></td><td><button class="btn btn-danger btn-xs remove-row" type="button"><i class="fa fa-trash"></i></button></td></tr>`);
            });
            $(document).on('click', '#btnAddcer', function () {
                $('.cerwarp').append(`<tr class="remove_field_warp"><td><select name="cerinst[]" class="form-control selectval">${instituteOptions}</select></td><td><select name="certrining[]" class="form-control selectval">${trainingOptions}</select></td><td><select name="cerfrom[]" class="form-control selectval">${academicYearOptions}</select></td><td><select name="certo[]" class="form-control selectval">${academicYearOptions}</select></td><td><input name="cerobtmark[]" type="text" class="form-control" value=""></td><td><input name="cergrd[]" type="text" class="form-control" value=""></td><td><button class="btn btn-danger btn-xs remove-row" type="button"><i class="fa fa-trash"></i></button></td></tr>`);
            });
            $(document).on('click', '#btnAddexp', function () {
                $('.expwarp').append(`<tr class="remove_field_warp"><td><select name="exporg[]" class="form-control selectval">${organizationOptions}</select></td><td><select name="exppost[]" class="form-control selectval">${designationOptions}</select></td><td><input name="expcontact[]" type="text" class="form-control" value=""></td><td><select name="expfrom[]" class="form-control selectval">${academicYearOptions}</select></td><td><select name="expto[]" class="form-control selectval">${academicYearOptions}</select></td><td><input name="expsalary[]" type="text" class="form-control" value=""></td><td><input name="explereason[]" type="text" class="form-control" value=""></td><td><button class="btn btn-danger btn-xs remove-row" type="button"><i class="fa fa-trash"></i></button></td></tr>`);
            });
            $(document).on('click', '#btnAddsalary', function () {
                $('.salarywarp').append(`<tr class="salary_remove_field_warp"><td><select name="salary_type[]" class="form-control selectval">${payTypeOptions}</select></td><td><select name="frequency[]" class="form-control"><option value="">Select</option><option value="Basic Pay">Basic Pay</option><option value="Allowance">Allowance</option><option value="Increment">Increment</option></select></td><td><input type="text" name="salary_amount[]" class="form-control salary_amount" onkeyup="getsalarytotal()" value=""></td><td><button class="btn btn-danger btn-xs btndetlsalary" type="button"><i class="fa fa-trash"></i></button></td></tr>`);
            });
            $(document).on('click', '#btnAddsalaryded', function () {
                $('.salarydedwarp').append(`<tr class="salary_ded_remove_field_warp"><td><select name="salary_ded_type[]" class="form-control selectval">${payDeductionOptions}</select></td><td><input type="text" name="salary_ded_amount[]" class="form-control salary_ded_amount" onkeyup="getsalarydedtotal()" value=""></td><td><button class="btn btn-danger btn-xs btndetlsalaryded" type="button"><i class="fa fa-trash"></i></button></td></tr>`);
            });
            $(document).on('click', '.remove-row', function () { $(this).closest('tr').remove(); });
            $(document).on('click', '.btndetlsalary', function () { $(this).closest('.salary_remove_field_warp').remove(); getsalarytotal(); getnetsalarytotal(); });
            $(document).on('click', '.btndetlsalaryded', function () { $(this).closest('.salary_ded_remove_field_warp').remove(); getsalarydedtotal(); getnetsalarytotal(); });

            $(document).on('click', '[data-option-type]', async function () {
                const button = this;
                const type = button.dataset.optionType;
                const target = document.getElementById(button.dataset.optionTarget);
                const name = window.prompt(`Enter ${type.replace('_', ' ')} name`);

                if (!name || !target) {
                    return;
                }

                const branchId = document.getElementById('brc_id')?.value || '';
                button.disabled = true;

                try {
                    const response = await fetch(optionStoreUrlTemplate.replace('__TYPE__', type), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            name,
                            branch_id: branchId,
                        }),
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        window.alert(data.message || 'Unable to add option right now.');
                        return;
                    }

                    const option = document.createElement('option');
                    option.value = data.id;
                    option.textContent = data.name;
                    option.selected = true;
                    target.appendChild(option);
                    target.value = String(data.id);
                } catch (error) {
                    window.alert('Unable to add option right now.');
                } finally {
                    button.disabled = false;
                }
            });
        })();

        function getsalarytotal() {
            let salarysum = 0;
            $('.salary_amount').each(function () { salarysum += Number($(this).val() || 0); });
            $('.total-amount').html(salarysum);
            getnetsalarytotal();
        }

        function getsalarydedtotal() {
            let salarydedsum = 0;
            $('.salary_ded_amount').each(function () { salarydedsum += Number($(this).val() || 0); });
            $('.total-dedamount').html(salarydedsum);
            getnetsalarytotal();
        }

        function getnetsalarytotal() {
            let salarysum = 0;
            let salarydedsum = 0;
            $('.salary_amount').each(function () { salarysum += Number($(this).val() || 0); });
            $('.salary_ded_amount').each(function () { salarydedsum += Number($(this).val() || 0); });
            $('.net-salary').html(salarysum - salarydedsum);
        }

        document.querySelector('.showcam')?.addEventListener('click', () => {
            document.getElementById('my_camera').style.display = 'block';
            document.getElementById('pre_take_buttons').style.display = 'block';
            Webcam.set({ width: 220, height: 180, image_format: 'jpeg', jpeg_quality: 90 });
            Webcam.attach('#my_camera');
        });

        function preview_snapshot() {
            Webcam.freeze();
            document.getElementById('post_take_buttons').style.display = 'block';
            document.getElementById('pre_take_buttons').style.display = 'none';
        }

        function cancel_preview() {
            Webcam.unfreeze();
            document.getElementById('post_take_buttons').style.display = 'none';
            document.getElementById('pre_take_buttons').style.display = 'block';
        }

        function save_photo() {
            Webcam.snap(function (dataUri) {
                document.querySelector('.image-tag').value = dataUri;
                document.getElementById('results').style.display = 'block';
                document.getElementById('results').innerHTML = `<img src="${dataUri}" class="img-responsive img-thumbnail" />`;
                document.getElementById('post_take_buttons').style.display = 'none';
                document.getElementById('pre_take_buttons').style.display = 'block';
                Webcam.reset();
                document.getElementById('my_camera').style.display = 'none';
            });
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\TNT\cmslv_git_clone2\resources\views\admin\hrms\staff\edit.blade.php ENDPATH**/ ?>