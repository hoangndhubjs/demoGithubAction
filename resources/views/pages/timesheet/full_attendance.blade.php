@extends('layout.default')

@section('content')
    <div class="d-flex flex-row">

        @include('admin.constants.nav')

        <div class="flex-row-fluid ml-lg-8">
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">{{ __("employee_full_attendance") }}
                        <span class="d-block text-muted pt-2 font-size-sm"></span>
                    </h3>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.timesheet.full_attendance') }}">
                    @csrf
                    <div class="form-group">
                        <select type="text" name="full_attendances[]" id="full_attendances" class="form-control select2" multiple="multiple">
                            @foreach($employees as $employee)
                                <option value="{{ $employee->user_id }}" selected>{{ $employee->getFullName() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-light-primary"><x-icon type="svg" category="General" icon="Save"/>{{ __('xin_update') }}</button>
                </form>
            </div>
        </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        window._select_ids = {{ $employees->pluck('user_id')->toJson() }};
        window._input_select_employee_url = '{{ route('employees.ajax.list_employees') }}';
        $(document).ready(function () {
            let options = {
                ajax: {
                    delay: 250,
                    url: window._input_select_employee_url,
                    dataType: 'json',
                    data: function (params) {
                        return {
                            query: params.term, // search term
                            page: params.page
                        }
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        let results = data.data.map(function (employee) {
                            employee.id = employee.user_id;
                            return employee;
                        });
                        return {
                            results: results,
                            pagination: {
                                more: data.current_page < data.last_page
                            }
                        }
                    },
                },
                placeholder: "Type for search assignee",
                minimumInputLength: 0,
                templateResult: formatEmployee,
                templateSelection: formatEmployeeSelection,
                initSelection: function(element, callback) {
                    $.ajax({
                        url: window._input_select_employee_url,
                        data: {
                            ids: window._select_ids,
                            withoutPaginate: true
                        },
                    }).then(function (data) {
                        if (window._select_ids.length > 0 && data.success) {
                            callback(data.data);
                        } else {
                            callback([]);
                        }
                    });
                }
            };
            $('#full_attendances').select2(options);
        });

        var formatEmployee = function(employee) {
            if (employee.loading) {
                return employee.text;
            }
            var container = $('' +
                '<div class="d-flex align-items-center bg-hover-light-primary p-3 rounded-lg">\n' +
                '<div class="flex-shrink-0 mr-4 mt-lg-0 mt-3">\n' +
                '<div class="symbol symbol-circle symbol-lg-50">\n' +
                '<img src="'+employee.profile_picture+'" alt="image">\n' +
                '</div>\n' +
                '</div>\n' +
                '<div class="d-flex flex-column">\n' +
                '<span class="text-dark font-weight-bold font-size-h4 mb-0">'+employee.first_name+' '+employee.last_name+'</span>\n' +
                '<span class="text-muted font-weight-bold">'+employee.email+'</span>\n' +
                '</div>\n' +
                '</div>');
            return container;
        }

        var formatEmployeeSelection = function(employee) {
            if(employee.text) {
                return employee.text;
            }
            return employee.first_name+' '+employee.last_name;
        }
    </script>
@endsection
