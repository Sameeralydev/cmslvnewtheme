<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class SystemSettingController extends Controller
{
    public static function menu(): array
    {
        return [
            'general' => ['label' => 'General Settings', 'table' => 'system_settings', 'fields' => ['name', 'phone', 'email', 'address', 'timezone', 'date_format', 'currency', 'currency_format', 'currency_place', 'base_url', 'folder_path']],
            'logo' => ['label' => 'Logo', 'table' => 'system_settings', 'fields' => ['admin_logo', 'admin_small_logo', 'app_logo', 'image']],
            'loginpagebackground' => ['label' => 'Login Page Background', 'table' => 'system_settings', 'fields' => ['admin_login_page_background', 'user_login_page_background']],
            'backendtheme' => ['label' => 'Backend Theme', 'table' => 'system_settings', 'fields' => ['theme']],
            'mobileapp' => ['label' => 'Mobile App', 'table' => 'system_settings', 'fields' => ['mobile_api_url', 'app_primary_color_code', 'app_secondary_color_code', 'admin_mobile_api_url', 'admin_app_primary_color_code', 'admin_app_secondary_color_code']],
            'studentguardianpanel' => ['label' => 'Student / Guardian Panel', 'table' => 'system_settings', 'fields' => ['student_login', 'parent_login', 'student_panel_login', 'parent_panel_login', 'student_timeline']],
            'royalty' => ['label' => 'Royalty', 'table' => 'branch', 'fields' => ['is_royalty_type', 'is_royalty_amount']],
            'fees' => ['label' => 'Fees', 'table' => 'system_settings', 'fields' => ['is_fees_mode', 'fee_mode_admission', 'fee_due_days', 'is_duplicate_fees_invoice', 'collect_back_date_fees', 'lock_grace_period', 'is_offline_fee_payment', 'offline_bank_payment_instruction']],
            'idautogeneration' => ['label' => 'ID Auto Generation', 'table' => 'system_settings', 'fields' => ['adm_auto_insert', 'adm_prefix', 'adm_start_from', 'adm_no_digit', 'regd_auto_insert', 'regd_prefix', 'regd_start_from', 'regd_no_digit', 'staffid_auto_insert', 'staffid_prefix', 'staffid_start_from', 'staffid_no_digit']],
            'attendancetype' => ['label' => 'Attendance Type', 'table' => 'system_settings', 'fields' => ['attendence_type', 'biometric', 'biometric_device', 'low_attendance_limit']],
            'maintenance' => ['label' => 'Maintenance', 'table' => 'system_settings', 'fields' => ['maintenance_mode']],
            'miscellaneous' => ['label' => 'Miscellaneous', 'table' => 'system_settings', 'fields' => ['my_question', 'exam_result', 'class_teacher', 'superadmin_restriction', 'event_reminder', 'staff_notification_email', 'scan_code_type']],
            'branch' => ['label' => 'Branch Settings', 'table' => 'branch', 'fields' => ['name', 'regd_date', 'phone', 'email', 'address', 'websiteurl', 'is_active']],
            'session' => ['label' => 'Session Settings', 'table' => 'sessions', 'fields' => ['session', 'start_date', 'end_date', 'is_active']],
            'notification' => ['label' => 'Notification Setting', 'table' => 'notification_setting', 'fields' => ['type', 'is_mail', 'is_sms', 'is_whatsapp', 'is_notification', 'is_student_recipient', 'is_guardian_recipient', 'is_staff_recipient']],
            'whatsapp' => ['label' => 'Whatsapp Messaging', 'table' => 'whatsapp_config', 'fields' => ['type', 'name', 'api_id', 'authkey', 'senderid', 'contact', 'username', 'url', 'password', 'is_active']],
            'sms' => ['label' => 'SMS Setting', 'table' => 'sms_config', 'fields' => ['type', 'username', 'password', 'senderid', 'url', 'is_active']],
            'email' => ['label' => 'Email Setting', 'table' => 'email_config', 'fields' => ['email_type', 'smtp_server', 'smtp_port', 'smtp_username', 'smtp_password', 'ssl_tls', 'smtp_auth', 'is_active']],
            'modules' => ['label' => 'Modules Setting', 'table' => 'system_settings', 'fields' => ['modules']],
            'roles' => ['label' => 'Roles Permissions', 'table' => 'roles', 'fields' => ['name', 'is_active']],
            'country' => ['label' => 'Country', 'table' => 'country', 'fields' => ['name', 'is_active']],
            'province' => ['label' => 'Province', 'table' => 'province', 'fields' => ['name', 'country_id', 'is_active']],
            'division' => ['label' => 'Division', 'table' => 'division', 'fields' => ['name', 'province_id', 'is_active']],
            'district' => ['label' => 'District', 'table' => 'district', 'fields' => ['name', 'division_id', 'is_active']],
            'tehsils' => ['label' => 'Tehsil', 'table' => 'tehsils', 'fields' => ['name', 'district_id', 'is_active']],
            'area' => ['label' => 'Area', 'table' => 'area', 'fields' => ['name', 'tehsils_id', 'is_active']],
            'sections' => ['label' => 'Sections', 'table' => 'sections', 'fields' => ['section', 'name', 'is_active']],
            'classes' => ['label' => 'Class', 'table' => 'classes', 'fields' => ['class', 'name', 'is_active']],
            'department' => ['label' => 'Department', 'table' => 'department', 'fields' => ['name', 'is_active']],
            'designation' => ['label' => 'Designation', 'table' => 'designation', 'fields' => ['name', 'is_active']],
            'academicyear' => ['label' => 'Academic Year', 'table' => 'adcademicyear', 'fields' => ['session', 'start_date', 'end_date', 'is_active']],
            'leavetype' => ['label' => 'Leave Type', 'table' => 'leave_types', 'fields' => ['type', 'name', 'is_active']],
            'studentcategories' => ['label' => 'Student Categories', 'table' => 'categories', 'fields' => ['category', 'name', 'is_active']],
            'skills' => ['label' => 'Skill', 'table' => 'skill', 'fields' => ['name', 'is_active']],
            'medium' => ['label' => 'Medium', 'table' => 'medium', 'fields' => ['name', 'is_active']],
            'occupation' => ['label' => 'Occupation', 'table' => 'occupation', 'fields' => ['name', 'is_active']],
            'living' => ['label' => 'Living', 'table' => 'living', 'fields' => ['name', 'is_active']],
            'education' => ['label' => 'Education', 'table' => 'education', 'fields' => ['name', 'is_active']],
            'religion' => ['label' => 'Religion', 'table' => 'religion', 'fields' => ['name', 'is_active']],
            'caste' => ['label' => 'Caste', 'table' => 'caste', 'fields' => ['name', 'is_active']],
            'banks' => ['label' => 'Banks', 'table' => 'banks', 'fields' => ['name', 'is_active']],
            'concessiontype' => ['label' => 'Concession Type', 'table' => 'concessiontype', 'fields' => ['name', 'is_active']],
            'previousschool' => ['label' => 'Previous School', 'table' => 'perviousschool', 'fields' => ['name', 'is_active']],
            'disablereason' => ['label' => 'Disable Reason', 'table' => 'disable_reason', 'fields' => ['name', 'is_active']],
            'height' => ['label' => 'Height', 'table' => 'height', 'fields' => ['name', 'is_active']],
            'weight' => ['label' => 'Weight', 'table' => 'weight', 'fields' => ['name', 'is_active']],
            'universityboard' => ['label' => 'University / Board', 'table' => 'universityboard', 'fields' => ['name', 'is_active']],
            'degreecertificate' => ['label' => 'Degree / Certificate', 'table' => 'degreecertificate', 'fields' => ['name', 'is_active']],
            'institute' => ['label' => 'Institute', 'table' => 'institute', 'fields' => ['name', 'is_active']],
            'training' => ['label' => 'Training', 'table' => 'training', 'fields' => ['name', 'is_active']],
            'organization' => ['label' => 'Organization', 'table' => 'organization', 'fields' => ['name', 'is_active']],
            'jobcategory' => ['label' => 'Job Category', 'table' => 'job_categories', 'fields' => ['name', 'is_active']],
        ];
    }

    public function index(string $slug): View
    {
        if (isset(ParticleController::menu()[$slug])) {
            return (new ParticleController())->index(request(), $slug);
        }

        abort_unless(isset(self::menu()[$slug]), 404);
        $config = self::menu()[$slug];
        $columns = Schema::hasTable($config['table']) ? Schema::getColumnListing($config['table']) : [];
        $fields = array_values(array_intersect($config['fields'], $columns));
        $records = Schema::hasTable($config['table']) ? DB::table($config['table'])->latest('id')->paginate(20)->withQueryString() : collect();
        $editing = request()->integer('edit') ?: null;
        $editRecord = $editing ? DB::table($config['table'])->where('id', $editing)->first() : null;

        return view('admin.systemsettings.resource', compact('slug', 'config', 'columns', 'fields', 'records', 'editRecord'));
    }

    public function store(Request $request, string $slug): RedirectResponse
    {
        if (isset(ParticleController::menu()[$slug])) {
            return (new ParticleController())->store($request, $slug);
        }

        return $this->save($request, $slug);
    }

    public function update(Request $request, string $slug, int $id): RedirectResponse
    {
        if (isset(ParticleController::menu()[$slug])) {
            return (new ParticleController())->update($request, $slug, $id);
        }

        return $this->save($request, $slug, $id);
    }

    public function destroy(string $slug, int $id): RedirectResponse
    {
        if (isset(ParticleController::menu()[$slug])) {
            return (new ParticleController())->destroy($slug, $id);
        }

        abort_unless(isset(self::menu()[$slug]), 404);
        $table = self::menu()[$slug]['table'];
        if (Schema::hasTable($table)) DB::table($table)->where('id', $id)->delete();
        return back()->with('success', self::menu()[$slug]['label'].' record deleted.');
    }

    private function save(Request $request, string $slug, ?int $id = null): RedirectResponse
    {
        abort_unless(isset(self::menu()[$slug]), 404);
        $config = self::menu()[$slug];
        abort_unless(Schema::hasTable($config['table']), 404);
        $columns = Schema::getColumnListing($config['table']);
        $fields = array_values(array_intersect($config['fields'], $columns));
        $data = $request->only($fields);
        foreach ($data as $key => $value) if ($value === '') $data[$key] = null;
        if (in_array('is_active', $fields, true) && !$request->has('is_active')) $data['is_active'] = 0;
        $timestamps = array_intersect(['created_at', 'updated_at'], $columns);
        if ($id) DB::table($config['table'])->where('id', $id)->update($data + (in_array('updated_at', $timestamps, true) ? ['updated_at' => now()] : []));
        else DB::table($config['table'])->insert($data + (in_array('created_at', $timestamps, true) ? ['created_at' => now()] : []) + (in_array('updated_at', $timestamps, true) ? ['updated_at' => now()] : []));
        return back()->with('success', $config['label'].' saved successfully.');
    }
}
