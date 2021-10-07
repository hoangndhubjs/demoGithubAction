@extends('layout.default')
@section('content')
    <style>
        .dots_ps {
            position: relative;
        }
        .dots_as {
            position: absolute;
            width: 5px;
            height: 5px;
            content: 'a';
            left: -10px;
            top: 50%;
            transform: translate(-50%, -50%);
            border-radius: 100%;
        }
        .close_compens {
            position: absolute;
            top: 30%;
            right: 3%;
        }
    </style>
    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{__($page_title)}}
                    <span class="d-block text-muted pt-2 font-size-sm">{{__('attendance_monthly_list_by_employee')}}</span></h3>
            </div>
            <div class="card-toolbar">
            </div>
        </div>
        <div class="card-body">
            <!--begin: Search Form-->
            <div class="mb-7">
                <form action="" id="payroll_list_search_form">
                    <div class="row align-items-center">
                        <div class="col-lg-12">
                            <div class="row align-items-center">
                                <div class="col-md-3 my-2 my-md-0 pr-0">
                                    <div class="align-items-center">
                                        <label class="mr-3 mb-0 d-none d-md-block">{{ __('Nhập tên nhân viên') }}</label>
                                        <input type="" autocomplete="off"  name="employee_name" class=" form-control" value="" placeholder="{{ __('Nhập tên nhân viên') }}">
                                    </div>
                                </div>
                                <div class="col-md-3 my-2 my-md-0 pr-0">
                                    <div class="align-items-center">
                                        <label class="mr-3 mb-0 d-none d-md-block">{{ __('Month') }}</label>
                                        <input class="form-control datepicker-month"
                                               placeholder="{{__('Month')}}"
                                               name="month" type="text" value="{{date('m-Y')}}" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="mr-3 mb-0 d-none d-md-block cc_cursor">&nbsp</label>
                                    <button type="submit" class="btn btn-primary font-weight-bold mr-3">{{ __('xin_search') }}
                                        <span> {{  \App\Classes\Theme\Metronic::getSVG('media/svg/icons/General/Search.svg') }}</span>
                                    </button>
                                    <button  id="resetForm" class="btn btn-light-primary px-6 font-weight-bold">{{ __('xin_reset') }}</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <!--end::Search Form-->
            <!--begin: Datatable-->
            <div class="datatable datatable-bordered datatable-head-custom 222" id="attendance_list"></div>
            <!--end: Datatable-->
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $('.datepicker-month').datepicker({
            todayHighlight: true,
            format: 'mm-yyyy',
            disableTouchKeyboard: true,
            autoclose: true,
            language:'vi',
            zIndexOffset:100,
            viewMode: "months",
            minViewMode: "months"
        });

        window._tables = {
            'attendance_list': {
                'url': '{{ route('admin.timesheet.timesheet_by_month') }}',
                'columns': [
                    {
                        field: 'STT',
                        title: '{{ __('STT') }}',
                        sortable: false,
                        width:30,
                        textAlign: 'center',
                        template: function (row, index) {
                            return ++index;
                        }
                    },
                    {
                        field: 'employee_name',
                        title: '{{ __('employee_name') }}',
                        sortable: false,
                        width: 120,
                        template: function (row) {
                            return '<span style="font-weight: bold; cursor: pointer;">'+row.employee.first_name +' '+ row.employee.last_name+'</span>';
                        }
                    },
                    {
                        field: 'employee_id',
                        title: '{{ __('dashboard_employee_id') }}',
                        sortable: false,
                        width: 120,
                        textAlign: 'center',
                        template: function (row) {
                            return row.employee.employee_id
                        }
                    },
                    {
                        field: 'so_cong',
                        title: '{{ __('total_attendance') }}',
                        textAlign: 'center',
                        template: function (row) {
                            let total = row.total_ngaycong/1 + row.total_lam_nua_ngay/2;
                            let html = total + ' công';
                            return html;
                        }
                    },
                    {
                        field: 'total_late',
                        title: '{{ __('total_late') }}',
                        sortable: false,
                        textAlign: 'center',
                        template: function (row) {
                            return row.total_phut_di_muon + ' phút';
                        }
                    },
                    {
                        field: 'total_leave',
                        title: '{{ __('total_leave') }}',
                        sortable: false,
                        textAlign: 'center',
                        template: function (row) {
                            return row.total_phut_ve_som + ' phút';
                        }
                    }
                ],
                'search_from': '#payroll_list_search_form'
            }
        };

        $('#resetForm').click(function(){
            $('#payroll_list_search_form').trigger("reset");
            $('#payroll_list_search_form select').trigger("change");
        });

        $('#attendance_list').on('click', 'td', function(e){
            var rowIndex = $(this).closest('tr').data('row');
            // xử lý click
            let data = window._tables.attendance_list.datatable.getDataSet()[rowIndex];
            let url = "{{route('admin.timesheet.attendance_monthly')}}";
            url +='?id='+data.employee_id+'&month='+data.month;
            window.open(url);
            //window.location.href=url;
        });

    </script>
    <script src="{{ mix('js/payroll/payroll.js') }}" type="text/javascript"></script>
    <script src="{{ mix('js/list.js') }}" type="text/javascript"></script>

@endsection
