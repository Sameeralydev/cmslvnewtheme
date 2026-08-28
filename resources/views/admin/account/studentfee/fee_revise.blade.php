@extends('admin.layouts.app')

@section('title', 'Fee Revise')

@section('content')
    <div class="legacy-coa">
        <section class="content">
            {{-- Select Criteria Card --}}
            <div class="feerevise-box">
                <div class="box-header">
                    <h3 class="box-title">Select Criteria</h3>
                </div>

                <form id="criteriaForm" action="{{ url('admin/account/studentfee/feerevise/' . $brc_id) }}" method="POST">
                    @csrf
                    <div class="box-body">
                        {{-- Row 1: 4 columns --}}
                        <div class="criteria-row">
                            {{-- Branch --}}
                            <div class="criteria-col-3">
                                <div class="form-group">
                                    <label for="brc_id">Branch <span class="req">*</span></label>
                                    <select id="brc_id" name="brc_id" class="form-control selectval" onchange="getBranchByID(this.value)">
                                        <option value="">Select</option>
                                        @foreach ($branchlist as $brc)
                                            <option value="{{ $brc->id }}" {{ (string) old('brc_id', $brc_id) === (string) $brc->id ? 'selected' : '' }}>
                                                {{ $brc->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Class --}}
                            <div class="criteria-col-3">
                                <div class="form-group">
                                    <label for="class_id">Class</label>
                                    <select id="class_id" name="class_id" class="form-control selectval" onchange="loadSections(this.value)">
                                        <option value="">Select</option>
                                        @foreach ($classlist as $class)
                                            <option value="{{ $class->id }}" {{ (string) old('class_id', $class_post) === (string) $class->id ? 'selected' : '' }}>
                                                {{ $class->class }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Section --}}
                            <div class="criteria-col-3">
                                <div class="form-group">
                                    <label for="section_id">Section</label>
                                    <select id="section_id" name="section_id" class="form-control selectval">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Fees Type --}}
                            <div class="criteria-col-3">
                                <div class="form-group">
                                    <label for="due_id">Fees Type <span class="req">*</span></label>
                                    <select id="due_id" name="due_id" class="form-control selectval" required>
                                        <option value="">Select</option>
                                        @foreach ($feetypeList as $feetype)
                                            <option value="{{ $feetype->id }}" {{ (string) old('due_id', $due_id) === (string) $feetype->id ? 'selected' : '' }}>
                                                {{ $feetype->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Row 2: Fees Management Options --}}
                        <div class="criteria-row">
                            {{-- Fees (Manage Type) --}}
                            <div class="criteria-col-3">
                                <div class="form-group">
                                    <label for="fees_manage">Fees <span class="req">*</span></label>
                                    <select id="fees_manage" name="fees_manage" class="form-control selectval" onchange="handleFeesManageChange(this.value)" required>
                                        <option value="">Select</option>
                                        <option value="1" {{ (string) old('fees_manage', $feesmanage) === '1' ? 'selected' : '' }}>Increment</option>
                                        <option value="2" {{ (string) old('fees_manage', $feesmanage) === '2' ? 'selected' : '' }}>Decrement</option>
                                        <option value="3" {{ (string) old('fees_manage', $feesmanage) === '3' ? 'selected' : '' }}>Assign Fee</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Increment / Decrement Type (Radio) --}}
                            <div class="criteria-col-3" id="incrementTypeGroup" style="display: {{ (string) $feesmanage === '1' ? 'block' : 'none' }};">
                                <div class="form-group">
                                    <label>Increment By</label>
                                    <div class="radio-inline-wrap">
                                        <label>
                                            <input type="radio" name="is_increment_type" value="1" {{ (string) $increment_type !== '2' ? 'checked' : '' }} onchange="handleIncrementTypeChange('1')"> Fixed
                                        </label>
                                        <label>
                                            <input type="radio" name="is_increment_type" value="2" {{ (string) $increment_type === '2' ? 'checked' : '' }} onchange="handleIncrementTypeChange('2')"> Percentage %
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {{-- Fixed Amount --}}
                            <div class="criteria-col-3" id="incrementAmountGroup" style="display: {{ (string) $feesmanage === '1' && (string) $increment_type !== '2' ? 'block' : 'none' }};">
                                <div class="form-group">
                                    <label for="increment_amount">Amount</label>
                                    <input type="number" step="any" min="0" id="increment_amount" name="increment_amount" class="form-control" value="{{ old('increment_amount', $increment_amount) }}" placeholder="Enter Amount">
                                </div>
                            </div>

                            {{-- Percentage % --}}
                            <div class="criteria-col-3" id="incrementValueGroup" style="display: {{ (string) $feesmanage === '1' && (string) $increment_type === '2' ? 'block' : 'none' }};">
                                <div class="form-group">
                                    <label for="increment_value">Percentage %</label>
                                    <input type="number" step="any" min="0" id="increment_value" name="increment_value" class="form-control" value="{{ old('increment_value', $increment_value) }}" placeholder="Enter Percentage">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer text-right">
                        <button type="submit" class="btn-theme-search">
                            Search
                        </button>
                    </div>
                </form>
            </div>

            {{-- Results Table Card --}}
            @if ($resultlist !== null)
                <div class="feerevise-box">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-list"></i> Fee Revise</h3>
                    </div>

                    <form id="feeReviseUpdateForm">
                        @csrf
                        <input type="hidden" name="feesmanage" value="{{ $feesmanage }}">
                        <input type="hidden" name="class_post" value="{{ $class_post }}">
                        <input type="hidden" name="section_post" value="{{ $section_post }}">

                        <div class="box-body">
                            <div class="cmsc-table-wrap">
                                <table class="table table-striped table-bordered table-hover example" id="feeReviseTable">
                                    <thead>
                                        <tr>
                                            <th class="th-checkbox-col">
                                                <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll(this)">
                                            </th>
                                            <th>Admission No</th>
                                            <th>Class</th>
                                            <th>Student Name</th>
                                            <th>Father Name</th>
                                            <th>Date of Birth</th>
                                            <th>Gender</th>
                                            <th class="text-right">Current Fee</th>
                                            <th class="th-fee-col">
                                                @if ((string) $feesmanage === '1')
                                                    Revised Fee
                                                @elseif ((string) $feesmanage === '2')
                                                    Decrement Fee
                                                @elseif ((string) $feesmanage === '3')
                                                    Assign Fee
                                                @else
                                                    Fee
                                                @endif
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($resultlist as $student)
                                            <tr>
                                                <td class="text-center">
                                                    <input type="checkbox" class="student-checkbox" name="check[]" value="{{ $student->student_session_id }}">
                                                    <input type="hidden" name="dues_id_{{ $student->student_session_id }}" value="{{ $due_id }}">
                                                </td>
                                                <td>{{ $student->admission_no }}</td>
                                                <td>{{ $student->class }} ({{ $student->section }})</td>
                                                <td>{{ trim($student->firstname . ' ' . $student->lastname) }}</td>
                                                <td>{{ $student->father_name }}</td>
                                                <td>{{ $student->dob ? \Illuminate\Support\Carbon::parse($student->dob)->format('d-m-Y') : '' }}</td>
                                                <td>{{ ucfirst($student->gender) }}</td>
                                                <td class="td-fee-amount">
                                                    {{ number_format((float) ($student->current_fee ?? 0), 2) }}
                                                </td>
                                                <td class="text-right">
                                                    @if ((string) $feesmanage === '1')
                                                        <input type="number" step="any" min="0" name="incrementfee_{{ $student->student_session_id }}" class="form-control input-revised-fee" value="{{ $student->suggested_fee ?? '' }}">
                                                    @elseif ((string) $feesmanage === '2')
                                                        <input type="number" step="any" min="0" name="decrementfee_{{ $student->student_session_id }}" class="form-control input-revised-fee" placeholder="Amount">
                                                    @elseif ((string) $feesmanage === '3')
                                                        <input type="number" step="any" min="0" name="assignfee_{{ $student->student_session_id }}" class="form-control input-revised-fee" value="{{ $student->suggested_fee ?? '' }}">
                                                    @else
                                                        <input type="number" step="any" min="0" name="fee_{{ $student->student_session_id }}" class="form-control input-revised-fee" value="{{ $student->current_fee ?? '' }}">
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="td-empty-notice">
                                                    No students found for the selected criteria.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if (count($resultlist) > 0)
                            <div class="box-footer text-right">
                                <button type="button" class="btn-theme-save" onclick="submitFeeRevise(event)">
                                    Save
                                </button>
                            </div>
                        @endif
                    </form>
                </div>
            @endif
        </section>
    </div>

    <script>
        function getBranchByID(val) {
            if (val) {
                window.location.href = '{{ url("admin/account/studentfee/feerevise") }}/' + val;
            }
        }

        function loadSections(classId, selectedSectionId) {
            var sectionSelect = document.getElementById('section_id');
            sectionSelect.innerHTML = '<option value="">Select</option>';

            if (!classId) return;

            fetch('{{ url("admin/account/studentfee/get-sections") }}/' + classId)
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    data.forEach(function (sec) {
                        var opt = document.createElement('option');
                        opt.value = sec.section_id || sec.id;
                        opt.textContent = sec.section || sec.name;
                        if (selectedSectionId && String(selectedSectionId) === String(sec.section_id || sec.id)) {
                            opt.selected = true;
                        }
                        sectionSelect.appendChild(opt);
                    });
                })
                .catch(function (err) {
                    console.error('Error loading sections:', err);
                });
        }

        function handleFeesManageChange(val) {
            var incGroup = document.getElementById('incrementTypeGroup');
            var incAmt = document.getElementById('incrementAmountGroup');
            var incVal = document.getElementById('incrementValueGroup');

            if (val === '1') { // Increment
                if (incGroup) incGroup.style.display = 'block';
                var selectedRadio = document.querySelector('input[name="is_increment_type"]:checked');
                var radioVal = selectedRadio ? selectedRadio.value : '1';
                handleIncrementTypeChange(radioVal);
            } else if (val === '2') { // Decrement
                if (incGroup) incGroup.style.display = 'none';
                if (incAmt) incAmt.style.display = 'block';
                if (incVal) incVal.style.display = 'none';
            } else {
                if (incGroup) incGroup.style.display = 'none';
                if (incAmt) incAmt.style.display = 'none';
                if (incVal) incVal.style.display = 'none';
            }
        }

        function handleIncrementTypeChange(val) {
            var incAmt = document.getElementById('incrementAmountGroup');
            var incVal = document.getElementById('incrementValueGroup');

            if (val === '1') {
                if (incAmt) incAmt.style.display = 'block';
                if (incVal) incVal.style.display = 'none';
            } else {
                if (incAmt) incAmt.style.display = 'none';
                if (incVal) incVal.style.display = 'block';
            }
        }

        function toggleSelectAll(masterCheckbox) {
            var checkboxes = document.querySelectorAll('.student-checkbox');
            checkboxes.forEach(function (cb) {
                cb.checked = masterCheckbox.checked;
            });
        }

        function showToast(msg) {
            let toast = document.getElementById('appToast');
            if (!toast) {
                toast = document.createElement('div');
                toast.id = 'appToast';
                toast.className = 'fixed top-3.5 right-3.5 z-[9999] w-[230px] max-w-[calc(100vw-2rem)] overflow-hidden rounded-md bg-green-100 border-l-[3px] border-green-500 text-green-700 shadow-md toast-slide-in';
                toast.innerHTML = `
                    <div class="flex items-start gap-2 px-2.5 py-1.5">
                        <div class="flex h-4 w-4 mt-0.5 shrink-0 items-center justify-center rounded-full bg-green-500 text-white text-[9px] font-bold leading-none">✓</div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[11px] font-bold leading-tight">Success</p>
                            <p class="text-[10px] leading-tight mt-0.5 opacity-90 truncate">${msg}</p>
                        </div>
                        <button type="button" onclick="hideToast()" class="text-xs font-bold opacity-60 hover:opacity-100 transition leading-none px-0.5" aria-label="Close">×</button>
                    </div>
                    <div class="toast-progress-track">
                        <div id="toastProgress" class="toast-progress bg-green-500"></div>
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

        function submitFeeRevise(e) {
            e.preventDefault();
            var form = document.getElementById('feeReviseUpdateForm');
            var checked = form.querySelectorAll('.student-checkbox:checked');

            if (checked.length === 0) {
                alert('Please select at least one student.');
                return;
            }

            var formData = new FormData(form);

            fetch('{{ url("admin/account/studentfee/feereviseUpdate") }}', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                if (data.status === 'success') {
                    showToast(data.message || 'Fees updated successfully!');
                    setTimeout(function () {
                        window.location.reload();
                    }, 1200);
                } else {
                    var errMsg = 'Unable to update fees.';
                    if (data.error) {
                        var keys = Object.keys(data.error);
                        if (keys.length > 0) errMsg = data.error[keys[0]];
                    }
                    alert(errMsg);
                }
            })
            .catch(function (err) {
                console.error(err);
                alert('An error occurred while saving.');
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            var classId = '{{ $class_post }}';
            var sectionId = '{{ $section_post }}';
            if (classId) {
                loadSections(classId, sectionId);
            }
        });
    </script>
@endsection
