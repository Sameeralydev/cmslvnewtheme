<?php

use App\Http\Controllers\Admin\Academics\AcademicDocumentController;
use App\Http\Controllers\Admin\Academics\AcademicsController;
use App\Http\Controllers\Admin\Academics\BookController;
use App\Http\Controllers\Admin\Academics\ChapterController;
use App\Http\Controllers\Admin\Academics\ConferenceController;
use App\Http\Controllers\Admin\Academics\DaySettingController;
use App\Http\Controllers\Admin\Academics\DomainController;
use App\Http\Controllers\Admin\Academics\DomainModuleController;
use App\Http\Controllers\Admin\Academics\ExamGroupController;
use App\Http\Controllers\Admin\Academics\ExamResultController;
use App\Http\Controllers\Admin\Academics\ExamScheduleController;
use App\Http\Controllers\Admin\Academics\GoogleMeetController;
use App\Http\Controllers\Admin\Academics\GroomingController;
use App\Http\Controllers\Admin\Academics\GroomingDomainController;
use App\Http\Controllers\Admin\Academics\GroomingParameterController;
use App\Http\Controllers\Admin\Academics\HomeworkController;
use App\Http\Controllers\Admin\Academics\LessonController;
use App\Http\Controllers\Admin\Academics\MemberController;
use App\Http\Controllers\Admin\Academics\OnlineExamController;
use App\Http\Controllers\Admin\Academics\PaperGenerateController;
use App\Http\Controllers\Admin\Academics\QuestionController;
use App\Http\Controllers\Admin\Academics\SubjectController;
use App\Http\Controllers\Admin\Academics\SubjectGroupController;
use App\Http\Controllers\Admin\Academics\SyllabusController;
use App\Http\Controllers\Admin\Academics\TeacherController;
use App\Http\Controllers\Admin\Academics\TermSettingController;
use App\Http\Controllers\Admin\Academics\TestGroupController;
use App\Http\Controllers\Admin\Academics\TestResultController;
use App\Http\Controllers\Admin\Academics\TestScheduleController;
use App\Http\Controllers\Admin\Academics\TimetableController;
use App\Http\Controllers\Admin\Academics\TopicController;
use App\Http\Controllers\Admin\Academics\WeekSettingController;
use App\Http\Controllers\Admin\Account\AccountController;
use App\Http\Controllers\Admin\Account\AccountDocumentController;
use App\Http\Controllers\Admin\Account\BrandController;
use App\Http\Controllers\Admin\Account\ClassBookSetController;
use App\Http\Controllers\Admin\Account\ContraVoucherController;
use App\Http\Controllers\Admin\Account\ExpenseController;
use App\Http\Controllers\Admin\Account\FeeMasterController;
use App\Http\Controllers\Admin\Account\InvoiceBookSetController;
use App\Http\Controllers\Admin\Account\InvoiceBookSetReturnController;
use App\Http\Controllers\Admin\Account\ItemCategoryController;
use App\Http\Controllers\Admin\Account\JournalVoucherController;
use App\Http\Controllers\Admin\Account\PaymentVoucherController;
use App\Http\Controllers\Admin\Account\PayrollController;
use App\Http\Controllers\Admin\Account\ProductController;
use App\Http\Controllers\Admin\Account\ProductTypeController;
use App\Http\Controllers\Admin\Account\PurchaseController;
use App\Http\Controllers\Admin\Account\PurchaseReturnController;
use App\Http\Controllers\Admin\Account\ReceiptVoucherController;
use App\Http\Controllers\Admin\Account\RoyaltyController;
use App\Http\Controllers\Admin\Account\SaleController;
use App\Http\Controllers\Admin\Account\SaleReturnController;
use App\Http\Controllers\Admin\Account\StockController;
use App\Http\Controllers\Admin\Account\StudentFeeController;
use App\Http\Controllers\Admin\Account\SupplierController;
use App\Http\Controllers\Admin\Account\UnitController;
use App\Http\Controllers\Admin\Adm\AchievementController as AdmAchievementController;
use App\Http\Controllers\Admin\Adm\AdmDashboardController;
use App\Http\Controllers\Admin\Adm\AdmDocumentController;
use App\Http\Controllers\Admin\Adm\AttendanceController;
use App\Http\Controllers\Admin\Adm\CalendarController;
use App\Http\Controllers\Admin\Adm\ChatController;
use App\Http\Controllers\Admin\Adm\ComplaintController;
use App\Http\Controllers\Admin\Adm\ComplaintRegardingController;
use App\Http\Controllers\Admin\Adm\ComplaintSourceController;
use App\Http\Controllers\Admin\Adm\ComplaintTypeController;
use App\Http\Controllers\Admin\Adm\ContentController;
use App\Http\Controllers\Admin\Adm\ContentTypeController;
use App\Http\Controllers\Admin\Adm\DispatchController;
use App\Http\Controllers\Admin\Adm\EnquiryController;
use App\Http\Controllers\Admin\Adm\GeneralCallController;
use App\Http\Controllers\Admin\Adm\GeneralRemarkController;
use App\Http\Controllers\Admin\Adm\IdCardGeneratorController;
use App\Http\Controllers\Admin\Adm\LeaveApprovalController;
use App\Http\Controllers\Admin\Adm\LeaveRequestController;
use App\Http\Controllers\Admin\Adm\MailSmsController;
use App\Http\Controllers\Admin\Adm\NotificationController;
use App\Http\Controllers\Admin\Adm\ReceiveController;
use App\Http\Controllers\Admin\Adm\ReferenceController;
use App\Http\Controllers\Admin\Adm\SiblingController;
use App\Http\Controllers\Admin\Adm\SourceController;
use App\Http\Controllers\Admin\Adm\StaffAttendanceController;
use App\Http\Controllers\Admin\Adm\StaffIdCardController;
use App\Http\Controllers\Admin\Adm\StaffIdCardGeneratorController;
use App\Http\Controllers\Admin\Adm\StudentAttendanceController;
use App\Http\Controllers\Admin\Adm\StudentController;
use App\Http\Controllers\Admin\Adm\StudentIdCardController;
use App\Http\Controllers\Admin\Adm\StudentRegistrationController;
use App\Http\Controllers\Admin\Adm\StudentTransferController;
use App\Http\Controllers\Admin\Adm\SubjectAttendanceController;
use App\Http\Controllers\Admin\Adm\VideoTutorialController;
use App\Http\Controllers\Admin\Adm\VisitorPurposeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Front\BannerController;
use App\Http\Controllers\Admin\Front\EventController;
use App\Http\Controllers\Admin\Front\GalleryController as FrontGalleryController;
use App\Http\Controllers\Admin\Front\MediaController;
use App\Http\Controllers\Admin\Front\MenuController;
use App\Http\Controllers\Admin\Front\NoticeController;
use App\Http\Controllers\Admin\Front\PageController as FrontPageController;
use App\Http\Controllers\Admin\FrontCmsController;
use App\Http\Controllers\Admin\Hrms\HrDocumentController;
use App\Http\Controllers\Admin\Hrms\HrManualController;
use App\Http\Controllers\Admin\Hrms\HrmsDashboardController;
use App\Http\Controllers\Admin\Hrms\StaffController as HrmsStaffController;
use App\Http\Controllers\Admin\Hrms\StaffDisableDirectoryController;
use App\Http\Controllers\Admin\Hrms\StaffDemandController;
use App\Http\Controllers\Admin\Hrms\JobAdvertisementController;
use App\Http\Controllers\Admin\Hrms\JobApplicationController;
use App\Http\Controllers\Admin\Hrms\WrittenTestController;
use App\Http\Controllers\Admin\Hrms\InterviewRatingController;
use App\Http\Controllers\Admin\Hrms\MeritListController;
use App\Http\Controllers\Admin\Hrms\JobOfferController;
use App\Http\Controllers\Admin\Hrms\StaffRecruitmentOrderController;
use App\Http\Controllers\Admin\Hrms\PerformanceAndComplianceController;
use App\Http\Controllers\Admin\Hrms\TrainingAgendaController;
use App\Http\Controllers\Admin\Hrms\TrainingNeedAnalysisController;
use App\Http\Controllers\Admin\Hrms\TrainingEvaluationController;
use App\Http\Controllers\Admin\MembershipController;
use App\Http\Controllers\Admin\QmsController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\SystemNotificationController;
use App\Http\Controllers\Admin\SystemSettingsDashboardController;
use App\Http\Controllers\Biometric\BiometricController;
use App\Http\Controllers\Cron\CronController;
use App\Http\Controllers\Frontend\WelcomeController;
use App\Http\Controllers\Site\AuthController as SiteAuthController;
use App\Http\Controllers\Staff\AuthController as StaffAuthController;
use App\Http\Controllers\Teacher\ConferenceController as TeacherConferenceController;
use App\Http\Controllers\Teacher\ExamResultController as TeacherExamResultController;
use App\Http\Controllers\Teacher\ExamScheduleController as TeacherExamScheduleController;
use App\Http\Controllers\Teacher\GoogleMeetController as TeacherGoogleMeetController;
use App\Http\Controllers\Teacher\GroomingController as TeacherGroomingController;
use App\Http\Controllers\Teacher\HomeworkController as TeacherHomeworkController;
use App\Http\Controllers\Teacher\LeaveApprovalController as TeacherLeaveApprovalController;
use App\Http\Controllers\Teacher\LessonController as TeacherLessonController;
use App\Http\Controllers\Teacher\StudentAttendanceController as TeacherStudentAttendanceController;
use App\Http\Controllers\Teacher\SyllabusController as TeacherSyllabusController;
use App\Http\Controllers\Teacher\TeacherDashboardController;
use App\Http\Controllers\Teacher\TeacherPasswordController;
use App\Http\Controllers\Teacher\TeacherProfileController;
use App\Http\Controllers\Teacher\TermSettingController as TeacherTermSettingController;
use App\Http\Controllers\Teacher\TestResultController as TeacherTestResultController;
use App\Http\Controllers\Teacher\TestScheduleController as TeacherTestScheduleController;
use App\Http\Controllers\Teacher\TimetableController as TeacherTimetableController;
use App\Http\Controllers\User\AttendanceController as UserAttendanceController;
use App\Http\Controllers\User\BookController as UserBookController;
use App\Http\Controllers\User\ConferenceController as UserConferenceController;
use App\Http\Controllers\User\ContentController as UserContentController;
use App\Http\Controllers\User\GoogleMeetController as UserGoogleMeetController;
use App\Http\Controllers\User\LeaveRequestController as UserLeaveRequestController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\UserFeeController;
use App\Http\Controllers\User\UserPasswordController;
use App\Http\Controllers\User\UserProfileController;
use App\Http\Controllers\User\UserUsernameController;
use App\Http\Controllers\User\VideoTutorialController as UserVideoTutorialController;
use Illuminate\Support\Facades\Route;

Route::controller(WelcomeController::class)->group(function () {
    Route::get('/', 'index')->name('frontend.home');
    Route::get('/frontend', 'index')->name('frontend.index');

    Route::get('/page/{branch}/{slug}', 'page')
        ->where([
            'branch' => '[A-Za-z0-9_-]+',
            'slug' => '[A-Za-z0-9_-]+',
        ])
        ->name('frontend.page');

    Route::get('/read/{branch}/{slug}', 'read')
        ->where([
            'branch' => '[A-Za-z0-9_-]+',
            'slug' => '[A-Za-z0-9_-]+',
        ])
        ->name('frontend.read');

    Route::get('/branch/{id}', 'branch')
        ->whereNumber('id')
        ->name('frontend.branch');

    Route::get('/franchises', 'franchises')->name('frontend.franchises');
    Route::get('/franchiseoffer', 'franchiseOffer')->name('frontend.franchise-offer');
    Route::get('/register', 'register')->name('frontend.register');
    Route::post('/register', 'storeRegistration')->name('frontend.register.store');
    Route::get('/privacypolicy', 'privacyPolicy')->name('frontend.privacy-policy');
    Route::get('/contactus', 'contactUs')->name('frontend.contact-us');
    Route::post('/contactus', 'storeContact')->name('frontend.contact-us.store');
});

Route::redirect('/home', '/')->name('home');

Route::middleware(['auth'])->group(function () {
    Route::redirect('dashboard', '/admin/admin/dashboard')->name('dashboard');
});

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin', 'branch'])
    ->group(function () {
        Route::get('/admin/dashboard/{branch?}', [DashboardController::class, 'index'])
            ->middleware('permission:dashboard,view')
            ->whereNumber('branch')
            ->name('dashboard');

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->middleware('permission:dashboard,view')
            ->name('dashboard.clean');

        Route::get('/staff', [StaffController::class, 'index'])
            ->middleware('permission:staff,view')
            ->name('staff.index');

        Route::get('/report', [ReportController::class, 'index'])
            ->middleware('permission:report,view')
            ->name('report.index');

        Route::get('/frontcms', [FrontCmsController::class, 'index'])
            ->middleware('permission:front_cms,view')
            ->name('frontcms.index');

        Route::get('/setting/systemsettings/dashboard', [SystemSettingsDashboardController::class, 'index'])
            ->name('systemsettings.dashboard');

        Route::get('/membership', [MembershipController::class, 'index'])
            ->middleware('permission:membership,view')
            ->name('membership.index');

        Route::get('/qms', [QmsController::class, 'index'])
            ->middleware('permission:qms,view')
            ->name('qms.index');

        Route::get('/systemnotification', [SystemNotificationController::class, 'index'])
            ->middleware('permission:system_notification,view')
            ->name('system-notification.index');
    });

Route::prefix('cmsc/admin')
    ->name('cmsc.admin.')
    ->middleware(['auth', 'admin', 'branch'])
    ->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'index'])
            ->middleware('permission:dashboard,view')
            ->name('dashboard');
    });

Route::prefix('admin/academics')
    ->name('admin.academics.')
    ->middleware(['auth', 'admin', 'branch'])
    ->group(function () {
        Route::get('/acadm', [AcademicsController::class, 'index'])
            ->middleware('permission:academics,view')
            ->name('dashboard');

        Route::get('/acadm/dashboard', [AcademicsController::class, 'index'])
            ->middleware('permission:academics,view')
            ->name('dashboard.legacy');

        Route::get('/book', [BookController::class, 'index'])
            ->middleware('permission:book,view')
            ->name('books.index');

        Route::get('/chapter', [ChapterController::class, 'index'])
            ->middleware('permission:chapter,view')
            ->name('chapters.index');
        Route::post('/chapter', [ChapterController::class, 'store'])->middleware('permission:chapter,add')->name('chapters.store');
        Route::put('/chapter/{chapter}', [ChapterController::class, 'update'])->middleware('permission:chapter,edit')->name('chapters.update');
        Route::delete('/chapter/{chapter}', [ChapterController::class, 'destroy'])->middleware('permission:chapter,delete')->name('chapters.destroy');

        Route::get('/conference', [ConferenceController::class, 'index'])
            ->middleware('permission:conference,view')
            ->name('conferences.index');

        Route::get('/daysetting', [DaySettingController::class, 'index'])
            ->middleware('permission:daysetting,view')
            ->name('day-settings.index');

        Route::get('/documentsaca', [AcademicDocumentController::class, 'index'])
            ->middleware('permission:documentsaca,view')
            ->name('documents.index');

        Route::get('/domain', [DomainController::class, 'index'])
            ->middleware('permission:domain,view')
            ->name('domains.index');
        Route::post('/domain', [DomainController::class, 'store'])->middleware('permission:domain,add')->name('domains.store');
        Route::put('/domain/{domain}', [DomainController::class, 'update'])->middleware('permission:domain,edit')->name('domains.update');
        Route::delete('/domain/{domain}', [DomainController::class, 'destroy'])->middleware('permission:domain,delete')->name('domains.destroy');

        Route::get('/domainmodules', [DomainModuleController::class, 'index'])
            ->middleware('permission:domainmodules,view')
            ->name('domain-modules.index');
        Route::post('/domainmodules', [DomainModuleController::class, 'store'])->middleware('permission:domainmodules,add')->name('domain-modules.store');
        Route::put('/domainmodules/{domainModule}', [DomainModuleController::class, 'update'])->middleware('permission:domainmodules,edit')->name('domain-modules.update');
        Route::delete('/domainmodules/{domainModule}', [DomainModuleController::class, 'destroy'])->middleware('permission:domainmodules,delete')->name('domain-modules.destroy');

        Route::get('/examgroup', [ExamGroupController::class, 'index'])
            ->middleware('permission:examgroup,view')
            ->name('exam-groups.index');

        Route::get('/examresult', [ExamResultController::class, 'index'])
            ->middleware('permission:examresult,view')
            ->name('exam-results.index');

        Route::get('/examschedule', [ExamScheduleController::class, 'index'])
            ->middleware('permission:examschedule,view')
            ->name('exam-schedules.index');

        Route::get('/gmeet', [GoogleMeetController::class, 'index'])
            ->middleware('permission:gmeet,view')
            ->name('google-meet.index');

        Route::get('/grooming', [GroomingController::class, 'index'])
            ->middleware('permission:grooming,view')
            ->name('grooming.index');

        Route::get('/groomingdomain', [GroomingDomainController::class, 'index'])
            ->middleware('permission:groomingdomain,view')
            ->name('grooming-domains.index');

        Route::get('/groomingparameter', [GroomingParameterController::class, 'index'])
            ->middleware('permission:groomingparameter,view')
            ->name('grooming-parameters.index');

        Route::get('/homework', [HomeworkController::class, 'index'])
            ->middleware('permission:homework,view')
            ->name('homework.index');

        Route::get('/lesson', [LessonController::class, 'index'])
            ->middleware('permission:lesson,view')
            ->name('lessons.index');

        Route::get('/member', [MemberController::class, 'index'])
            ->middleware('permission:member,view')
            ->name('members.index');

        Route::get('/onlineexam', [OnlineExamController::class, 'index'])
            ->middleware('permission:onlineexam,view')
            ->name('online-exams.index');

        Route::get('/papergenerate', [PaperGenerateController::class, 'index'])
            ->middleware('permission:papergenerate,view')
            ->name('paper-generate.index');

        Route::get('/question', [QuestionController::class, 'index'])
            ->middleware('permission:question,view')
            ->name('questions.index');

        Route::get('/subject', [SubjectController::class, 'index'])
            ->middleware('permission:subject,view')
            ->name('subjects.index');
        Route::post('/subject', [SubjectController::class, 'store'])->middleware('permission:subject,add')->name('subjects.store');
        Route::put('/subject/{subject}', [SubjectController::class, 'update'])->middleware('permission:subject,edit')->name('subjects.update');
        Route::delete('/subject/{subject}', [SubjectController::class, 'destroy'])->middleware('permission:subject,delete')->name('subjects.destroy');

        Route::get('/subjectgroup', [SubjectGroupController::class, 'index'])
            ->middleware('permission:subjectgroup,view')
            ->name('subject-groups.index');
        Route::post('/subjectgroup', [SubjectGroupController::class, 'store'])->middleware('permission:subjectgroup,add')->name('subject-groups.store');
        Route::put('/subjectgroup/{subjectGroup}', [SubjectGroupController::class, 'update'])->middleware('permission:subjectgroup,edit')->name('subject-groups.update');
        Route::delete('/subjectgroup/{subjectGroup}', [SubjectGroupController::class, 'destroy'])->middleware('permission:subjectgroup,delete')->name('subject-groups.destroy');

        Route::get('/syllabus', [SyllabusController::class, 'index'])
            ->middleware('permission:syllabus,view')
            ->name('syllabus.index');

        Route::get('/teacher', [TeacherController::class, 'index'])
            ->middleware('permission:teacher,view')
            ->name('teachers.index');

        Route::get('/termsetting', [TermSettingController::class, 'index'])
            ->middleware('permission:termsetting,view')
            ->name('term-settings.index');

        Route::get('/testgroup', [TestGroupController::class, 'index'])
            ->middleware('permission:testgroup,view')
            ->name('test-groups.index');

        Route::get('/testresult', [TestResultController::class, 'index'])
            ->middleware('permission:testresult,view')
            ->name('test-results.index');

        Route::get('/testschedule', [TestScheduleController::class, 'index'])
            ->middleware('permission:testschedule,view')
            ->name('test-schedules.index');

        Route::get('/timetable', [TimetableController::class, 'index'])
            ->middleware('permission:timetable,view')
            ->name('timetables.index');

        Route::get('/topic', [TopicController::class, 'index'])
            ->middleware('permission:topic,view')
            ->name('topics.index');
        Route::post('/topic', [TopicController::class, 'store'])->middleware('permission:topic,add')->name('topics.store');
        Route::put('/topic/{topic}', [TopicController::class, 'update'])->middleware('permission:topic,edit')->name('topics.update');
        Route::delete('/topic/{topic}', [TopicController::class, 'destroy'])->middleware('permission:topic,delete')->name('topics.destroy');

        Route::get('/weeksetting', [WeekSettingController::class, 'index'])
            ->middleware('permission:weeksetting,view')
            ->name('week-settings.index');
    });

Route::prefix('admin/account')
    ->name('admin.account.')
    ->middleware(['auth', 'admin', 'branch', 'financial.year'])
    ->group(function () {
        Route::get('/accounts', [AccountController::class, 'index'])
            ->middleware('permission:accounts,view')
            ->name('accounts.index');

        Route::get('/accounts/dashboard', [AccountController::class, 'dashboard'])
            ->middleware('permission:accounts,view')
            ->name('accounts.dashboard.legacy');

        Route::get('/accounts/newaccounts', [AccountController::class, 'newAccounts'])
            ->middleware('permission:accounts,view')
            ->name('accounts.newaccounts');

        Route::post('/accounts/newaccountscreate', [AccountController::class, 'storeNewAccount'])
            ->middleware('permission:accounts,view')
            ->name('accounts.newaccounts.store');

        Route::get('/accounts/newaccountsedit/{account}', [AccountController::class, 'editNewAccount'])
            ->middleware('permission:accounts,view')
            ->name('accounts.newaccounts.edit');

        Route::post('/accounts/newaccountsedit/{account}', [AccountController::class, 'updateNewAccount'])
            ->middleware('permission:accounts,view')
            ->name('accounts.newaccounts.update');

        Route::get('/accounts/accountshead/{branch?}', [AccountController::class, 'accountsHead'])
            ->middleware('permission:accounts,view')
            ->whereNumber('branch')
            ->name('accounts.accountshead');

        Route::post('/accounts/accountsheadcreate/{branch?}', [AccountController::class, 'storeAccountsHead'])
            ->middleware('permission:accounts,view')
            ->whereNumber('branch')
            ->name('accounts.accountshead.store');

        Route::get('/accounts/accountsheadedit/{account}/{branch?}', [AccountController::class, 'editAccountsHead'])
            ->middleware('permission:accounts,view')
            ->whereNumber('branch')
            ->name('accounts.accountshead.edit');

        Route::post('/accounts/accountsheadedit/{account}/{branch?}', [AccountController::class, 'updateAccountsHead'])
            ->middleware('permission:accounts,view')
            ->whereNumber('branch')
            ->name('accounts.accountshead.update');

        Route::get('/accounts/getBynewaccounts', [AccountController::class, 'getByNewAccounts'])
            ->middleware('permission:accounts,view')
            ->name('accounts.newaccounts.by-head');

        Route::post('/accounts/changestatus', [AccountController::class, 'changeStatus'])
            ->middleware('permission:accounts,view')
            ->name('accounts.change-status');

        Route::post('/accounts/changestatuspost', [AccountController::class, 'changeStatusPost'])
            ->middleware('permission:accounts,view')
            ->name('accounts.change-status-post');

        Route::get('/brands', [BrandController::class, 'index'])
            ->middleware('permission:brands,view')
            ->name('brands.index');

        Route::get('/classbooksets', [ClassBookSetController::class, 'index'])
            ->middleware('permission:classbooksets,view')
            ->name('class-book-sets.index');

        Route::get('/contra', [ContraVoucherController::class, 'index'])
            ->middleware('permission:contra,view')
            ->name('contra.index');

        Route::get('/documentsacc', [AccountDocumentController::class, 'index'])
            ->middleware('permission:documentsacc,view')
            ->name('documents.index');

        Route::get('/expenses', [ExpenseController::class, 'index'])
            ->middleware('permission:expenses,view')
            ->name('expenses.index');

        Route::get('/feemaster/index/{branch_id?}', [FeeMasterController::class, 'index'])
            ->whereNumber('branch_id')
            ->middleware('permission:feemaster,view')
            ->name('fee-master.index.branch');

        Route::get('/feemaster/{branch_id?}', [FeeMasterController::class, 'index'])
            ->whereNumber('branch_id')
            ->middleware('permission:feemaster,view')
            ->name('fee-master.index');

        Route::post('/feemaster/{branch_id?}', [FeeMasterController::class, 'store'])
            ->whereNumber('branch_id')
            ->middleware('permission:feemaster,view')
            ->name('fee-master.store');

        Route::get('/feemaster/edit/{id}/{branch_id?}', [FeeMasterController::class, 'edit'])
            ->whereNumber('id')
            ->middleware('permission:feemaster,view')
            ->name('fee-master.edit');

        Route::match(['post', 'put', 'patch'], '/feemaster/edit/{id}/{branch_id?}', [FeeMasterController::class, 'update'])
            ->whereNumber('id')
            ->middleware('permission:feemaster,view')
            ->name('fee-master.update');

        Route::match(['delete', 'get', 'post'], '/feemaster/deletegrp/{id}/{branch_id?}', [FeeMasterController::class, 'destroy'])
            ->whereNumber('id')
            ->middleware('permission:feemaster,view')
            ->name('fee-master.deletegrp');

        Route::match(['delete', 'get', 'post'], '/feemaster/delete/{id}/{branch_id?}', [FeeMasterController::class, 'destroy'])
            ->whereNumber('id')
            ->middleware('permission:feemaster,view')
            ->name('fee-master.delete');

        Route::get('/feemaster/pdf/{branch_id?}', [FeeMasterController::class, 'downloadPdf'])
            ->whereNumber('branch_id')
            ->middleware('permission:feemaster,view')
            ->name('fee-master.pdf');

        Route::get('/invoicebooksets', [InvoiceBookSetController::class, 'index'])
            ->middleware('permission:invoicebooksets,view')
            ->name('invoice-book-sets.index');

        Route::get('/invoicebooksetreturns', [InvoiceBookSetReturnController::class, 'index'])
            ->middleware('permission:invoicebooksetreturns,view')
            ->name('invoice-book-set-returns.index');

        Route::get('/itemcategory', [ItemCategoryController::class, 'index'])
            ->middleware('permission:itemcategory,view')
            ->name('item-categories.index');

        Route::get('/journalvoucher', [JournalVoucherController::class, 'index'])
            ->middleware('permission:journalvoucher,view')
            ->name('journal-vouchers.index');

        Route::get('/payments', [PaymentVoucherController::class, 'index'])
            ->middleware('permission:payments,view')
            ->name('payments.index');

        Route::get('/payroll', [PayrollController::class, 'index'])
            ->middleware('permission:payroll,view')
            ->name('payroll.index');

        Route::get('/products', [ProductController::class, 'index'])
            ->middleware('permission:products,view')
            ->name('products.index');

        Route::get('/producttype', [ProductTypeController::class, 'index'])
            ->middleware('permission:producttype,view')
            ->name('product-types.index');

        Route::get('/purchases', [PurchaseController::class, 'index'])
            ->middleware('permission:purchases,view')
            ->name('purchases.index');

        Route::get('/purchasereturns', [PurchaseReturnController::class, 'index'])
            ->middleware('permission:purchasereturns,view')
            ->name('purchase-returns.index');

        Route::get('/receipts', [ReceiptVoucherController::class, 'index'])
            ->middleware('permission:receipts,view')
            ->name('receipts.index');

        Route::get('/royalty', [RoyaltyController::class, 'index'])
            ->middleware('permission:royalty,view')
            ->name('royalty.index');

        Route::get('/sales', [SaleController::class, 'index'])
            ->middleware('permission:sales,view')
            ->name('sales.index');

        Route::get('/salesreturns', [SaleReturnController::class, 'index'])
            ->middleware('permission:salesreturns,view')
            ->name('sales-returns.index');

        Route::get('/stock', [StockController::class, 'index'])
            ->middleware('permission:stock,view')
            ->name('stock.index');

        Route::get('/studentfee', [StudentFeeController::class, 'index'])
            ->middleware('permission:studentfee,view')
            ->name('student-fees.index');

        Route::match(['get', 'post'], '/studentfee/feerevise/{branch_id?}', [StudentFeeController::class, 'feerevise'])
            ->middleware('permission:studentfee,view')
            ->name('studentfee.feerevise');

        Route::post('/studentfee/feereviseUpdate', [StudentFeeController::class, 'feereviseUpdate'])
            ->middleware('permission:studentfee,view')
            ->name('studentfee.feereviseUpdate');

        Route::get('/studentfee/get-sections/{class_id}', [StudentFeeController::class, 'getSectionsByClass'])
            ->middleware('permission:studentfee,view')
            ->name('studentfee.getSectionsByClass');

        Route::match(['get', 'post'], '/studentfee/assigndues/{branch_id?}', [StudentFeeController::class, 'assigndues'])
            ->middleware('permission:studentfee,view')
            ->name('studentfee.assigndues');

        Route::post('/studentfee/getStudentByBranch', [StudentFeeController::class, 'getStudentByBranch'])
            ->middleware('permission:studentfee,view')
            ->name('studentfee.getStudentByBranch');

        Route::post('/studentfee/getClassesByBranch', [StudentFeeController::class, 'getClassesByBranch'])
            ->middleware('permission:studentfee,view')
            ->name('studentfee.getClassesByBranch');

        Route::post('/studentfee/getClassesSectionsByBranch', [StudentFeeController::class, 'getClassesSectionsByBranch'])
            ->middleware('permission:studentfee,view')
            ->name('studentfee.getClassesSectionsByBranch');

        Route::post('/studentfee/getStudentClassSectionsByBranch', [StudentFeeController::class, 'getStudentClassSectionsByBranch'])
            ->middleware('permission:studentfee,view')
            ->name('studentfee.getStudentClassSectionsByBranch');

        Route::post('/studentfee/getstdByBrcIDByAdmitNo', [StudentFeeController::class, 'getstdByBrcIDByAdmitNo'])
            ->middleware('permission:studentfee,view')
            ->name('studentfee.getstdByBrcIDByAdmitNo');

        Route::post('/studentfee/getFeeTypeByBranchID', [StudentFeeController::class, 'getFeeTypeByBranchID'])
            ->middleware('permission:studentfee,view')
            ->name('studentfee.getFeeTypeByBranchID');

        Route::post('/studentfee/addDues', [StudentFeeController::class, 'addDues'])
            ->middleware('permission:studentfee,view')
            ->name('studentfee.addDues');

        Route::match(['get', 'post'], '/studentfee/assignfeevoucher/{branch_id?}', [StudentFeeController::class, 'assignfeevoucher'])
            ->middleware('permission:studentfee,view')
            ->name('studentfee.assignfeevoucher');

        Route::post('/studentfee/revertfeevoucher', [StudentFeeController::class, 'revertfeevoucher'])
            ->middleware('permission:studentfee,view')
            ->name('studentfee.revertfeevoucher');

        Route::get('/studentfee/printfeevoucher', [StudentFeeController::class, 'printfeevoucher'])
            ->middleware('permission:studentfee,view')
            ->name('studentfee.printfeevoucher');

        Route::match(['get', 'post'], '/studentfee/assignfeevoucherdatewise/{branch_id?}', [StudentFeeController::class, 'assignfeevoucherdatewise'])
            ->middleware('permission:studentfee,view')
            ->name('studentfee.assignfeevoucherdatewise');

        Route::match(['get', 'post'], '/studentfee/feevoucherstudentsibling/{branch_id?}/{tab?}', [StudentFeeController::class, 'feevoucherstudentsibling'])
            ->middleware('permission:studentfee,view')
            ->name('studentfee.feevoucherstudentsibling');

        Route::match(['get', 'post'], '/studentfee/feevoucher/{branch_id?}', [StudentFeeController::class, 'feevoucher'])
            ->middleware('permission:studentfee,view')
            ->name('studentfee.feevoucher');

        Route::match(['get', 'post'], '/studentfee/customfeevoucher/{branch_id?}', [StudentFeeController::class, 'customfeevoucher'])
            ->middleware('permission:studentfee,view')
            ->name('studentfee.customfeevoucher');

        Route::get('/studentfee/getStudentFeeSummary', [StudentFeeController::class, 'getStudentFeeSummary'])
            ->middleware('permission:studentfee,view')
            ->name('studentfee.getStudentFeeSummary');

        Route::get('/studentfee/getSiblingFeeSummary', [StudentFeeController::class, 'getSiblingFeeSummary'])
            ->middleware('permission:studentfee,view')
            ->name('studentfee.getSiblingFeeSummary');

        Route::get('/studentfee/get-sections/{class_id}', function ($classId) {
            $classId = (int) $classId;
            $sections = collect();

            if (\Illuminate\Support\Facades\Schema::hasTable('class_sections')) {
                $sections = \Illuminate\Support\Facades\DB::table('class_sections')
                    ->join('sections', 'sections.id', '=', 'class_sections.section_id')
                    ->where('class_sections.class_id', $classId)
                    ->select('sections.id as section_id', 'sections.id', 'sections.section', 'sections.section as name')
                    ->orderBy('sections.section', 'asc')
                    ->get();
            }

            if ($sections->isEmpty() && \Illuminate\Support\Facades\Schema::hasTable('student_session')) {
                $sections = \Illuminate\Support\Facades\DB::table('student_session')
                    ->join('sections', 'sections.id', '=', 'student_session.section_id')
                    ->where('student_session.class_id', $classId)
                    ->select('sections.id as section_id', 'sections.id', 'sections.section', 'sections.section as name')
                    ->distinct()
                    ->orderBy('sections.section', 'asc')
                    ->get();
            }

            if ($sections->isEmpty() && \Illuminate\Support\Facades\Schema::hasTable('sections')) {
                $sections = \Illuminate\Support\Facades\DB::table('sections')
                    ->select('sections.id as section_id', 'sections.id', 'sections.section', 'sections.section as name')
                    ->orderBy('sections.section', 'asc')
                    ->get();
            }

            return response()->json($sections);
        });

        Route::get('/setting/sections/getByClass', function (\Illuminate\Http\Request $request) {
            $classId = (int) $request->input('class_id');
            $sections = collect();

            if (\Illuminate\Support\Facades\Schema::hasTable('class_sections')) {
                $sections = \Illuminate\Support\Facades\DB::table('class_sections')
                    ->join('sections', 'sections.id', '=', 'class_sections.section_id')
                    ->where('class_sections.class_id', $classId)
                    ->select('sections.id as section_id', 'sections.id', 'sections.section', 'sections.section as name')
                    ->orderBy('sections.section', 'asc')
                    ->get();
            }

            if ($sections->isEmpty() && \Illuminate\Support\Facades\Schema::hasTable('student_session')) {
                $sections = \Illuminate\Support\Facades\DB::table('student_session')
                    ->join('sections', 'sections.id', '=', 'student_session.section_id')
                    ->where('student_session.class_id', $classId)
                    ->select('sections.id as section_id', 'sections.id', 'sections.section', 'sections.section as name')
                    ->distinct()
                    ->orderBy('sections.section', 'asc')
                    ->get();
            }

            if ($sections->isEmpty() && \Illuminate\Support\Facades\Schema::hasTable('sections')) {
                $sections = \Illuminate\Support\Facades\DB::table('sections')
                    ->select('sections.id as section_id', 'sections.id', 'sections.section', 'sections.section as name')
                    ->orderBy('sections.section', 'asc')
                    ->get();
            }

            return response()->json($sections);
        });

        Route::get('/supplier', [SupplierController::class, 'index'])
            ->middleware('permission:supplier,view')
            ->name('suppliers.index');

        Route::get('/units', [UnitController::class, 'index'])
            ->middleware('permission:units,view')
            ->name('units.index');
    });

Route::prefix('admin/adm')
    ->name('admin.adm.')
    ->middleware(['auth', 'admin', 'branch'])
    ->group(function () {
        Route::get('/admn', [AdmDashboardController::class, 'index'])
            ->middleware('permission:admn,view')
            ->name('dashboard');

        Route::get('/admn/dashboard', [AdmDashboardController::class, 'index'])
            ->middleware('permission:admn,view')
            ->name('dashboard.legacy');

        Route::get('/achievement', [AdmAchievementController::class, 'index'])
            ->middleware('permission:achievement,view')
            ->name('achievements.index');

        Route::get('/approve_leave', [LeaveApprovalController::class, 'index'])
            ->middleware('permission:approve_leave,view')
            ->name('leave-approvals.index');

        Route::get('/attendance', [AttendanceController::class, 'index'])
            ->middleware('permission:attendance,view')
            ->name('attendance.index');

        Route::get('/calendar', [CalendarController::class, 'index'])
            ->middleware('permission:calendar,view')
            ->name('calendar.index');

        Route::get('/chat', [ChatController::class, 'index'])
            ->middleware('permission:chat,view')
            ->name('chat.index');

        Route::get('/complaint', [ComplaintController::class, 'index'])
            ->middleware('permission:complaint,view')
            ->name('complaints.index');

        Route::get('/complaintregarding', [ComplaintRegardingController::class, 'index'])
            ->middleware('permission:complaintregarding,view')
            ->name('complaint-regardings.index');

        Route::get('/complaintsource', [ComplaintSourceController::class, 'index'])
            ->middleware('permission:complaintsource,view')
            ->name('complaint-sources.index');

        Route::get('/complainttype', [ComplaintTypeController::class, 'index'])
            ->middleware('permission:complainttype,view')
            ->name('complaint-types.index');

        Route::get('/content', [ContentController::class, 'index'])
            ->middleware('permission:content,view')
            ->name('content.index');

        Route::get('/contenttype', [ContentTypeController::class, 'index'])
            ->middleware('permission:contenttype,view')
            ->name('content-types.index');

        Route::get('/dispatch', [DispatchController::class, 'index'])
            ->middleware('permission:dispatch,view')
            ->name('dispatch.index');

        Route::get('/documentsadm', [AdmDocumentController::class, 'index'])
            ->middleware('permission:documentsadm,view')
            ->name('documents.index');

        Route::get('/enquiry', [EnquiryController::class, 'index'])
            ->middleware('permission:enquiry,view')
            ->name('enquiries.index');

        Route::get('/generalcall', [GeneralCallController::class, 'index'])
            ->middleware('permission:generalcall,view')
            ->name('general-calls.index');

        Route::get('/generalremarks', [GeneralRemarkController::class, 'index'])
            ->middleware('permission:generalremarks,view')
            ->name('general-remarks.index');

        Route::get('/generateidcard', [IdCardGeneratorController::class, 'index'])
            ->middleware('permission:generateidcard,view')
            ->name('id-card-generator.index');

        Route::get('/generatestaffidcard', [StaffIdCardGeneratorController::class, 'index'])
            ->middleware('permission:generatestaffidcard,view')
            ->name('staff-id-card-generator.index');

        Route::get('/leaverequest', [LeaveRequestController::class, 'index'])
            ->middleware('permission:leaverequest,view')
            ->name('leave-requests.index');

        Route::get('/mailsms', [MailSmsController::class, 'index'])
            ->middleware('permission:mailsms,view')
            ->name('mail-sms.index');

        Route::get('/notification', [NotificationController::class, 'index'])
            ->middleware('permission:notification,view')
            ->name('notifications.index');

        Route::get('/receive', [ReceiveController::class, 'index'])
            ->middleware('permission:receive,view')
            ->name('receive.index');

        Route::get('/reference', [ReferenceController::class, 'index'])
            ->middleware('permission:reference,view')
            ->name('references.index');

        Route::get('/siblings', [SiblingController::class, 'index'])
            ->middleware('permission:siblings,view')
            ->name('siblings.index');

        Route::get('/source', [SourceController::class, 'index'])
            ->middleware('permission:source,view')
            ->name('sources.index');

        Route::get('/staffattendance', [StaffAttendanceController::class, 'index'])
            ->middleware('permission:staffattendance,view')
            ->name('staff-attendance.index');

        Route::get('/staffidcard', [StaffIdCardController::class, 'index'])
            ->middleware('permission:staffidcard,view')
            ->name('staff-id-cards.index');

        Route::get('/stdtransferclasssection', [StudentTransferController::class, 'index'])
            ->middleware('permission:stdtransferclasssection,view')
            ->name('student-transfers.index');

        Route::get('/stuattendence', [StudentAttendanceController::class, 'index'])
            ->middleware('permission:stuattendence,view')
            ->name('student-attendance.index');

        Route::get('/student', [StudentController::class, 'index'])
            ->middleware('permission:student,view')
            ->name('students.index');

        Route::get('/student_regd', [StudentRegistrationController::class, 'index'])
            ->middleware('permission:student_regd,view')
            ->name('student-registrations.index');

        Route::get('/studentidcard', [StudentIdCardController::class, 'index'])
            ->middleware('permission:studentidcard,view')
            ->name('student-id-cards.index');

        Route::get('/subjectattendence', [SubjectAttendanceController::class, 'index'])
            ->middleware('permission:subjectattendence,view')
            ->name('subject-attendance.index');

        Route::get('/videotutorial', [VideoTutorialController::class, 'index'])
            ->middleware('permission:videotutorial,view')
            ->name('video-tutorials.index');

        Route::get('/visitorspurpose', [VisitorPurposeController::class, 'index'])
            ->middleware('permission:visitorspurpose,view')
            ->name('visitor-purposes.index');
    });

Route::prefix('admin/front')
    ->name('admin.front.')
    ->middleware(['auth', 'admin', 'branch'])
    ->group(function () {
        Route::get('/banner', [BannerController::class, 'index'])
            ->middleware('permission:banner,view')
            ->name('banners.index');

        Route::get('/events', [EventController::class, 'index'])
            ->middleware('permission:events,view')
            ->name('events.index');

        Route::get('/gallery', [FrontGalleryController::class, 'index'])
            ->middleware('permission:gallery,view')
            ->name('galleries.index');

        Route::get('/media', [MediaController::class, 'index'])
            ->middleware('permission:media,view')
            ->name('media.index');

        Route::get('/menus', [MenuController::class, 'index'])
            ->middleware('permission:menus,view')
            ->name('menus.index');

        Route::get('/notice', [NoticeController::class, 'index'])
            ->middleware('permission:notice,view')
            ->name('notices.index');

        Route::get('/page', [FrontPageController::class, 'index'])
            ->middleware('permission:page,view')
            ->name('pages.index');
    });

Route::prefix('admin/hrms')
    ->name('admin.hrms.')
    ->middleware(['auth', 'admin', 'branch'])
    ->group(function () {
        Route::get('/hrm', [HrmsDashboardController::class, 'index'])
            ->middleware('permission:hrm,view')
            ->name('dashboard');

        Route::get('/hrm/dashboard', [HrmsDashboardController::class, 'index'])
            ->middleware('permission:hrm,view')
            ->name('dashboard.legacy');

        Route::get('/trainingagenda', [TrainingAgendaController::class, 'index'])
            ->middleware('permission:training_agenda,view')->name('training.agenda.index');
        Route::post('/trainingagenda', [TrainingAgendaController::class, 'store'])
            ->middleware('permission:training_agenda,edit')->name('training.agenda.store');
        Route::get('/trainingagenda/{trainingAgenda}/edit', [TrainingAgendaController::class, 'edit'])
            ->middleware('permission:training_agenda,edit')->name('training.agenda.edit');
        Route::put('/trainingagenda/{trainingAgenda}', [TrainingAgendaController::class, 'update'])
            ->middleware('permission:training_agenda,edit')->name('training.agenda.update');
        Route::get('/trainingagenda/{trainingAgenda}', [TrainingAgendaController::class, 'show'])
            ->middleware('permission:training_agenda,view')->name('training.agenda.show');
        Route::delete('/trainingagenda/{trainingAgenda}', [TrainingAgendaController::class, 'destroy'])
            ->middleware('permission:training_agenda,delete')->name('training.agenda.destroy');

        Route::get('/trainingneedanalysis', [TrainingNeedAnalysisController::class, 'index'])
            ->middleware('permission:training_analysis,view')->name('training.analysis.index');
        Route::post('/trainingneedanalysis', [TrainingNeedAnalysisController::class, 'store'])
            ->middleware('permission:training_analysis,edit')->name('training.analysis.store');
        Route::get('/trainingneedanalysis/{trainingNeedAnalysis}/edit', [TrainingNeedAnalysisController::class, 'edit'])
            ->middleware('permission:training_analysis,edit')->name('training.analysis.edit');
        Route::put('/trainingneedanalysis/{trainingNeedAnalysis}', [TrainingNeedAnalysisController::class, 'update'])
            ->middleware('permission:training_analysis,edit')->name('training.analysis.update');
        Route::get('/trainingneedanalysis/{trainingNeedAnalysis}', [TrainingNeedAnalysisController::class, 'show'])
            ->middleware('permission:training_analysis,view')->name('training.analysis.show');
        Route::delete('/trainingneedanalysis/{trainingNeedAnalysis}', [TrainingNeedAnalysisController::class, 'destroy'])
            ->middleware('permission:training_analysis,delete')->name('training.analysis.destroy');

        Route::get('/trainingevaluationform', [TrainingEvaluationController::class, 'index'])
            ->middleware('permission:training_evaluation,view')->name('training.evaluation.index');
        Route::post('/trainingevaluationform', [TrainingEvaluationController::class, 'store'])
            ->middleware('permission:training_evaluation,edit')->name('training.evaluation.store');
        Route::get('/trainingevaluationform/{trainingEvaluation}/edit', [TrainingEvaluationController::class, 'edit'])
            ->middleware('permission:training_evaluation,edit')->name('training.evaluation.edit');
        Route::put('/trainingevaluationform/{trainingEvaluation}', [TrainingEvaluationController::class, 'update'])
            ->middleware('permission:training_evaluation,edit')->name('training.evaluation.update');
        Route::get('/trainingevaluationform/{trainingEvaluation}', [TrainingEvaluationController::class, 'show'])
            ->middleware('permission:training_evaluation,view')->name('training.evaluation.show');
        Route::delete('/trainingevaluationform/{trainingEvaluation}', [TrainingEvaluationController::class, 'destroy'])
            ->middleware('permission:training_evaluation,delete')->name('training.evaluation.destroy');

        Route::get('/documentshrm', [HrDocumentController::class, 'index'])
            ->middleware('permission:documentshrm,view')
            ->name('documents.index');

        Route::get('/manualhrm', [HrManualController::class, 'index'])
            ->middleware('permission:manualhrm,view')
            ->name('manual.index');

        Route::get('/staff', [HrmsStaffController::class, 'index'])
            ->middleware('permission:staff,view')
            ->name('staff.index');

        Route::get('/staffdisabledirectory', [StaffDisableDirectoryController::class, 'index'])
            ->middleware('permission:staff,view')
            ->name('staff-disable-directory.index');
        Route::post('/staffdisabledirectory/{staff}/enable', [StaffDisableDirectoryController::class, 'enable'])
            ->middleware('permission:staff,edit')
            ->whereNumber('staff')
            ->name('staff-disable-directory.enable');

        Route::match(['get', 'post'], '/staff/import', [HrmsStaffController::class, 'import'])
            ->middleware('permission:staff,edit')
            ->name('staff.import');

        Route::get('/staffdemand', [StaffDemandController::class, 'index'])
            ->middleware('permission:staff_demand,view')->name('staffdemand.index');
        Route::post('/staffdemand', [StaffDemandController::class, 'store'])
            ->middleware('permission:staff_demand,edit')->name('staffdemand.store');
        Route::get('/staffdemand/{staffDemand}/edit', [StaffDemandController::class, 'edit'])
            ->middleware('permission:staff_demand,edit')->name('staffdemand.edit');
        Route::put('/staffdemand/{staffDemand}', [StaffDemandController::class, 'update'])
            ->middleware('permission:staff_demand,edit')->name('staffdemand.update');
        Route::get('/staffdemand/{staffDemand}', [StaffDemandController::class, 'show'])
            ->middleware('permission:staff_demand,view')->name('staffdemand.show');
        Route::delete('/staffdemand/{staffDemand}', [StaffDemandController::class, 'destroy'])
            ->middleware('permission:staff_demand,delete')->name('staffdemand.destroy');
        Route::post('/staffdemand/staff-by-campus', [StaffDemandController::class, 'staffByCampus'])
            ->middleware('permission:staff_demand,view')->name('staffdemand.staff-by-campus');

        Route::get('/jobadvertisements', [JobAdvertisementController::class, 'index'])
            ->middleware('permission:job_advertisements,view')->name('jobadvertisements.index');
        Route::post('/jobadvertisements', [JobAdvertisementController::class, 'store'])
            ->middleware('permission:job_advertisements,edit')->name('jobadvertisements.store');
        Route::get('/jobadvertisements/{jobAdvertisement}/edit', [JobAdvertisementController::class, 'edit'])
            ->middleware('permission:job_advertisements,edit')->name('jobadvertisements.edit');
        Route::put('/jobadvertisements/{jobAdvertisement}', [JobAdvertisementController::class, 'update'])
            ->middleware('permission:job_advertisements,edit')->name('jobadvertisements.update');
        Route::get('/jobadvertisements/{jobAdvertisement}', [JobAdvertisementController::class, 'show'])
            ->middleware('permission:job_advertisements,view')->name('jobadvertisements.show');
        Route::delete('/jobadvertisements/{jobAdvertisement}', [JobAdvertisementController::class, 'destroy'])
            ->middleware('permission:job_advertisements,delete')->name('jobadvertisements.destroy');
        Route::post('/jobadvertisements/salary-range', [JobAdvertisementController::class, 'salaryRange'])
            ->middleware('permission:job_advertisements,view')->name('jobadvertisements.salary-range');
        Route::post('/jobadvertisements/{jobAdvertisement}/status', [JobAdvertisementController::class, 'updateStatus'])
            ->middleware('permission:job_advertisements,edit')->name('jobadvertisements.status');
        Route::get('/jobadvertisements/{jobAdvertisement}/print', [JobAdvertisementController::class, 'print'])
            ->middleware('permission:job_advertisements,view')->name('jobadvertisements.print');

        Route::get('/jobapplications', [JobApplicationController::class, 'index'])
            ->middleware('permission:job_applications,view')->name('jobapplications.index');
        Route::post('/jobapplications', [JobApplicationController::class, 'store'])
            ->middleware('permission:job_applications,edit')->name('jobapplications.store');
        Route::get('/jobapplications/{jobApplication}/edit', [JobApplicationController::class, 'edit'])
            ->middleware('permission:job_applications,edit')->name('jobapplications.edit');
        Route::put('/jobapplications/{jobApplication}', [JobApplicationController::class, 'update'])
            ->middleware('permission:job_applications,edit')->name('jobapplications.update');
        Route::get('/jobapplications/{jobApplication}', [JobApplicationController::class, 'show'])
            ->middleware('permission:job_applications,view')->name('jobapplications.show');
        Route::delete('/jobapplications/{jobApplication}', [JobApplicationController::class, 'destroy'])
            ->middleware('permission:job_applications,delete')->name('jobapplications.destroy');
        Route::post('/jobapplications/job-details', [JobApplicationController::class, 'jobDetails'])
            ->middleware('permission:job_applications,view')->name('jobapplications.job-details');
        Route::post('/jobapplications/{jobApplication}/status', [JobApplicationController::class, 'updateStatus'])
            ->middleware('permission:job_applications,edit')->name('jobapplications.status');
        Route::get('/jobapplications/{jobApplication}/print', [JobApplicationController::class, 'print'])
            ->middleware('permission:job_applications,view')->name('jobapplications.print');

        Route::get('/Writtentest', [WrittenTestController::class, 'index'])
            ->middleware('permission:written_test,view')->name('writtentest.index');
        Route::post('/Writtentest/{jobApplication}/update-marks', [WrittenTestController::class, 'updateMarks'])
            ->middleware('permission:written_test,edit')->name('writtentest.update-marks');

        Route::get('/interviewratings', [InterviewRatingController::class, 'index'])
            ->middleware('permission:interview_ratings,view')->name('interviewratings.index');
        Route::post('/interviewratings', [InterviewRatingController::class, 'store'])
            ->middleware('permission:interview_ratings,edit')->name('interviewratings.store');
        Route::get('/interviewratings/{interviewRating}/edit', [InterviewRatingController::class, 'edit'])
            ->middleware('permission:interview_ratings,edit')->name('interviewratings.edit');
        Route::put('/interviewratings/{interviewRating}', [InterviewRatingController::class, 'update'])
            ->middleware('permission:interview_ratings,edit')->name('interviewratings.update');
        Route::get('/interviewratings/{interviewRating}', [InterviewRatingController::class, 'show'])
            ->middleware('permission:interview_ratings,view')->name('interviewratings.show');
        Route::delete('/interviewratings/{interviewRating}', [InterviewRatingController::class, 'destroy'])
            ->middleware('permission:interview_ratings,delete')->name('interviewratings.destroy');
        Route::post('/interviewratings/{interviewRating}/decision', [InterviewRatingController::class, 'updateDecision'])
            ->middleware('permission:interview_ratings,edit')->name('interviewratings.decision');

        Route::get('/meritlist', [MeritListController::class, 'index'])
            ->middleware('permission:merit_list,view')->name('meritlist.index');

        Route::get('/joboffers', [JobOfferController::class, 'index'])
            ->middleware('permission:job_offer_letters,view')->name('joboffers.index');
        Route::post('/joboffers', [JobOfferController::class, 'store'])
            ->middleware('permission:job_offer_letters,edit')->name('joboffers.store');
        Route::get('/joboffers/{jobOffer}/edit', [JobOfferController::class, 'edit'])
            ->middleware('permission:job_offer_letters,edit')->name('joboffers.edit');
        Route::put('/joboffers/{jobOffer}', [JobOfferController::class, 'update'])
            ->middleware('permission:job_offer_letters,edit')->name('joboffers.update');
        Route::get('/joboffers/{jobOffer}', [JobOfferController::class, 'show'])
            ->middleware('permission:job_offer_letters,view')->name('joboffers.show');
        Route::delete('/joboffers/{jobOffer}', [JobOfferController::class, 'destroy'])
            ->middleware('permission:job_offer_letters,delete')->name('joboffers.destroy');
        Route::get('/joboffers/{jobOffer}/print', [JobOfferController::class, 'print'])
            ->middleware('permission:job_offer_letters,view')->name('joboffers.print');

        Route::get('/Staffrecruitmentorders', [StaffRecruitmentOrderController::class, 'index'])
            ->middleware('permission:Staff_recruitment_orders,view')->name('staffrecruitmentorders.index');
        Route::post('/Staffrecruitmentorders', [StaffRecruitmentOrderController::class, 'store'])
            ->middleware('permission:Staff_recruitment_orders,edit')->name('staffrecruitmentorders.store');
        Route::get('/Staffrecruitmentorders/{staffRecruitmentOrder}/edit', [StaffRecruitmentOrderController::class, 'edit'])
            ->middleware('permission:Staff_recruitment_orders,edit')->name('staffrecruitmentorders.edit');
        Route::put('/Staffrecruitmentorders/{staffRecruitmentOrder}', [StaffRecruitmentOrderController::class, 'update'])
            ->middleware('permission:Staff_recruitment_orders,edit')->name('staffrecruitmentorders.update');
        Route::get('/Staffrecruitmentorders/{staffRecruitmentOrder}', [StaffRecruitmentOrderController::class, 'show'])
            ->middleware('permission:Staff_recruitment_orders,view')->name('staffrecruitmentorders.show');
        Route::delete('/Staffrecruitmentorders/{staffRecruitmentOrder}', [StaffRecruitmentOrderController::class, 'destroy'])
            ->middleware('permission:Staff_recruitment_orders,delete')->name('staffrecruitmentorders.destroy');

        foreach ([
            'schoolperformancereports'=>['school-performance','school_performance'],
            'monthlyappraisalteaching'=>['monthly-teacher','monthly_teacher'],
            'monthlyappraisalmanagement'=>['monthly-management','monthly_management'],
            'annualconfidentialreport'=>['annual-teacher','annual_teacher'],
            'annualconfidentialreportmanagement'=>['annual-management','annual_management'],
            'nonconferencenoticereply'=>['notice-reply','notice_reply'],
            'clearanceform'=>['clearance','clearance'],
            'exitinterview'=>['exit-interview','exit_interview'],
            'finalsettlement'=>['final-settlement','final_settlement'],
            'showcausenotice'=>['show-cause','show_cause'],
            'inquiryprocess'=>['inquiry','inquiry'],
        ] as $path=>$route) {
            [$name,$type]=$route;
            Route::get('/'.$path, [PerformanceAndComplianceController::class, 'index'])->defaults('type',$type)->middleware('permission:hrm,view')->name($name.'.index');
            Route::post('/'.$path, [PerformanceAndComplianceController::class, 'store'])->defaults('type',$type)->middleware('permission:hrm,edit')->name($name.'.store');
            Route::get('/'.$path.'/{record}/edit', [PerformanceAndComplianceController::class, 'edit'])->defaults('type',$type)->middleware('permission:hrm,edit')->name($name.'.edit');
            Route::put('/'.$path.'/{record}', [PerformanceAndComplianceController::class, 'update'])->defaults('type',$type)->middleware('permission:hrm,edit')->name($name.'.update');
            Route::get('/'.$path.'/{record}', [PerformanceAndComplianceController::class, 'show'])->defaults('type',$type)->middleware('permission:hrm,view')->name($name.'.show');
            Route::delete('/'.$path.'/{record}', [PerformanceAndComplianceController::class, 'destroy'])->defaults('type',$type)->middleware('permission:hrm,delete')->name($name.'.destroy');
        }

        Route::get('/staff/create/{branchId?}', [HrmsStaffController::class, 'create'])
            ->middleware('permission:staff,edit')
            ->name('staff.create');

        Route::post('/staff/create/{branchId?}', [HrmsStaffController::class, 'store'])
            ->middleware('permission:staff,edit')
            ->name('staff.store');

        Route::post('/staff/options/{type}', [HrmsStaffController::class, 'storeOption'])
            ->middleware('permission:staff,edit')
            ->name('staff.options.store');

        Route::get('/staff/profile/{staffId}', [HrmsStaffController::class, 'profile'])
            ->middleware('permission:staff,view')
            ->name('staff.profile');

        Route::get('/staff/edit/{staffId}', [HrmsStaffController::class, 'edit'])
            ->middleware('permission:staff,edit')
            ->name('staff.edit');

        Route::put('/staff/{staffId}', [HrmsStaffController::class, 'update'])
            ->middleware('permission:staff,edit')
            ->name('staff.update');

        Route::get('/staff/appointment-form/{staffId}', [HrmsStaffController::class, 'appointmentForm'])
            ->middleware('permission:staff,view')
            ->name('staff.appointment-form');

        Route::get('/staff/service-experience-certificate/{staffId}', [HrmsStaffController::class, 'serviceExperienceCertificate'])
            ->middleware('permission:staff,view')
            ->name('staff.service-experience-certificate');
    });

/*
|--------------------------------------------------------------------------
| CodeIgniter-Compatible Teacher Module Routes
|--------------------------------------------------------------------------
|
| These routes keep the original /teacher URLs intact while moving request
| handling to dedicated Laravel controllers and teacher-scoped services.
|
*/
Route::prefix('teacher')
    ->name('teacher.')
    ->middleware(['auth', 'teacher', 'branch'])
    ->group(function () {
        Route::controller(TeacherDashboardController::class)->group(function () {
            Route::get('/teacher/dashboard', 'index')->name('dashboard');
        });

        Route::controller(TeacherProfileController::class)->group(function () {
            Route::get('/teacher/profile/{id}', 'show')->whereNumber('id')->name('profile.show');
        });

        Route::controller(TeacherPasswordController::class)->group(function () {
            Route::get('/teacher/changepass', 'edit')->name('password.edit');
            Route::post('/teacher/changepass', 'update')->name('password.update');
        });

        Route::controller(TeacherLeaveApprovalController::class)->group(function () {
            Route::get('/approve_leave', 'index')->name('leave-approvals.index');
        });

        Route::controller(TeacherConferenceController::class)->group(function () {
            Route::get('/conference', 'index')->name('conferences.index');
        });

        Route::controller(TeacherExamResultController::class)->group(function () {
            Route::get('/examresult', 'index')->name('exam-results.index');
        });

        Route::controller(TeacherExamScheduleController::class)->group(function () {
            Route::get('/examschedule', 'index')->name('exam-schedules.index');
        });

        Route::controller(TeacherGoogleMeetController::class)->group(function () {
            Route::get('/gmeet', 'index')->name('google-meet.index');
        });

        Route::controller(TeacherGroomingController::class)->group(function () {
            Route::get('/grooming', 'index')->name('grooming.index');
        });

        Route::controller(TeacherHomeworkController::class)->group(function () {
            Route::get('/homework', 'index')->name('homework.index');
        });

        Route::controller(TeacherLessonController::class)->group(function () {
            Route::get('/lesson', 'index')->name('lessons.index');
        });

        Route::controller(TeacherStudentAttendanceController::class)->group(function () {
            Route::get('/stuattendence', 'index')->name('student-attendance.index');
        });

        Route::controller(TeacherSyllabusController::class)->group(function () {
            Route::get('/syllabus', 'index')->name('syllabus.index');
        });

        Route::controller(TeacherTermSettingController::class)->group(function () {
            Route::get('/termsetting', 'index')->name('term-settings.index');
        });

        Route::controller(TeacherTestResultController::class)->group(function () {
            Route::get('/testresult', 'index')->name('test-results.index');
        });

        Route::controller(TeacherTestScheduleController::class)->group(function () {
            Route::get('/testschedule', 'index')->name('test-schedules.index');
        });

        Route::controller(TeacherTimetableController::class)->group(function () {
            Route::get('/timetable', 'index')->name('timetable.index');
        });
    });

/*
|--------------------------------------------------------------------------
| CodeIgniter-Compatible Student / Parent User Routes
|--------------------------------------------------------------------------
|
| These routes preserve the legacy /user URL contract while the controllers
| resolve the authenticated student or parent context on the server.
|
*/
Route::prefix('user')
    ->name('user.')
    ->middleware(['auth:site', 'student.parent'])
    ->group(function () {
        Route::controller(UserDashboardController::class)->group(function () {
            Route::get('/user/dashboard', 'index')->name('dashboard');
        });

        Route::controller(UserProfileController::class)->group(function () {
            Route::get('/user/profile', 'show')->name('profile.show');
        });

        Route::controller(UserFeeController::class)->group(function () {
            Route::get('/user/getfees', 'index')->name('fees.index');
        });

        Route::controller(UserPasswordController::class)->group(function () {
            Route::get('/user/changepass', 'edit')->name('password.edit');
            Route::post('/user/changepass', 'update')->name('password.update');
        });

        Route::controller(UserUsernameController::class)->group(function () {
            Route::get('/user/changeusername', 'edit')->name('username.edit');
            Route::post('/user/changeusername', 'update')->name('username.update');
        });

        Route::controller(UserLeaveRequestController::class)->group(function () {
            Route::get('/apply_leave', 'index')->name('leave-requests.index');
        });

        Route::controller(UserAttendanceController::class)->group(function () {
            Route::get('/attendence', 'index')->name('attendance.index');
        });

        Route::controller(UserBookController::class)->group(function () {
            Route::get('/book', 'index')->name('books.index');
        });

        Route::controller(UserConferenceController::class)->group(function () {
            Route::get('/conference', 'index')->name('conferences.index');
        });

        Route::controller(UserContentController::class)->group(function () {
            Route::get('/content', 'index')->name('content.index');
        });

        Route::controller(UserGoogleMeetController::class)->group(function () {
            Route::get('/gmeet', 'index')->name('google-meet.index');
        });

        Route::controller(UserVideoTutorialController::class)->group(function () {
            Route::get('/video_tutorial', 'index')->name('video-tutorials.index');
        });
    });

/*
|--------------------------------------------------------------------------
| CodeIgniter-Compatible Cron Routes
|--------------------------------------------------------------------------
|
| Specific cron routes must be registered before /cron/{key} so legacy job
| names are not captured by the generic index route.
|
*/
Route::prefix('cron')
    ->name('cron.')
    ->middleware(['throttle:cron'])
    ->controller(CronController::class)
    ->group(function () {
        Route::get('/biometricattendance/{key}', 'biometricAttendance')->name('biometric-attendance');
        Route::get('/student_attendance/{key}', 'studentAttendance')->name('student-attendance');
        Route::get('/autobackup/{key}', 'autoBackup')->name('auto-backup');
        Route::get('/feereminder/{key}', 'feeReminder')->name('fee-reminder');
        Route::get('/eventreminder/{key}', 'eventReminder')->name('event-reminder');
        Route::get('/schedulesmsemails/{key}', 'scheduleSmsEmails')->name('scheduled-sms-emails');
        Route::get('/{key}', 'index')->name('index');
    });

/*
|--------------------------------------------------------------------------
| CodeIgniter-Compatible Biometric Routes
|--------------------------------------------------------------------------
*/
Route::prefix('biometric')
    ->name('biometric.')
    ->middleware(['throttle:biometric'])
    ->controller(BiometricController::class)
    ->group(function () {
        Route::match(['get', 'post'], '/', 'index')->name('index');
        Route::match(['get', 'post'], '/getUser', 'getUser')->name('get-user');
    });

/*
|--------------------------------------------------------------------------
| CodeIgniter-Compatible Staff Authentication Routes
|--------------------------------------------------------------------------
|
| These routes preserve the legacy CodeIgniter URLs while pointing them to
| Laravel controllers and middleware.
|
*/
Route::controller(StaffAuthController::class)->group(function () {
    Route::middleware('guest')->group(function () {
        Route::match(['get', 'post'], 'login', 'login')->name('staff.login.legacy');
        Route::match(['get', 'post'], 'staff/login', 'login')->name('staff.login');
        Route::match(['get', 'post'], 'superadmin/login', 'login')->name('superadmin.login');
        Route::match(['get', 'post'], 'staff/forgotpassword', 'forgotPassword')->name('staff.forgot_password');
    });

    Route::post('staff/logout', 'logout')->middleware('auth')->name('staff.logout');
});

/*
|--------------------------------------------------------------------------
| CodeIgniter-Compatible Site Authentication Routes
|--------------------------------------------------------------------------
|
| The public site login URLs are kept intact so existing links continue to
| resolve during and after the CodeIgniter to Laravel migration.
|
*/
Route::controller(SiteAuthController::class)->group(function () {
    Route::middleware('guest')->group(function () {
        Route::match(['get', 'post'], 'signin', 'signin')->name('site.signin');
        Route::match(['get', 'post'], 'site/signin', 'signin')->name('site.signin.legacy');
    });

    Route::post('site/logout', 'logout')->middleware('auth:site')->name('site.logout');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

Route::controller(WelcomeController::class)->group(function () {
    Route::get('/register', 'register')->name('frontend.register');
    Route::post('/register', 'storeRegistration')->name('frontend.register.store');
});
