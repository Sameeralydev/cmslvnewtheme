@php
    $currentRouteName = request()->route()?->getName() ?? '';
    $isHrmsRoute = str_starts_with($currentRouteName, 'admin.hrms.');
    $isAdmRoute = str_starts_with($currentRouteName, 'admin.adm.');
    $isAcademicsRoute = str_starts_with($currentRouteName, 'admin.academics.');
    $isAccountRoute = str_starts_with($currentRouteName, 'admin.account.');
    $isAdminDashboardRoute = in_array($currentRouteName, ['admin.dashboard', 'admin.dashboard.clean', 'cmsc.admin.dashboard'], true);
    $isSystemSettingsRoute = $currentRouteName === 'admin.systemsettings.dashboard';
    $requestedMenu = request()->string('menu')->toString();
    $requestedSubmenu = request()->string('submenu')->toString();
    $currentSidebarMenu = match ($currentRouteName) {
        'admin.hrms.dashboard' => 'dashboard',
        'admin.hrms.documents.index' => 'manual_support',
        'admin.hrms.manual.index' => 'manual_support',
        'admin.hrms.staff.index',
        'admin.hrms.staff.profile',
        'admin.hrms.staff.edit' => 'staff_recruitment',
        'admin.account.accounts.dashboard.legacy' => 'accounts',
        'admin.account.documents.index' => 'manual_accounts',
        'admin.account.accounts.index',
        'admin.account.accounts.newaccounts',
        'admin.account.accounts.newaccounts.edit',
        'admin.account.accounts.accountshead',
        'admin.account.accounts.accountshead.edit',
        'admin.account.fee-master.index' => 'chart_of_accounts',
        'admin.account.studentfee.feerevise',
        'admin.account.studentfee.assigndues',
        'admin.account.studentfee.assignfeevoucher',
        'admin.account.studentfee.assignfeevoucherdatewise',
        'admin.account.student-fees.index' => 'fee_voucher',
        'admin.account.expenses.index',
        'admin.account.payments.index',
        'admin.account.receipts.index',
        'admin.account.contra.index',
        'admin.account.journal-vouchers.index' => 'accounting_records',
        'admin.account.payroll.index' => 'payroll_adv_clearance',
        'admin.account.item-categories.index',
        'admin.account.units.index',
        'admin.account.brands.index',
        'admin.account.product-types.index',
        'admin.account.products.index',
        'admin.account.stock.index',
        'admin.account.suppliers.index',
        'admin.account.class-book-sets.index',
        'admin.account.invoice-book-sets.index',
        'admin.account.invoice-book-set-returns.index',
        'admin.account.purchases.index',
        'admin.account.purchase-returns.index',
        'admin.account.sales.index',
        'admin.account.sales-returns.index' => 'inventory_process',
        'admin.account.royalty.index' => 'network_associate_account',
        default => null,
    };

    if ($requestedMenu !== '') {
        $currentSidebarMenu = $requestedMenu;
    }

    $hrmsSidebarItems = [
        [
            'key' => 'dashboard',
            'label' => 'Dashboard',
            'icon' => 'fa fa-desktop',
            'route' => 'admin.hrms.dashboard',
            'children' => [],
        ],
        [
            'key' => 'manual_support',
            'label' => 'Manual Support',
            'icon' => 'fa fa-life-ring',
            'route' => null,
            'children' => [
                ['label' => 'Add Documents', 'route' => 'admin.hrms.documents.index'],
                ['label' => 'Policy Manual', 'route' => 'admin.hrms.manual.index'],
                ['label' => 'Flow Charts', 'route' => 'admin.hrms.manual.index'],
                ['label' => 'Supportive Documents', 'route' => 'admin.hrms.documents.index'],
                ['label' => 'Registers', 'route' => 'admin.hrms.documents.index'],
                ['label' => 'Video Supports', 'route' => 'admin.hrms.manual.index'],
            ],
        ],
        [
            'key' => 'staff_recruitment',
            'label' => 'Staff Recruitment',
            'icon' => 'fa fa-users',
            'route' => 'admin.hrms.staff.index',
            'children' => [
                ['key' => 'staff_demand', 'label' => 'Staff Demand', 'route' => 'admin.hrms.staffdemand.index'],
                ['key' => 'job_post', 'label' => 'Job Advertisements', 'route' => 'admin.hrms.jobadvertisements.index'],
                ['key' => 'job_application', 'label' => 'Job Application', 'route' => 'admin.hrms.jobapplications.index'],
                ['key' => 'written_test', 'label' => 'Written Test', 'route' => 'admin.hrms.writtentest.index'],
                ['key' => 'interview_rating', 'label' => 'Interview Rating', 'route' => 'admin.hrms.interviewratings.index'],
                ['key' => 'merit_list', 'label' => 'Merit List', 'route' => 'admin.hrms.meritlist.index'],
                ['key' => 'job_offer_letters', 'label' => 'Offer Letter', 'route' => 'admin.hrms.joboffers.index'],
                ['key' => 'recruitment_orders', 'label' => 'Recruitment Orders', 'route' => 'admin.hrms.staffrecruitmentorders.index'],
                ['key' => 'staff_directory', 'label' => 'Staff Directory', 'route' => 'admin.hrms.staff.index'],
            ],
        ],
        [
            'key' => 'compensations_benefits',
            'label' => 'Compensations Benefits',
            'icon' => 'fa fa-chart-line',
            'route' => null,
            'children' => [
                ['label' => 'Overview', 'route' => 'admin.hrms.staff.index'],
            ],
        ],
        [
            'key' => 'training_development',
            'label' => 'Training Development',
            'icon' => 'fa fa-compass',
            'route' => null,
            'children' => [
                ['label' => 'Training Agenda', 'route' => 'admin.hrms.training.agenda.index'],
                ['label' => 'Training Analysis', 'route' => 'admin.hrms.training.analysis.index'],
                ['label' => 'Training Evaluation', 'route' => 'admin.hrms.training.evaluation.index'],
            ],
        ],
        [
            'key' => 'performance_management',
            'label' => 'Performance Management',
            'icon' => 'fa fa-chart-pie',
            'route' => null,
            'children' => [
                ['label' => 'Scl Performance', 'route' => 'admin.hrms.school-performance.index'],
                ['label' => 'Teaching Appraisal', 'route' => 'admin.hrms.monthly-teacher.index'],
                ['label' => 'Management Appraisal', 'route' => 'admin.hrms.monthly-management.index'],
                ['label' => 'ACR Teaching', 'route' => 'admin.hrms.annual-teacher.index'],
                ['label' => 'ACR Management', 'route' => 'admin.hrms.annual-management.index'],
            ],
        ],
        [
            'key' => 'non_conformance',
            'label' => 'Control of Non-Conformance',
            'icon' => 'fa fa-bullseye',
            'route' => null,
            'children' => [
                ['label' => 'Non-Conformance Notice', 'route' => 'admin.hrms.notice-reply.index'],
                ['label' => 'Clearance Form', 'route' => 'admin.hrms.clearance.index'],
                ['label' => 'Emp Exit Interview', 'route' => 'admin.hrms.exit-interview.index'],
                ['label' => 'Final Settlement', 'route' => 'admin.hrms.final-settlement.index'],
                ['label' => 'Show Cause Notice', 'route' => 'admin.hrms.show-cause.index'],
                ['label' => 'Inquiry Process', 'route' => 'admin.hrms.inquiry.index'],
            ],
        ],
        [
            'key' => 'registration_termination',
            'label' => 'Registration & Termination',
            'icon' => 'fa fa-window-close',
            'route' => null,
            'children' => [
                ['key' => 'staff_disable_directory', 'label' => 'Staff Disable Directory', 'route' => 'admin.hrms.staff-disable-directory.index'],
            ],
        ],
        [
            'key' => 'reports_reviews',
            'label' => 'Reports Reviews',
            'icon' => 'fa fa-chart-column',
            'route' => null,
            'children' => [
                ['label' => 'Overview', 'route' => 'admin.hrms.staff.index'],
            ],
        ],
    ];

    $dashboardSidebarItems = [
        [
            'key' => 'dashboard',
            'label' => 'Dashboard',
            'icon' => 'fa fa-television',
            'route' => 'admin.dashboard',
            'children' => [],
        ],
        [
            'key' => 'staff_recruitment',
            'label' => 'Staff Recruitment',
            'icon' => 'fa fa-users',
            'route' => 'admin.hrms.staff.index',
            'children' => [],
        ],
        [
            'key' => 'internal_external_communication',
            'label' => "Internal & External Comm'n",
            'icon' => 'fa fa-comment-dots',
            'route' => null,
            'children' => [],
        ],
        [
            'key' => 'customer_services_management',
            'label' => 'Customer Services Mgmt.',
            'icon' => 'fa fa-list-ol',
            'route' => 'admin.adm.complaints.index',
            'children' => [],
        ],
        [
            'key' => 'admission_process',
            'label' => 'Admission Process',
            'icon' => 'fa fa-user-plus',
            'route' => 'admin.adm.students.index',
            'children' => [],
        ],
        [
            'key' => 'withdrawal_process',
            'label' => 'Withdrawal Process',
            'icon' => 'fa fa-ban',
            'route' => 'admin.adm.students.index',
            'children' => [],
        ],
        [
            'key' => 'attendance_management',
            'label' => 'Attendance Mgmt.',
            'icon' => 'fa fa-calendar-check',
            'route' => 'admin.adm.student-attendance.index',
            'children' => [],
        ],
        [
            'key' => 'syllabus_management',
            'label' => 'Syllabus Management',
            'icon' => 'fa fa-building',
            'route' => 'admin.academics.dashboard',
            'children' => [],
        ],
        [
            'key' => 'effective_lesson_planning',
            'label' => 'Lesson Planning',
            'icon' => 'fa fa-calendar-check',
            'route' => 'admin.academics.lessons.index',
            'children' => [],
        ],
        [
            'key' => 'timetable_staffing',
            'label' => 'Timetable Staffing',
            'icon' => 'fa fa-clock',
            'route' => 'admin.academics.timetables.index',
            'children' => [],
        ],
        [
            'key' => 'homework',
            'label' => 'Homework',
            'icon' => 'fa fa-flask',
            'route' => 'admin.academics.homework.index',
            'children' => [],
        ],
        [
            'key' => 'paper_generate',
            'label' => 'Paper Generate',
            'icon' => 'fa fa-file-lines',
            'route' => 'admin.academics.paper-generate.index',
            'children' => [],
        ],
        [
            'key' => 'examination',
            'label' => 'Examination',
            'icon' => 'fa fa-file-lines',
            'route' => 'admin.academics.exam-groups.index',
            'children' => [],
        ],
        [
            'key' => 'test_system',
            'label' => 'Test System',
            'icon' => 'fa fa-file',
            'route' => 'admin.academics.test-groups.index',
            'children' => [],
        ],
        [
            'key' => 'fee_voucher',
            'label' => 'Fee Voucher',
            'icon' => 'fa fa-newspaper',
            'route' => 'admin.account.student-fees.index',
            'children' => [],
        ],
        [
            'key' => 'accounting_records',
            'label' => 'Accounting Records',
            'icon' => 'fa fa-money',
            'route' => 'admin.account.accounts.index',
            'children' => [],
        ],
    ];

    $systemSettingsSidebarItems = [
        [
            'key' => 'dashboard',
            'label' => 'Dashboard',
            'icon' => 'fa fa-dashboard',
            'route' => 'admin.systemsettings.dashboard',
            'children' => [],
        ],
        [
            'key' => 'system_settings',
            'label' => 'System Settings',
            'icon' => 'fa fa-gears',
            'route' => null,
            'children' => [
                ['label' => 'General Settings', 'route' => 'admin.systemsettings.dashboard'],
                ['label' => 'Branch Settings', 'route' => 'admin.systemsettings.dashboard'],
                ['label' => 'Session Settings', 'route' => 'admin.systemsettings.dashboard'],
                ['label' => 'Notification Setting', 'route' => 'admin.systemsettings.dashboard'],
                ['label' => 'Whatsaap Messaging', 'route' => 'admin.systemsettings.dashboard'],
                ['label' => 'SMS Setting', 'route' => 'admin.systemsettings.dashboard'],
                ['label' => 'Email Setting', 'route' => 'admin.systemsettings.dashboard'],
                ['label' => 'Modules Setting', 'route' => 'admin.systemsettings.dashboard'],
                ['label' => 'Roles Permissions', 'route' => 'admin.systemsettings.dashboard'],
                ['label' => 'Front CMS Setting', 'route' => 'admin.frontcms.index'],
            ],
        ],
    ];

    $accountSidebarItems = [
        [
            'key' => 'accounts',
            'label' => 'Dashboard',
            'icon' => 'fa fa-dashboard',
            'route' => 'admin.account.accounts.dashboard.legacy',
            'children' => [],
        ],
        [
            'key' => 'manual_accounts',
            'label' => 'Manual Support',
            'icon' => 'fa fa-life-ring',
            'route' => null,
            'children' => [
                ['label' => 'Add Documents', 'route' => 'admin.account.documents.index'],
                ['label' => 'Policy Manual', 'route' => 'admin.account.documents.index'],
                ['label' => 'Flow Charts', 'route' => 'admin.account.documents.index'],
                ['label' => 'Supportive Documents', 'route' => 'admin.account.documents.index'],
                ['label' => 'Registers', 'route' => 'admin.account.documents.index'],
                ['label' => 'Video Supports', 'route' => 'admin.account.documents.index'],
            ],
        ],
        [
            'key' => 'chart_of_accounts',
            'label' => 'Chart Of Accounts',
            'icon' => 'fa fa-list',
            'route' => null,
            'children' => [
                ['label' => 'Add Accounts Type', 'route' => 'admin.account.accounts.newaccounts'],
                ['label' => 'Add New Accounts', 'route' => 'admin.account.accounts.accountshead'],
                ['label' => 'Chart Of Accounts', 'route' => 'admin.account.accounts.index'],
                ['label' => 'Fee Structure', 'route' => 'admin.account.fee-master.index'],
            ],
        ],
        [
            'key' => 'fee_voucher',
            'label' => 'Fee Voucher',
            'icon' => 'fa fa-file-invoice-dollar',
            'route' => null,
            'children' => [
                ['label' => 'Fee Revise', 'route' => 'admin.account.studentfee.feerevise'],
                ['label' => 'Assign Dues', 'route' => 'admin.account.studentfee.assigndues'],
                ['label' => 'Assign Fee Voucher', 'route' => 'admin.account.studentfee.assignfeevoucher'],
                ['label' => 'Assign Fee Voucher Date Wise', 'route' => 'admin.account.studentfee.assignfeevoucherdatewise'],
                ['label' => 'Fee Voucher Student Sibling', 'route' => 'admin.account.studentfee.feevoucherstudentsibling'],
                ['label' => 'Fee Voucher', 'route' => 'admin.account.studentfee.feevoucher'],
                ['label' => 'Custom Fee Voucher', 'route' => 'admin.account.studentfee.customfeevoucher'],
            ],
        ],
        [
            'key' => 'accounting_records',
            'label' => 'Accounting Records',
            'icon' => 'fa fa-money-bill',
            'route' => null,
            'children' => [
                ['label' => 'Expense Bill', 'route' => 'admin.account.expenses.index'],
                ['label' => 'Payment Voucher', 'route' => 'admin.account.payments.index'],
                ['label' => 'Receipt Voucher', 'route' => 'admin.account.receipts.index'],
                ['label' => 'Contra Voucher', 'route' => 'admin.account.contra.index'],
                ['label' => 'JV', 'route' => 'admin.account.journal-vouchers.index'],
                ['label' => 'Fee Collect', 'route' => 'admin.account.student-fees.index'],
                ['label' => 'Import Fee', 'route' => 'admin.account.student-fees.index'],
                ['label' => 'Cash Book', 'route' => 'admin.account.accounts.index'],
            ],
        ],
        [
            'key' => 'payroll_adv_clearance',
            'label' => 'Payroll/Advance/Clearance',
            'icon' => 'fa fa-indent',
            'route' => null,
            'children' => [
                ['label' => 'Payroll', 'route' => 'admin.account.payroll.index'],
            ],
        ],
        [
            'key' => 'inventory_process',
            'label' => 'Inventory Process',
            'icon' => 'fa fa-shopping-cart',
            'route' => null,
            'children' => [
                ['label' => 'Item Category', 'route' => 'admin.account.item-categories.index'],
                ['label' => 'Units', 'route' => 'admin.account.units.index'],
                ['label' => 'Brands', 'route' => 'admin.account.brands.index'],
                ['label' => 'Products / Services', 'route' => 'admin.account.products.index'],
                ['label' => 'Stock', 'route' => 'admin.account.stock.index'],
                ['label' => 'Supplier', 'route' => 'admin.account.suppliers.index'],
                ['label' => 'Classes Book Sets', 'route' => 'admin.account.class-book-sets.index'],
                ['label' => 'Invoice Book Sets', 'route' => 'admin.account.invoice-book-sets.index'],
                ['label' => 'Invoice Book Sets Return', 'route' => 'admin.account.invoice-book-set-returns.index'],
                ['label' => 'Purchases', 'route' => 'admin.account.purchases.index'],
                ['label' => 'Purchase Return', 'route' => 'admin.account.purchase-returns.index'],
                ['label' => 'Sale Invoice', 'route' => 'admin.account.sales.index'],
                ['label' => 'Sales Return', 'route' => 'admin.account.sales-returns.index'],
            ],
        ],
        [
            'key' => 'network_associate_account',
            'label' => 'Network Associate Account',
            'icon' => 'fa fa-sitemap',
            'route' => null,
            'children' => [
                ['label' => 'Assign Royalty Voucher', 'route' => 'admin.account.royalty.index'],
                ['label' => 'Collect Royalty', 'route' => 'admin.account.royalty.index'],
            ],
        ],
        [
            'key' => 'account_reports_reviews',
            'label' => 'Reports & Reviews',
            'icon' => 'fa fa-chart-column',
            'route' => null,
            'children' => [
                ['label' => 'General Report', 'route' => 'admin.account.accounts.index'],
                ['label' => 'Incomes / Fee Report', 'route' => 'admin.account.student-fees.index'],
                ['label' => 'Expenses Report', 'route' => 'admin.account.expenses.index'],
                ['label' => 'Payroll Report', 'route' => 'admin.account.payroll.index'],
                ['label' => 'Inventory Reports', 'route' => 'admin.account.purchases.index'],
            ],
        ],
    ];

    $admModules = app(\App\Services\Adm\AdmModuleRegistry::class)->all();
    $admChildren = static function (array $keys) use ($admModules): array {
        return array_values(array_filter(array_map(
            static fn (string $key): ?array => isset($admModules[$key])
                ? ['key' => $key, 'label' => $admModules[$key]['label'], 'route' => $admModules[$key]['route']]
                : null,
            $keys
        )));
    };
    $admissionKeys = ['enquiries', 'student-registrations', 'students', 'student-id-cards', 'siblings', 'student-transfers', 'achievements'];
    $withdrawalKeys = ['leave-requests', 'leave-approvals'];
    $attendanceKeys = ['attendance', 'student-attendance', 'subject-attendance', 'staff-attendance'];
    $customerServiceKeys = ['complaints', 'complaint-regardings', 'complaint-sources', 'complaint-types'];
    $communicationKeys = ['content', 'content-types', 'dispatch', 'documents', 'general-calls', 'general-remarks', 'mail-sms', 'notifications', 'receive', 'references', 'sources'];
    $usedAdmKeys = array_merge($admissionKeys, $withdrawalKeys, $attendanceKeys, $customerServiceKeys, $communicationKeys);
    $otherAdmKeys = array_values(array_diff(array_keys($admModules), ['dashboard'], $usedAdmKeys));

    $administrationSidebarItems = [
        ['key' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'fa fa-television', 'route' => 'admin.adm.dashboard', 'children' => []],
        ['key' => 'manual_support', 'label' => 'Manual Support', 'icon' => 'fa fa-life-ring', 'route' => null, 'children' => [
            ['label' => 'Add Documents', 'route' => 'admin.adm.documents.index'],
            ['label' => 'Policy Manual', 'route' => 'admin.adm.documents.index'],
            ['label' => 'Flow Charts', 'route' => 'admin.adm.documents.index'],
            ['label' => 'Supportive Documents', 'route' => 'admin.adm.documents.index'],
            ['label' => 'Registers', 'route' => 'admin.adm.documents.index'],
            ['label' => 'Video Supports', 'route' => 'admin.adm.video-tutorials.index'],
        ]],
        ['key' => 'admission_process', 'label' => 'Admission Process', 'icon' => 'fa fa-user-plus', 'route' => null, 'children' => $admChildren($admissionKeys)],
        ['key' => 'withdrawal_process', 'label' => 'Withdrawal Process', 'icon' => 'fa fa-ban', 'route' => null, 'children' => $admChildren($withdrawalKeys)],
        ['key' => 'attendance_management', 'label' => 'Attendance Management', 'icon' => 'fa fa-calendar-check', 'route' => null, 'children' => $admChildren($attendanceKeys)],
        ['key' => 'customer_services_management', 'label' => 'Customer Services Mgmt.', 'icon' => 'fa fa-list-ol', 'route' => null, 'children' => $admChildren($customerServiceKeys)],
        ['key' => 'internal_external_communication', 'label' => "Internal & External Comm'n", 'icon' => 'fa fa-comment-dots', 'route' => null, 'children' => $admChildren($communicationKeys)],
        ['key' => 'other_adm_modules', 'label' => 'Other ADM Modules', 'icon' => 'fa fa-th-large', 'route' => null, 'children' => $admChildren($otherAdmKeys)],
    ];

    $academicModules = app(\App\Services\Academics\AcademicModuleRegistry::class)->all();
    $academicChildren = static function (array $keys) use ($academicModules): array {
        return array_values(array_filter(array_map(
            static fn (string $key): ?array => isset($academicModules[$key])
                ? ['key' => $key, 'label' => $academicModules[$key]['label'], 'route' => $academicModules[$key]['route']]
                : null,
            $keys
        )));
    };
    $academicGroup = static fn (string $key, string $label, string $icon, array $children): array => [
        'key' => $key, 'label' => $label, 'icon' => $icon, 'route' => null, 'children' => $children,
    ];
    $academicsSidebarItems = [
        ['key' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'fa fa-television', 'route' => 'admin.academics.dashboard', 'children' => []],
        $academicGroup('manual_support', 'Manual Support', 'fa fa-life-ring', [
            ['label' => 'Add Documents', 'route' => 'admin.academics.documents.index'],
            ['label' => 'Policy Manual', 'route' => 'admin.academics.documents.index'],
            ['label' => 'Flow Charts', 'route' => 'admin.academics.documents.index'],
            ['label' => 'Supportive Documents', 'route' => 'admin.academics.documents.index'],
            ['label' => 'Registers', 'route' => 'admin.academics.documents.index'],
            ['label' => 'Video Supports', 'route' => 'admin.academics.documents.index'],
        ]),
        $academicGroup('curriculum_management', 'Curriculum Mgmt.', 'fa fa-list-alt', $academicChildren(['subjects', 'subject-groups', 'chapters', 'topics'])),
        $academicGroup('syllabus_management', 'Syllabus Mgmt.', 'fa fa-building', $academicChildren(['term-settings', 'week-settings', 'day-settings', 'syllabus', 'syllabus-directory'])),
        $academicGroup('effective_lesson_planning', 'Effective Lesson Planning', 'fa fa-calendar-check', $academicChildren(['lessons'])),
        $academicGroup('timetable_staffing', 'Timetable & Staffing', 'fa fa-clock', [['label' => 'Time Allocation', 'route' => 'admin.academics.time-allocation.index'], ['label' => 'TimeTable', 'route' => 'admin.academics.timetables.index'], ['label' => 'Master Timetable', 'route' => 'admin.academics.master-timetables.index'], ...$academicChildren(['teachers'])]),
        $academicGroup('what_i_have_learnt', 'What I Have Learnt', 'fa fa-flask', $academicChildren(['homework'])),
        $academicGroup('zoom_live_classes', 'Zoom Live Classes', 'fa fa-video-camera', []),
        $academicGroup('gmeet_live_classes', 'Gmeet Live Classes', 'fa fa-video-camera', $academicChildren(['google-meet'])),
        $academicGroup('question_bank', 'Question Bank', 'fa fa-question', $academicChildren(['questions'])),
        $academicGroup('paper_generate', 'Paper Generate', 'fa fa-file-lines', $academicChildren(['paper-generate'])),
        $academicGroup('holistic_development', 'Holistic Development', 'fa fa-chart-column', []),
        $academicGroup('examination', 'Examination', 'fa fa-file-lines', $academicChildren(['exam-groups', 'exam-schedules', 'exam-results'])),
        $academicGroup('test_system', 'Test System', 'fa fa-file', $academicChildren(['test-groups', 'test-schedules', 'test-results'])),
        $academicGroup('online_examination', 'Online Examination', 'fa fa-rss', $academicChildren(['online-exams'])),
        $academicGroup('grooming_analysis', 'Grooming Analysis', 'fa fa-chart-area', $academicChildren(['grooming', 'grooming-domains', 'grooming-parameters'])),
        $academicGroup('parent_teachers_meeting', 'Parent Teachers Meeting', 'fa fa-handshake', []),
        $academicGroup('discipline_conduct', 'Discipline / Conduct', 'fa fa-person-dress', []),
        $academicGroup('library', 'Library', 'fa fa-book', $academicChildren(['members', 'books'])),
        $academicGroup('lab', 'Lab', 'fa fa-flask', []),
        $academicGroup('academic_conferences', 'Conferences', 'fa fa-comments', $academicChildren(['conferences'])),
        $academicGroup('academic_reports_reviews', 'Reports & Reviews', 'fa fa-chart-column', []),
    ];

    $sidebarItems = $isAdminDashboardRoute
        ? $dashboardSidebarItems
        : ($isSystemSettingsRoute
            ? $systemSettingsSidebarItems
            : ($isAccountRoute
                ? $accountSidebarItems
                : ($isAdmRoute ? $administrationSidebarItems : ($isAcademicsRoute ? $academicsSidebarItems : $hrmsSidebarItems))));
@endphp

<aside class="admin-sidebar" aria-label="Primary navigation">
    <div class="admin-sidebar-header">
        <span class="admin-sidebar-logo">TS</span>
        <div class="admin-sidebar-user">
            <a href="{{ route('admin.dashboard', absolute: false) }}">Super Admin</a>
            <span>Management portal</span>
        </div>
    </div>

    <div class="admin-sidebar-session">
        <strong>Current Session</strong><br>
        <span>2026–27</span>
    </div>

    <nav class="admin-sidebar-nav">
        @foreach ($sidebarItems as $item)
            @php
                $itemIsActive = !empty($item['route']) ? request()->routeIs($item['route']) : false;
                $menuIsActive = ($item['key'] ?? null) !== null && ($item['key'] ?? null) === $currentSidebarMenu;
                $childRouteIsActive = collect($item['children'])->contains(fn (array $child): bool => !empty($child['route']) && request()->routeIs($child['route']));
                $isExpanded = $itemIsActive || $menuIsActive || $childRouteIsActive;
            @endphp

            @if ($item['children'] !== [])
                <div class="admin-sidebar-section">
                    <a
                        class="admin-sidebar-link px-3"
                        data-sidebar-toggle
                        data-sidebar-target="sidebar-menu-{{ $item['key'] }}"
                        aria-expanded="{{ $isExpanded ? 'true' : 'false' }}"
<<<<<<< HEAD
                        href="{{ $item['route'] ? route($item['route'], absolute: false) : '#' }}"
=======
                        href="{{ (!empty($item['route']) && \Illuminate\Support\Facades\Route::has($item['route'])) ? route($item['route'], absolute: false) : 'javascript:void(0);' }}"
>>>>>>> d2db107 (feat: Payment Voucher & Expense Bill 2-Up PDF print download, table layout and account modules updates)
                    >
                        <span class="admin-sidebar-link-icon"><i class="{{ $item['icon'] }}"></i></span>
                        <span class="admin-sidebar-link-label">{{ $item['label'] }}</span>
                        <span class="admin-sidebar-link-chevron"><i data-sidebar-chevron class="fa fa-angle-{{ $isExpanded ? 'down' : 'right' }}"></i></span>
                    </a>

                    <div id="sidebar-menu-{{ $item['key'] }}" class="admin-sidebar-tree {{ $isExpanded ? 'is-open' : '' }}">
                        <div class="admin-sidebar-tree-inner">
                            @foreach ($item['children'] as $child)
                                @php
                                    $childKey = $child['key'] ?? '';
<<<<<<< HEAD
                                    $childIsCurrent = request()->routeIs($child['route']) && ($requestedSubmenu === '' || $requestedSubmenu === $childKey);
                                    // Keep navigation URLs clean. Menu state is handled by the
                                    // sidebar toggle and active route detection, not query strings.
                                    $childUrl = route($child['route'], absolute: false);
=======
                                    $hasRoute = !empty($child['route']) && \Illuminate\Support\Facades\Route::has($child['route']);
                                    $childIsCurrent = $hasRoute && request()->routeIs($child['route']) && ($requestedSubmenu === '' || $requestedSubmenu === $childKey);
                                    $childUrl = $hasRoute ? route($child['route'], absolute: false) : '#';
>>>>>>> d2db107 (feat: Payment Voucher & Expense Bill 2-Up PDF print download, table layout and account modules updates)
                                @endphp
                                <a class="admin-sidebar-link admin-sidebar-child {{ $childIsCurrent ? 'is-active' : '' }}" href="{{ $childUrl }}">
                                    <span class="admin-sidebar-link-icon"><i class="fa fa-angle-double-right"></i></span>
                                    <span class="admin-sidebar-link-label">{{ $child['label'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <a class="admin-sidebar-link px-3 {{ $itemIsActive ? 'is-active' : '' }}" href="{{ (!empty($item['route']) && \Illuminate\Support\Facades\Route::has($item['route'])) ? route($item['route'], absolute: false) : '#' }}">
                    <span class="admin-sidebar-link-icon"><i class="{{ $item['icon'] }}"></i></span>
                    <span class="admin-sidebar-link-label">{{ $item['label'] }}</span>
                </a>
            @endif
        @endforeach
    </nav>
</aside>
