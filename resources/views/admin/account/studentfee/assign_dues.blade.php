@extends('admin.layouts.app')

@section('title', 'Assign Dues')

@section('content')
<div class="assigndues-container">
    <h2 class="main-box-title">Assign Dues</h2>

    <div class="assigndues-grid">
        {{-- ==================== 1. Left Panel: Select Criteria ==================== --}}
        <div class="box-card">
            <div class="box-card-header">
                <h3 class="box-card-title">Select Criteria</h3>
            </div>
            <div class="box-card-body">
                <form id="debit_fee_form" onsubmit="return false">
                    {{-- Branch Wise --}}
                    <div class="form-group-item">
                        <label for="brc_wise">Branch Wise <span class="req">*</span></label>
                        <select id="brc_wise" name="brc_wise" class="custom-select" onchange="handleBranchWiseChange(this.value)">
                            <option value="">Select</option>
                            @foreach ($branchlist as $brc)
                                <option value="{{ $brc->id }}" {{ (string)$brc_id === (string)$brc->id ? 'selected' : '' }}>
                                    {{ $brc->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Classes Wise --}}
                    <div class="form-group-item">
                        <label for="classes_wise">Classes Wise <span class="req">*</span></label>
                        <select id="classes_wise" name="classes_wise" class="custom-select" onchange="handleClassesWiseChange(this.value)">
                            <option value="">Select</option>
                            <option value="all">All Classes</option>
                            @foreach ($classlist as $cls)
                                <option value="{{ $cls->id }}">{{ $cls->class }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Sections Wise --}}
                    <div class="form-group-item">
                        <label for="sections_wise">Sections Wise <span class="req">*</span></label>
                        <select id="sections_wise" name="sections_wise" class="custom-select" onchange="handleSectionsWiseChange(this.value)">
                            <option value="">Select</option>
                            <option value="all">All Sections</option>
                            @foreach ($sectionlist as $sec)
                                <option value="{{ $sec->id }}">{{ $sec->section }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Students Wise --}}
                    <div class="form-group-item">
                        <label>Students Wise <span class="req">*</span></label>
                        <div>
                            <button type="button" id="students_wise_btn" class="btn-primary-blue" onclick="handleStudentsWiseClick()">
                                Students Wise
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- ==================== 2. Center Panel: Select For Proceed ==================== --}}
        <div class="box-card">
            <div class="box-card-header">
                <h3 class="box-card-title" id="proceedTitle">
                    <i class="fa fa-spinner fa-spin" id="proceedSpinner" style="display:none; color: #1e3a8a;"></i>
                    <i class="fa fa-spinner" id="proceedStaticIcon" style="color: #6b7280;"></i>
                    <span id="proceedTitleText">Select For Proceed</span>
                </h3>
            </div>
            <div class="box-card-body proceed-body-panel" id="proceedBody">
                {{-- Dynamic Student filter if Students Wise clicked --}}
                <div id="studentCriteriaSection" class="student-criteria-section">
                    <div class="grid-3-col-compact">
                        <div>
                            <label class="label-compact">Branch <span class="req">*</span></label>
                            <select id="sw_brc_id" class="custom-select custom-select-compact" onchange="loadStudentsForProceed()">
                                <option value="">Select</option>
                                @foreach ($branchlist as $brc)
                                    <option value="{{ $brc->id }}" {{ (string)$brc_id === (string)$brc->id ? 'selected' : '' }}>
                                        {{ $brc->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="label-compact">Class <span class="req">*</span></label>
                            <select id="sw_class_id" class="custom-select custom-select-compact" onchange="handleStudentWiseClassChange(this.value)">
                                <option value="">Select</option>
                                @foreach ($classlist as $cls)
                                    <option value="{{ $cls->id }}">{{ $cls->class }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="label-compact">Section <span class="req">*</span></label>
                            <select id="sw_section_id" class="custom-select custom-select-compact" onchange="loadStudentsForProceed()">
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="label-compact">Admission No (use comma for multiple)</label>
                        <input type="text" id="sw_admission_no" class="custom-input custom-input-compact" placeholder="e.g. 101, 102" oninput="loadStudentsByAdmitNo(this.value)">
                    </div>
                </div>

                <div id="proceedTableContainer">
                    {{-- Dynamically rendered table rows --}}
                </div>
            </div>
        </div>

        {{-- ==================== 3. Right Panel: Add Dues ==================== --}}
        <div class="box-card">
            <div class="box-card-header">
                <h3 class="box-card-title">Add Dues</h3>
                <button type="button" class="btn-add-badge" onclick="addNewDuesRow()">
                    <i class="fa fa-plus"></i> Add
                </button>
            </div>

            <form id="add_dues_form">
                @csrf
                <input type="hidden" name="selectproceed" id="hiddenSelectProceed" value="">
                <input type="hidden" name="selec_barch" id="hiddenBranchId" value="">
                <input type="hidden" name="select_brc_id" id="hiddenClassBrcId" value="">
                <input type="hidden" name="sec_select_brc_id" id="hiddenSecBrcId" value="">

                <div class="box-card-body">
                    {{-- Row 1: Dues Type | School Amount | Amount --}}
                    <div id="duesRowsWrapper">
                        <div class="dues-dynamic-row">
                            <div>
                                <label class="label-dues">Dues Type <span class="req">*</span></label>
                                <select id="fee_type_main" name="dues_type[]" class="custom-select" required>
                                    <option value="">Select</option>
                                    @foreach ($feetypeList as $ft)
                                        <option value="{{ $ft->id }}">{{ $ft->type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="label-dues">School Amount(Rs.) <span class="req">*</span></label>
                                <input type="number" step="any" min="0" name="school_amount[]" class="custom-input" required>
                            </div>
                            <div>
                                <label class="label-dues">Amount(Rs.) <span class="req">*</span></label>
                                <input type="number" step="any" min="0" name="dues_amount[]" class="custom-input" required>
                            </div>
                        </div>
                    </div>

                    {{-- Extra dynamically appended rows --}}
                    <div id="extraDuesRowsWrapper"></div>

                    {{-- Dates and Description --}}
                    <div class="dues-dates-section">
                        <div class="form-group-item">
                            <label for="issue_date">Issue Date <span class="req">*</span></label>
                            <input type="text" id="issue_date" name="issue_date" class="custom-input" value="{{ date('d/m/Y') }}" required>
                        </div>
                        <div class="form-group-item">
                            <label for="due_date">Due Date <span class="req">*</span></label>
                            <input type="text" id="due_date" name="due_date" class="custom-input" value="{{ date('d/m/Y') }}" required>
                        </div>
                        <div class="form-group-item">
                            <label for="dues_date">Assign Dues Date <span class="req">*</span></label>
                            <input type="text" id="dues_date" name="dues_date" class="custom-input" value="{{ date('d/m/Y') }}" required>
                        </div>
                        <div class="form-group-item">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" class="custom-textarea" placeholder="Enter ..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="box-card-footer">
                    <div>
                        <label class="notification-checkbox-label">
                            <input type="checkbox" name="notification" value="notification" checked> Notification
                        </label>
                    </div>
                    <button type="button" id="addduesbtn" class="btn-primary-blue" onclick="submitAssignDues()">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    var globalFeeTypes = @json($feetypeList);

    function toggleProceedSpinner(show) {
        var sp = document.getElementById('proceedSpinner');
        var st = document.getElementById('proceedStaticIcon');
        if (sp) sp.style.display = show ? 'inline-block' : 'none';
        if (st) st.style.display = show ? 'none' : 'inline-block';
    }

    function showToast(msg, isError) {
        let toast = document.getElementById('appToast');
        let bgClass = isError ? 'bg-red-100' : 'bg-green-100';
        let borderClass = isError ? 'border-red-500' : 'border-green-500';
        let textClass = isError ? 'text-red-700' : 'text-green-700';
        let progressClass = isError ? 'bg-red-500' : 'bg-green-500';
        let title = isError ? 'Error' : 'Success';
        let icon = isError ? '×' : '✓';

        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'appToast';
            toast.className = `fixed top-3.5 right-3.5 z-[9999] w-[230px] max-w-[calc(100vw-2rem)] overflow-hidden rounded-md ${bgClass} border-l-[3px] ${borderClass} ${textClass} shadow-md toast-slide-in`;
            toast.innerHTML = `
                <div class="flex items-start gap-2 px-2.5 py-1.5">
                    <div class="flex h-4 w-4 mt-0.5 shrink-0 items-center justify-center rounded-full ${progressClass} text-white text-[9px] font-bold leading-none">${icon}</div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[11px] font-bold leading-tight">${title}</p>
                        <p class="text-[10px] leading-tight mt-0.5 opacity-90 truncate">${msg}</p>
                    </div>
                    <button type="button" onclick="hideToast()" class="text-xs font-bold opacity-60 hover:opacity-100 transition leading-none px-0.5" aria-label="Close">×</button>
                </div>
                <div class="toast-progress-track">
                    <div id="toastProgress" class="toast-progress ${progressClass}"></div>
                </div>
            `;
            document.body.appendChild(toast);
        } else {
            toast.classList.remove('toast-slide-out');
            toast.classList.add('toast-slide-in');
        }
        setTimeout(() => {
            if (typeof window.hideToast === 'function') window.hideToast();
        }, 3000);
    }

    // 1. Branch Wise
    function handleBranchWiseChange(brcId) {
        if (!brcId) return;
        document.getElementById('studentCriteriaSection').style.display = 'none';
        document.getElementById('hiddenSelectProceed').value = 'branch';
        document.getElementById('hiddenBranchId').value = brcId;
        document.getElementById('proceedTitleText').innerText = 'Select Branch(s) for Proceed';
        toggleProceedSpinner(true);

        fetch("{{ url('admin/account/studentfee/getStudentByBranch') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ brc_id: brcId })
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            toggleProceedSpinner(false);
            if (data && data.student && data.student.total_student > 0) {
                var html = '<table class="table-proceed-view"><thead><tr>';
                html += '<th style="width:36px;"><i class="fa fa-check-square"></i></th>';
                html += '<th style="text-align:left;">Branch Name</th>';
                html += '<th>Strength</th>';
                html += '</tr></thead><tbody><tr>';
                html += '<td style="text-align:center;"><input type="checkbox" name="selec_barch" value="' + data.student.brc_id + '" checked></td>';
                html += '<td style="text-align:left;">' + data.student.branch_name + '</td>';
                html += '<td style="text-align:center;">' + data.student.total_student + '</td>';
                html += '</tr></tbody></table>';
                document.getElementById('proceedTableContainer').innerHTML = html;
            } else {
                document.getElementById('proceedTableContainer').innerHTML = '<div style="text-align:center; padding: 20px; color:#ef4444;">No record found</div>';
            }
        })
        .catch(function(err) {
            toggleProceedSpinner(false);
            console.error(err);
        });
    }

    // 2. Classes Wise
    function handleClassesWiseChange(classId) {
        if (!classId) return;
        var brcId = document.getElementById('brc_wise').value || 1;
        document.getElementById('studentCriteriaSection').style.display = 'none';
        document.getElementById('hiddenSelectProceed').value = 'classes';
        document.getElementById('hiddenClassBrcId').value = brcId;
        document.getElementById('proceedTitleText').innerText = 'Select Class(s) for Proceed';
        toggleProceedSpinner(true);

        fetch("{{ url('admin/account/studentfee/getClassesByBranch') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ brc_id: brcId, class_id: classId })
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            toggleProceedSpinner(false);
            if (data && data.student && Array.isArray(data.student) && data.student.length > 0) {
                var html = '<table class="table-proceed-view"><thead><tr>';
                html += '<th style="width:36px;"><input type="checkbox" id="selectAllClasses" checked onclick="toggleAllCheckboxes(this, \'.checkbox_class\')"></th>';
                html += '<th style="text-align:left;">Class</th>';
                html += '<th>Strength</th>';
                html += '</tr></thead><tbody>';
                data.student.forEach(function(item) {
                    var strength = 0;
                    if (item.classesstudent) {
                        Object.values(item.classesstudent).forEach(function(val) { strength += Number(val); });
                    } else if (item.strength !== undefined) {
                        strength = Number(item.strength);
                    }
                    if (strength > 0) {
                        html += '<tr>';
                        html += '<td style="text-align:center;"><input type="checkbox" class="checkbox_class" name="class_id[]" value="' + item.id + '" checked></td>';
                        html += '<td style="text-align:left;">' + item.classname + '</td>';
                        html += '<td style="text-align:center;">' + strength + '</td>';
                        html += '</tr>';
                    }
                });
                html += '</tbody></table>';
                document.getElementById('proceedTableContainer').innerHTML = html;
            } else {
                document.getElementById('proceedTableContainer').innerHTML = '<div style="text-align:center; padding: 20px; color:#ef4444;">No record found</div>';
            }
        })
        .catch(function(err) {
            toggleProceedSpinner(false);
            console.error(err);
        });
    }

    // 3. Sections Wise
    function handleSectionsWiseChange(secId) {
        if (!secId) return;
        var brcId = document.getElementById('brc_wise').value || 1;
        document.getElementById('studentCriteriaSection').style.display = 'none';
        document.getElementById('hiddenSelectProceed').value = 'sections';
        document.getElementById('hiddenSecBrcId').value = brcId;
        document.getElementById('proceedTitleText').innerText = 'Select Section(s) for Proceed';
        toggleProceedSpinner(true);

        fetch("{{ url('admin/account/studentfee/getClassesSectionsByBranch') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ brc_id: brcId, section_id: secId })
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            toggleProceedSpinner(false);
            if (data && data.student && Array.isArray(data.student) && data.student.length > 0) {
                var html = '<table class="table-proceed-view"><thead><tr>';
                html += '<th style="width:36px;"><input type="checkbox" id="selectAllSections" checked onclick="toggleAllCheckboxes(this, \'.checkbox_section\')"></th>';
                html += '<th style="text-align:left;">Class - Section</th>';
                html += '<th>Strength</th>';
                html += '</tr></thead><tbody>';
                data.student.forEach(function(item) {
                    var strength = 0;
                    if (item.totalstudent) {
                        Object.values(item.totalstudent).forEach(function(val) { strength += Number(val); });
                    } else if (item.strength !== undefined) {
                        strength = Number(item.strength);
                    }
                    if (strength > 0) {
                        html += '<tr>';
                        html += '<td style="text-align:center;"><input type="hidden" name="section_id[]" value="' + item.section_id + '"><input type="checkbox" class="checkbox_section" name="class_id[]" value="' + item.class_id + '" checked></td>';
                        html += '<td style="text-align:left;">' + item.classname + ' - ' + item.sectionname + '</td>';
                        html += '<td style="text-align:center;">' + strength + '</td>';
                        html += '</tr>';
                    }
                });
                html += '</tbody></table>';
                document.getElementById('proceedTableContainer').innerHTML = html;
            } else {
                document.getElementById('proceedTableContainer').innerHTML = '<div style="text-align:center; padding: 20px; color:#ef4444;">No record found</div>';
            }
        })
        .catch(function(err) {
            toggleProceedSpinner(false);
            console.error(err);
        });
    }

    // 4. Students Wise Click
    function handleStudentsWiseClick() {
        document.getElementById('hiddenSelectProceed').value = 'students';
        document.getElementById('proceedTitleText').innerText = 'Select Student(s) for Proceed';
        document.getElementById('studentCriteriaSection').style.display = 'block';

        var selectedBrc = document.getElementById('brc_wise').value;
        if (selectedBrc) {
            var swBrc = document.getElementById('sw_brc_id');
            if (swBrc) {
                swBrc.value = selectedBrc;
            }
        }

        // Reset filter inputs and keep table empty until user enters criteria
        var swClass = document.getElementById('sw_class_id');
        if (swClass) swClass.value = '';
        var swSec = document.getElementById('sw_section_id');
        if (swSec) swSec.innerHTML = '<option value="">Select</option>';
        var swAdm = document.getElementById('sw_admission_no');
        if (swAdm) swAdm.value = '';
        document.getElementById('proceedTableContainer').innerHTML = '';
    }

    function handleStudentWiseClassChange(classId) {
        loadStudentWiseSections(classId);
        // Do not load students until Section is selected or Admission No is entered
        document.getElementById('proceedTableContainer').innerHTML = '';
    }

    function loadStudentWiseSections(classId) {
        var secSelect = document.getElementById('sw_section_id');
        secSelect.innerHTML = '<option value="">Select</option>';
        if (!classId) return;

        fetch("{{ url('admin/account/studentfee/get-sections') }}/" + classId)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (Array.isArray(data)) {
                    data.forEach(function(s) {
                        var opt = document.createElement('option');
                        opt.value = s.section_id || s.id;
                        opt.text = s.section || s.name;
                        secSelect.appendChild(opt);
                    });
                }
            });
    }

    function loadStudentsForProceed() {
        var brcId = document.getElementById('sw_brc_id').value || document.getElementById('brc_wise').value || 1;
        var classId = document.getElementById('sw_class_id').value;
        var secId = document.getElementById('sw_section_id').value;
        var admitNo = document.getElementById('sw_admission_no') ? document.getElementById('sw_admission_no').value.trim() : '';

        // If Section is not selected and Admission No is not entered, do not show table
        if (!secId && !admitNo) {
            document.getElementById('proceedTableContainer').innerHTML = '';
            return;
        }

        toggleProceedSpinner(true);
        fetch("{{ url('admin/account/studentfee/getStudentClassSectionsByBranch') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ brc_id: brcId, class_id: classId, section_id: secId })
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            toggleProceedSpinner(false);
            if (data && data.student && data.student.length > 0) {
                var html = '<table class="table-proceed-view"><thead><tr>';
                html += '<th style="width:36px;"><input type="checkbox" checked onclick="toggleAllCheckboxes(this, \'.checkbox_std\')"></th>';
                html += '<th style="text-align:left;">Admit No</th>';
                html += '<th style="text-align:left;">Student Name</th>';
                html += '<th style="text-align:left;">Father Name</th>';
                html += '</tr></thead><tbody>';
                data.student.forEach(function(std) {
                    html += '<tr>';
                    html += '<td style="text-align:center;"><input type="checkbox" class="checkbox_std" name="students_session_id[]" value="' + std.student_session_id + '" checked></td>';
                    html += '<td style="text-align:left;">' + std.admission_no + '</td>';
                    html += '<td style="text-align:left;">' + std.firstname + ' ' + (std.lastname || '') + '</td>';
                    html += '<td style="text-align:left;">' + (std.father_name || '') + '</td>';
                    html += '</tr>';
                });
                html += '</tbody></table>';
                document.getElementById('proceedTableContainer').innerHTML = html;
            } else {
                document.getElementById('proceedTableContainer').innerHTML = '<div style="text-align:center; padding: 20px; color:#ef4444;">No record found</div>';
            }
        })
        .catch(function(err) {
            toggleProceedSpinner(false);
            console.error(err);
        });
    }

    function loadStudentsByAdmitNo(admitNo) {
        var brcId = document.getElementById('sw_brc_id').value || 1;
        if (!admitNo || !admitNo.trim()) {
            var classId = document.getElementById('sw_class_id').value;
            if (classId) {
                loadStudentsForProceed();
            } else {
                document.getElementById('proceedTableContainer').innerHTML = '';
            }
            return;
        }

        toggleProceedSpinner(true);
        fetch("{{ url('admin/account/studentfee/getstdByBrcIDByAdmitNo') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ brc_id: brcId, admit_no: admitNo })
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            toggleProceedSpinner(false);
            if (data && data.student && data.student.length > 0) {
                var html = '<table class="table-proceed-view"><thead><tr>';
                html += '<th style="width:36px;"><input type="checkbox" checked onclick="toggleAllCheckboxes(this, \'.checkbox_std\')"></th>';
                html += '<th style="text-align:left;">Admit No</th>';
                html += '<th style="text-align:left;">Student Name</th>';
                html += '<th style="text-align:left;">Father Name</th>';
                html += '</tr></thead><tbody>';
                data.student.forEach(function(std) {
                    html += '<tr>';
                    html += '<td style="text-align:center;"><input type="checkbox" class="checkbox_std" name="students_session_id[]" value="' + std.student_session_id + '" checked></td>';
                    html += '<td style="text-align:left;">' + std.admission_no + '</td>';
                    html += '<td style="text-align:left;">' + std.firstname + ' ' + (std.lastname || '') + '</td>';
                    html += '<td style="text-align:left;">' + (std.father_name || '') + '</td>';
                    html += '</tr>';
                });
                html += '</tbody></table>';
                document.getElementById('proceedTableContainer').innerHTML = html;
            } else {
                document.getElementById('proceedTableContainer').innerHTML = '<div style="text-align:center; padding: 20px; color:#ef4444;">No record found</div>';
            }
        })
        .catch(function(err) {
            toggleProceedSpinner(false);
            console.error(err);
        });
    }

    function toggleAllCheckboxes(master, selector) {
        document.querySelectorAll(selector).forEach(function(cb) {
            cb.checked = master.checked;
        });
    }

    // 5. Dynamic Row Add
    function addNewDuesRow() {
        var wrapper = document.getElementById('extraDuesRowsWrapper');
        var row = document.createElement('div');
        row.className = 'dues-extra-row';

        var typeOptions = '<option value="">Select</option>';
        globalFeeTypes.forEach(function(ft) {
            typeOptions += '<option value="' + ft.id + '">' + (ft.type || ft.name) + '</option>';
        });

        row.innerHTML = `
            <div>
                <select name="dues_type[]" class="custom-select" required>
                    ${typeOptions}
                </select>
            </div>
            <div>
                <input type="number" step="any" min="0" name="school_amount[]" class="custom-input" placeholder="School Amount" required>
            </div>
            <div>
                <input type="number" step="any" min="0" name="dues_amount[]" class="custom-input" placeholder="Amount" required>
            </div>
            <div>
                <button type="button" class="btn-remove-badge" onclick="this.closest('.dues-extra-row').remove()">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        `;
        wrapper.appendChild(row);
    }

    // 6. Submit Assign Dues
    function submitAssignDues() {
        var form = document.getElementById('add_dues_form');
        var formData = new FormData(form);

        // Ensure category selection is present
        var proceedType = document.getElementById('hiddenSelectProceed').value;
        if (!proceedType) {
            if (document.getElementById('studentCriteriaSection').style.display !== 'none') {
                proceedType = 'students';
            } else if (document.getElementById('hiddenBranchId').value) {
                proceedType = 'branch';
            } else if (document.getElementById('hiddenClassBrcId').value) {
                proceedType = 'classes';
            } else if (document.getElementById('hiddenSecBrcId').value) {
                proceedType = 'sections';
            } else {
                proceedType = 'branch';
            }
            formData.set('selectproceed', proceedType);
        }

        // Collect all checked inputs in Proceed Table Container
        var proceedCheckboxes = document.querySelectorAll('#proceedTableContainer input[type="checkbox"]:checked');
        proceedCheckboxes.forEach(function(cb) {
            if (cb.name) {
                formData.append(cb.name, cb.value);
            }
        });

        // Also collect hidden inputs in proceed table
        var hiddenProceedInputs = document.querySelectorAll('#proceedTableContainer input[type="hidden"]');
        hiddenProceedInputs.forEach(function(hi) {
            if (hi.name) {
                formData.append(hi.name, hi.value);
            }
        });

        // Also collect student criteria filters if present
        var swBrc = document.getElementById('sw_brc_id');
        if (swBrc && swBrc.value) formData.set('sw_brc_id', swBrc.value);

        var swAdmit = document.getElementById('sw_admission_no');
        if (swAdmit && swAdmit.value) formData.set('sw_admission_no', swAdmit.value);

        var btn = document.getElementById('addduesbtn');
        btn.disabled = true;
        btn.innerText = 'Saving...';

        fetch("{{ url('admin/account/studentfee/addDues') }}", {
            method: 'POST',
            body: formData
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            btn.disabled = false;
            btn.innerText = 'Save';
            if (data.status === 'success') {
                showToast(data.message || 'Dues assigned successfully!', false);
                setTimeout(function() { window.location.reload(); }, 1500);
            } else {
                var errStr = 'Error: ';
                if (data.error) {
                    if (typeof data.error === 'object') {
                        errStr += Object.values(data.error).join(', ');
                    } else {
                        errStr += data.error;
                    }
                } else {
                    errStr += data.message || 'Could not assign dues.';
                }
                showToast(errStr, true);
            }
        })
        .catch(function(err) {
            btn.disabled = false;
            btn.innerText = 'Save';
            showToast('Network error occurred. Please try again.', true);
            console.error(err);
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        var btn = document.getElementById('students_wise_btn') || document.getElementById('students_wise');
        if (btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                handleStudentsWiseClick();
            });
        }
    });
</script>
@endpush
@endsection
