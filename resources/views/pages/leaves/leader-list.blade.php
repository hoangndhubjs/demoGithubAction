@extends('layout.default')

@section('content')
    <!--begin::Card-->
    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{__('list_leave')}}
                    <span class="d-block text-muted pt-2 font-size-sm">{{__('list_leave_approve')}}</span></h3>
            </div>
            {{--<div class="card-toolbar">
                <!--begin::Button-->
                <a href="#" class="btn btn-primary font-weight-bolder" data-toggle="modal" data-target="#add_leave_modal">
                    <span class="svg-icon svg-icon-md">
                        {{  \App\Classes\Theme\Metronic::getSVG('media/svg/icons/Code/Plus.svg') }}
                    </span>{{__('Thêm mới')}}</a>
                <!--end::Button-->
            </div>--}}
        </div>
        <div class="card-body">
            <!--begin: Search Form-->
            <div class="mb-7">
                <div class="row align-items-center">
                    <div class="col-lg-12 col-xl-12">
                        <form id="leave_list_search_form" autocomplete="off">
                            <div class=" row align-items-center">
                                <div class="col-md-3 my-2">
                                    <label class="d-block required">{{ __("xin_date") }}</label>
                                    <input class="form-control datepicker-default"
                                           placeholder="{{__('xin_date')}}"
                                           name="created_at" type="text" value="">
                                </div>
                                <div class="col-md-3 my-2">
                                    <label class="d-block required">{{ __("xin_companies") }}</label>
                                    <select class="form-control selectpicker" id="company_id" name="company_id" style="width: 100%" >
                                        <option value="">{{__('xin_companies')}}</option>
                                        @foreach($companies as $key => $company)
                                            <option value="{{$key}}">{{$company}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 my-2">
                                    <label class="d-block required">{{ __("xin_department") }}</label>
                                    <select class="form-control selectpicker" id="department_id" name="department_id">
                                        <option value="">{{__('xin_department')}}</option>
                                    </select>
                                </div>
                                <div class="col-md-3 my-2">
                                    <label class="d-block required">{{ __("dashboard_employees") }}</label>
                                    <input class="form-control" placeholder="{{__('Tên hoặc ID')}}" name="employee" type="text" value="">
                                </div>
                                <div class="col-md-3 my-2 my-md-0">
                                    <label class="d-block required">{{ __("xin_is_salary_type") }}</label>
                                    <select class="form-control selectpicker" id="is_salary" name="is_salary" style="width: 100%" >
                                        <option value="">{{__('xin_is_salary_type')}}</option>
                                        <option value="0">{{__('not_salary')}}</option>
                                        <option value="1">{{__('is_salary')}}</option>
                                    </select>
                                </div>
                                <div class="col-md-3 my-2 my-md-0">
                                    <label class="d-block required">{{ __("dashboard_xin_status") }}</label>
                                    <select class="form-control selectpicker" id="status" name="status" style="width: 100%" >
                                        <option value="">{{__('dashboard_xin_status')}}</option>
                                        @foreach($status as $k => $v)
                                            <option value="{{$k}}">{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mt-8 text-right">
                                    <button type="submit" class="btn btn-primary font-weight-bold">{{ __('search') }}</button>
                                    <button type="button" class="btn btn-light-primary font-weight-bold" id="reset_form">{{ __('xin_reset') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--end::Search Form-->
            <!--begin: Datatable-->
            <div class="datatable datatable-bordered datatable-head-custom" id="leaves_list"></div>
            <!--end: Datatable-->
        </div>
    </div>
    <!--end::Card-->
    <!--begin::Modal-->
    <div class="modal fade" id="add_leave_modal" tabindex="-1" role="dialog" aria-labelledby="add_leave_modal1" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="add_leave_modal1">{{__('Xin nghỉ phép')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-lg-12 col-xl-12">
                        @include('pages.leaves.create', ['leaveTypes'=>$leaveTypes])
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Modal-->
@endsection

@section('scripts')
    <script>
        $('.datepicker-default').datepicker({
            todayHighlight: true,
            format: 'dd-mm-yyyy',
            disableTouchKeyboard: true,
            autoclose: true,
            language:'vi',
            zIndexOffset:100
        });
        function htmlDecode(input) {
            let doc = new DOMParser().parseFromString(input, "text/html");
            return doc.documentElement.textContent;
        }
        window._tables = {
            'leaves_list': {
                'url': '{{ route('leaves.leader.ajax.list') }}',
                'columns': [
                    {
                        field: 'RecordID',
                        title: '#',
                        sortable: false,
                        width: 10,
                        type: 'number',
                        autoHide: false,
                        selector: {
                            class: ''
                        },
                        textAlign: 'center',
                    },
                    {
                        field: 'created_at',
                        title: '{{ __('created_send') }}',
                        width: 90,
                        sortable: false,
                        template: function (row) {
                            return (row.created_at != null)?moment(row.created_at).format('DD-MM-YYYY HH:mm:ss'):''
                        }
                    },
                    {
                        field: 'employee_name',
                        title: '{{ __('employee_name') }}',
                        width: 110,
                        sortable: false,
                        template: function (row) {
                            return (row.employee != null)?row.employee.first_name+' '+row.employee.last_name:''
                        }
                    },
                    {
                        field: 'leave_type_id',
                        title: '{{ __('Các lý do nghỉ') }}',
                        width: 150,
                        sortable: false,
                        template: function (row) {
                            return (row.leave_type != null)?row.leave_type.type_name:''
                        }
                    },
                    {
                        field: 'reason',
                        title: '{{ __('Lý do') }}',
                        sortable: false,
                        width: 150,
                        template: function (row) {
                            return row.reason
                        }
                    },
                    {
                        field: 'confirm',
                        title: '{{ __('Trạng thái') }}',
                        sortable: false,
                        textAlign: 'center',
                        width: 150,
                        template: function (row) {
                            var status = {
                                0: {'class': ' label-light-danger'},
                                1: {'class': ' label-light-primary'},
                            };
                            let string = '';
                            if(row.status != 2){
                                string = ' label-light-danger';
                            } else {
                                string = status[row.confirm].class;
                            }
                            return '<span class="label label-lg font-weight-bold ' + string + ' label-inline">' + row.confirm_list + '</span>';
                        }
                    },
                    {
                        field: 'is_salary',
                        title: '{{ __('Tính lương') }}',
                        sortable: false,
                        textAlign: 'left',
                        width:120,
                        template: function (row) {
                            var status = {
                                0: {'class': ' label-light-danger'},
                                1: {'class': ' label-light-primary'},
                            };
                            let html = '<span class="label label-lg font-weight-bold ' + status[row.is_salary].class + ' label-inline">' + row.salary + '</span>';
                            /*if(row.confirm != 1 && row.status != 3){
                                html += "<a href='#' onclick='updateIsSalary("+row.leave_id+", "+row.is_salary+")' class='btn btn-sm btn-clean btn-icon' title='{{ __('change_is_salary') }}'>"+window.iconExchange+"</a>";
                            }*/
                            return html;
                        }
                    },
                    {
                        field: 'total_day_leave',
                        title: '{{ __('Thời lượng yêu cầu') }}',
                        sortable: false,
                        width: 150,
                        template: function (row) {
                            return htmlDecode(row.time_detail)
                        }
                    },
                    {
                        field: 'leave_types',
                        title: '{{ __('Loại nghỉ') }}',
                        width: 110,
                        sortable: false,
                        template: function (row) {
                            var status = {
                                1: {'class': ' label-light-danger'},
                                2: {'class': ' label-light-warning'},
                                3: {'class': ' label-light-primary'},
                            };
                            return '<span class="label label-lg font-weight-bold ' + status[row.leave_time_types].class + ' label-inline">' + row.leave_types + '</span>';
                        }
                    },
                    {
                        field: 'action',
                        title: '{{ __('xin_action') }}',
                        sortable: false,
                        autoHide: false,
                        textAlign: 'center',
                        template: function (row) {
                            let status = {
                                2: {'class': ' label-light-danger'},
                                1: {'class': ' label-light-primary'},
                            };
                            let html = "";
                            if(row.status == 1){
                                html += "<a href='#' onclick='appoveRequest("+row.leave_id+", 2)' class='btn btn-sm btn-clean btn-icon' title='{{ __('approve') }}'>"+window.iconCheck+"</a>";
                                html += "<a href='#' onclick='appoveRequest("+row.leave_id+", 3)' class='btn btn-sm btn-clean btn-icon' title='{{ __('not_approve') }}'>"+window.iconClose+"</a>";
                            }/* else if (row.status == 2 && row.confirm == 0) {
                                html += "<a href='#' onclick='appoveRequest("+row.leave_id+", 3)' class='btn btn-sm btn-clean btn-icon' title='{{ __('not_approve') }}'>"+window.iconClose+"</a>";
                            } else if(row.status == 3 && row.confirm == 0) {
                                html += "<a href='#' onclick='appoveRequest("+row.leave_id+", 2)' class='btn btn-sm btn-clean btn-icon' title='{{ __('approve') }}'>"+window.iconCheck+"</a>";
                            }*/
                            return html;
                        }
                    }
                ],
                'search_from': '#leave_list_search_form'
            }
        };

        let appoveRequest = function (id, approve) {
            Swal.fire({
                title: __("are_you_sure_action"),
                /*text: __("you_wont_able_revert_it"),*/
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: __("yes"),
                cancelButtonText: __("no"),
                reverseButtons: true,
                showLoaderOnConfirm: true,
                preConfirm: function (data) {
                    Swal.showLoading();
                    $.ajax({
                        method: 'POST',
                        url: '{{ route("leaves.leader.ajax.approve") }}',
                        data: { id: id, approve: approve }
                    }).done(function(response) {
                        if (response.success) {
                            Swal.fire(
                                __("update_title"),
                                response.data ?? __("record_updated"),
                                "success",
                                false,
                                false
                            );
                        } else {
                            Swal.fire(
                                __("error_title"),
                                response.error ?? __("error"),
                                "error",
                                false,
                                false
                            );
                        }
                        setTimeout(function () {Swal.close()}, 800);
                        window._tables.leaves_list.datatable.reload();
                    }).fail(function (jqXHR, status) {
                        let response = jqXHR.responseJSON;
                        Swal.fire(
                            __("error_title"),
                            response.errors ?? __("error"),
                            "error",
                            false,
                            false
                        );
                    });
                    return false;
                },
                allowOutsideClick: function () { return !Swal.isLoading() }
            });
        };

        $('#department_id').select2({
            placeholder: "Bộ phận",
            ajax: {
                url: '{{ route("leaves.ajax.department") }}',
                dataType: 'json',
                data: function (params) {
                    return {
                        q: $.trim(params.term),
                        company_id : $('#company_id').val()
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        $('#company_id').change(function () {
            $('#department_id').val(null).trigger('change');
        });

        $('#reset_form').click(function () {
            $('#leave_list_search_form').trigger("reset");
            $('#leave_list_search_form select').trigger("change");
            $('#leave_list_search_form').submit();
        });

        let updateIsSalary = function (id, is_salary) {
            if(is_salary == 1){is_salary = 0}else{is_salary = 1}
            Swal.fire({
                title: __("are_you_sure_action"),
                /*text: __("you_wont_able_revert_it"),*/
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: __("yes"),
                cancelButtonText: __("no"),
                reverseButtons: true,
                showLoaderOnConfirm: true,
                preConfirm: function (data) {
                    Swal.showLoading();
                    $.ajax({
                        method: 'POST',
                        url: '{{ route("leaves.leader.ajax.is_salary") }}',
                        data: { id: id, is_salary: is_salary }
                    }).done(function(response) {console.log(response);
                        if (response.success) {
                            Swal.fire(
                                __("update_title"),
                                response.data ?? __("record_updated"),
                                "success",
                                false,
                                false
                            );
                        } else {
                            Swal.fire(
                                __("error_title"),
                                response.error ?? __("error"),
                                "error",
                                false,
                                false
                            );
                        }
                        setTimeout(function () {Swal.close()}, 800);
                        window._tables.leaves_list.datatable.reload();
                    });
                    return false;
                },
                allowOutsideClick: function () { return !Swal.isLoading() }
            });
        }
    </script>
    <script src="{{ mix('js/list.js') }}" type="text/javascript"></script>
@endsection
