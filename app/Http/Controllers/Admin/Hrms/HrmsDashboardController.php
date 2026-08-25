<?php

namespace App\Http\Controllers\Admin\Hrms;

use Illuminate\Http\RedirectResponse;

class HrmsDashboardController extends BaseHrmsController
{
    public function index(): RedirectResponse
    {
        return to_route('admin.hrms.training.agenda.index');
    }
}
