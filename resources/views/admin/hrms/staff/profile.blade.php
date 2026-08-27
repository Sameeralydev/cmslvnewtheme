@extends('admin.layouts.app')

@section('title', 'Staff Profile')

@php
    $detailRows = [
        ['Username', $staff['username']],
        ['Password', $staff['plain_password']],
        ['Whatsapp No', $staff['whatsapp_no']],
        ['Phone', $staff['contact_no']],
        ['Emergency Contact Number', $staff['emergency_contact_no']],
        ['CNIC No', $staff['cnic']],
        ['Email', $staff['email']],
        ['Gender', $staff['gender']],
        ['Date of Birth', $staff['dob_label']],
        ['Marital Status', $staff['marital_status']],
        ['Father Name', $staff['father_name']],
        ['Note', $staff['note']],
        ['Total Security', $staff['total_security']],
        ['Per Month Security', $staff['month_security']],
    ];

    $summaryRows = [
        ['Branch', $staff['branch_name']],
        ['Staff ID', $staff['employee_id']],
        ['Role', $staff['role_name']],
        ['Designation', $staff['designation_name']],
        ['Department', $staff['department_name']],
        ['Contract Type', $staff['contract_type']],
        ['Work Shift', $staff['shift']],
        ['Location', $staff['location']],
        ['Date of Joining', $staff['date_of_joining_label']],
    ];

    if ($staff['date_of_leaving_label'] !== '-') {
        $summaryRows[] = ['Date of Leaving', $staff['date_of_leaving_label']];
    }

    if ($staff['disable_at_label'] !== '-') {
        $summaryRows[] = ['Disable Date', $staff['disable_at_label']];
    }
@endphp


@section('content')
    <div class="legacy-hrms-profile">
        <div class="row">
            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <div class="profile-user-img img-responsive img-circle" style="display:flex;align-items:center;justify-content:center;background:linear-gradient(#ffffff,#f1f1f1);border:1px solid #ddd;">
                            <div style="text-align:center;color:#b8bfca;">
                                <i class="fa fa-users" style="font-size:30px;"></i>
                                <div style="font-size:10px;line-height:1.2;margin-top:4px;">NO IMAGE<br>AVAILABLE</div>
                            </div>
                        </div>

                        <h3 class="profile-username text-center">{{ $staff['full_name'] }}</h3>

                        <ul class="list-group list-group-unbordered" style="margin-top: 15px;">
                            @foreach ($summaryRows as [$label, $value])
                                <li class="list-group-item listnoback">
                                    <b>{{ $label }}</b>
                                    <a class="pull-right text-aqua">{{ $value }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#profile" data-toggle="tab">Profile</a></li>
                        <li><a href="#payroll" data-toggle="tab">Payroll</a></li>
                        <li><a href="#leaves" data-toggle="tab">Leaves</a></li>
                        <li><a href="#attendance" data-toggle="tab">Attendance</a></li>
                        <li><a href="#documents" data-toggle="tab">Documents</a></li>
                        <li><a href="#timeline" data-toggle="tab">Timeline</a></li>
                        <li><a href="#reviews" data-toggle="tab">Reviews</a></li>

                        <li class="pull-right">
                            <a href="{{ route('admin.hrms.staff.appointment-form', $staff['id'], false) }}" class="action-link" title="Appointment Form">
                                <i class="fa fa-key"></i>
                            </a>
                        </li>
                        <li class="pull-right">
                            <a href="{{ route('admin.hrms.staff.service-experience-certificate', $staff['id'], false) }}" class="action-link" title="Service Experience Certificate">
                                <i class="fa fa-hand-o-down"></i>
                            </a>
                        </li>
                        <li class="pull-right">
                            <a href="{{ route('admin.hrms.staff.edit', $staff['id'], false) }}" class="action-link" title="Edit">
                                <i class="fa fa-pencil"></i>
                            </a>
                        </li>
                        <li class="pull-right">
                            <a href="#" class="action-link" title="Menu">
                                <i class="fa fa-navicon"></i>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="profile">
                            <div class="tshadow mb25 bozero">
                                <div class="table-responsive around10">
                                    <table class="table table-hover table-striped tmb0">
                                        <tbody>
                                            @foreach ($detailRows as [$label, $value])
                                                <tr>
                                                    <td>{{ $label }}</td>
                                                    <td>{{ $value }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="section-box">
                                <div class="section-title">Academic Information</div>
                                <div class="table-responsive">
                                    <table class="table table-hover tmb0">
                                        <thead>
                                            <tr>
                                                <th>Sr.#</th>
                                                <th>University/Board</th>
                                                <th>From</th>
                                                <th>To</th>
                                                <th>Degree/Certificate</th>
                                                <th>Maximum Marks</th>
                                                <th>Obtained Marks</th>
                                                <th>Division/Grade</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($academicRecords as $record)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $record['institute_name'] }}</td>
                                                    <td>{{ $record['from_year'] }}</td>
                                                    <td>{{ $record['to_year'] }}</td>
                                                    <td>{{ $record['degree_name'] }}</td>
                                                    <td>{{ $record['maxmarks'] }}</td>
                                                    <td>{{ $record['obtmarks'] }}</td>
                                                    <td>{{ $record['grade'] }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="blank-row">No record available.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="section-box">
                                <div class="section-title">Professional Trainings/Certifications/Others</div>
                                <div class="table-responsive">
                                    <table class="table table-hover tmb0">
                                        <thead>
                                            <tr>
                                                <th>Sr.#</th>
                                                <th>Institute</th>
                                                <th>Type of training</th>
                                                <th>From</th>
                                                <th>To</th>
                                                <th>Obtained Marks</th>
                                                <th>Division/Grade</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($certificationRecords as $record)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $record['institute_name'] }}</td>
                                                    <td>{{ $record['training_name'] }}</td>
                                                    <td>{{ $record['from_year'] }}</td>
                                                    <td>{{ $record['to_year'] }}</td>
                                                    <td>{{ $record['obtmarks'] }}</td>
                                                    <td>{{ $record['grade'] }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="blank-row">No record available.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="section-box">
                                <div class="section-title">Employment Record/Professional Experience</div>
                                <div class="table-responsive">
                                    <table class="table table-hover tmb0">
                                        <thead>
                                            <tr>
                                                <th>Sr.#</th>
                                                <th>Organization</th>
                                                <th>Position held</th>
                                                <th>Contact no</th>
                                                <th>From</th>
                                                <th>To</th>
                                                <th>Salary</th>
                                                <th>Reason of leaving</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($experienceRecords as $record)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $record['organization_name'] }}</td>
                                                    <td>{{ $record['position_name'] }}</td>
                                                    <td>{{ $record['contactno'] }}</td>
                                                    <td>{{ $record['from_year'] }}</td>
                                                    <td>{{ $record['to_year'] }}</td>
                                                    <td>{{ $record['salary'] }}</td>
                                                    <td>{{ $record['reason'] }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="blank-row">No record available.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="section-box">
                                <div class="section-title">Address</div>
                                <div class="table-responsive">
                                    <table class="table table-hover tmb0">
                                        <tbody>
                                            <tr>
                                                <td>Current Address</td>
                                                <td>{{ $staff['local_address'] }}</td>
                                            </tr>
                                            <tr>
                                                <td>Permanent Address</td>
                                                <td>{{ $staff['permanent_address'] }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="section-box">
                                <div class="section-title">Bank Account Details</div>
                                <div class="table-responsive">
                                    <table class="table table-hover tmb0">
                                        <tbody>
                                            <tr><td>Account Title</td><td>{{ $staff['account_title'] }}</td></tr>
                                            <tr><td>Bank Name</td><td>{{ $staff['bank_name'] }}</td></tr>
                                            <tr><td>Bank Branch Name</td><td>{{ $staff['bank_branch'] }}</td></tr>
                                            <tr><td>Bank Account Number</td><td>{{ $staff['bank_account_no'] }}</td></tr>
                                            <tr><td>IBAN Code</td><td>{{ $staff['iban_code'] }}</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="section-box">
                                <div class="section-title">Social Media Link</div>
                                <div class="table-responsive">
                                    <table class="table table-hover tmb0">
                                        <tbody>
                                            <tr><td>Facebook URL</td><td>{{ $staff['facebook'] }}</td></tr>
                                            <tr><td>Twitter URL</td><td>{{ $staff['twitter'] }}</td></tr>
                                            <tr><td>Linkedin URL</td><td>{{ $staff['linkedin'] }}</td></tr>
                                            <tr><td>Instagram URL</td><td>{{ $staff['instagram'] }}</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="payroll">
                            <div class="section-box" style="margin-bottom: 0;">
                                <div class="section-title">Pay Information</div>
                                <div class="table-responsive">
                                    <table class="table table-hover tmb0">
                                        <thead>
                                            <tr>
                                                <th>Type</th>
                                                <th>Frequency</th>
                                                <th>Amount (Rs.)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php($grandTotal = collect($payRecords)->sum('amount'))
                                            @forelse ($payRecords as $record)
                                                <tr>
                                                    <td>{{ $record['type_name'] }}</td>
                                                    <td>{{ $record['frequency'] }}</td>
                                                    <td>{{ number_format($record['amount'], 2) }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="blank-row">No pay record available.</td>
                                                </tr>
                                            @endforelse
                                            <tr>
                                                <td colspan="2" class="text-right"><strong>Grand Total :</strong></td>
                                                <td><strong>{{ number_format($grandTotal, 2) }}</strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="leaves">
                            <div class="section-box" style="margin-bottom: 0;">
                                <div class="section-title">Leaves</div>
                                <div class="table-responsive">
                                    <table class="table table-hover tmb0">
                                        <thead>
                                            <tr>
                                                <th>Type</th>
                                                <th>From</th>
                                                <th>To</th>
                                                <th>Days</th>
                                                <th>Status</th>
                                                <th>Remark</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($leaveRecords as $record)
                                                <tr>
                                                    <td>{{ $record['leave_type_name'] }}</td>
                                                    <td>{{ $record['leave_from'] }}</td>
                                                    <td>{{ $record['leave_to'] }}</td>
                                                    <td>{{ $record['leave_days'] }}</td>
                                                    <td>{{ $record['status'] }}</td>
                                                    <td>{{ $record['employee_remark'] }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="blank-row">No leave record available.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="attendance">
                            <div class="section-box" style="margin-bottom: 0;">
                                <div class="section-title">Attendance</div>
                                <div style="padding: 18px 14px; font-size: 12px; color: #777;">
                                    No attendance record available.
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="documents">
                            <div class="section-box" style="margin-bottom: 0;">
                                <div class="section-title">Documents</div>
                                <div class="table-responsive">
                                    <table class="table table-hover tmb0">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>File</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($documentRecords as $record)
                                                <tr>
                                                    <td>{{ $record['label'] }}</td>
                                                    <td>{{ $record['filename'] }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="2" class="blank-row">No document available.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="timeline">
                            <div class="section-box" style="margin-bottom: 0;">
                                <div class="section-title">Timeline</div>
                                <div style="padding: 18px 14px; font-size: 12px; color: #777;">
                                    No timeline record available.
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="reviews">
                            <div class="section-box" style="margin-bottom: 0;">
                                <div class="section-title">Reviews</div>
                                <div style="padding: 18px 14px; font-size: 12px; color: #777;">
                                    No review record available.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (() => {
            const tabLinks = document.querySelectorAll('.legacy-hrms-profile .nav-tabs a[href^="#"]');

            if (tabLinks.length === 0) {
                return;
            }

            const activateTab = (targetId) => {
                const panes = document.querySelectorAll('.legacy-hrms-profile .tab-pane');
                const items = document.querySelectorAll('.legacy-hrms-profile .nav-tabs li');

                items.forEach((item) => item.classList.remove('active'));

                panes.forEach((pane) => {
                    const active = `#${pane.id}` === targetId;
                    pane.classList.toggle('active', active);
                    pane.style.display = active ? 'block' : 'none';
                });

                const activeLink = document.querySelector(`.legacy-hrms-profile .nav-tabs a[href="${targetId}"]`);

                if (activeLink) {
                    activeLink.parentElement?.classList.add('active');
                }
            };

            tabLinks.forEach((link) => {
                link.addEventListener('click', (event) => {
                    const href = link.getAttribute('href');

                    if (! href || ! href.startsWith('#')) {
                        return;
                    }

                    event.preventDefault();
                    activateTab(href);
                });
            });

            activateTab('#profile');
        })();
    </script>
@endpush
