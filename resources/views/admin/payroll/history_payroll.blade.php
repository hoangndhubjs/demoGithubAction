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
            top: 50%;c
            transform: translate(-50%, -50%);
            border-radius: 100%;
        }
        .close_compens {
            position: absolute;
            top: 30%;
            right: 3%;
        }
        .datepicker table tr td span.disabled, .datepicker table tr td span.disabled:hover {
            background: none;
            color: #777777;
            cursor: default;
            opacity: 0.60;
            cursor: no-drop !important;
        }
    </style>
    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{__($page_title)}}
                    <span class="d-block text-muted pt-2 font-size-sm">{{__('listsPayroll')}}</span></h3>
            </div>
            <div class="card-toolbar">
                <!--begin::Button-->
                <button id="exportExcel" class="btn btn-primary font-weight-bolder mr-5" data-toggle="modal" data-target="#update_overtime_request">
                    <span class="svg-icon svg-icon-md">
                        {{  \App\Classes\Theme\Metronic::getSVG('media/svg/icons/Files/Export.svg') }}
                    </span>
                    {{__('exportExcel')}}
                </button>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <!--begin: Search Form-->
            <div class="mb-7">
                <form action="" id="payroll_list_search_form">
                    <input type="hidden" name="history_payroll" value="1">
                    <div class="row align-items-center">
                        <div class="col-lg-12 col-xl-12">
                            <div class="row align-items-center">
                                <div class="col-md-3 my-2 my-md-0 pr-0">
                                    <div class="align-items-center">
                                        <label class="mr-3 mb-0 d-none d-md-block cc_cursor">{{ __('left_company') }}</label>
                                        <select class="form-control select2 is-valid" id="kt_select2_1" name="company_id">
                                            <option value="0" selected disabled>{{ __('xin_acc_all') }}</option>
                                            @foreach($all_companies as $company_id)
                                                <option {{ $company_id->company_id == 1 ?? 'selected' }} value="{{ $company_id->company_id }}">{{ $company_id->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 my-2 my-md-0 pr-0">
                                    <div class="align-items-center">
                                        <label class="mr-3 mb-0 d-none d-md-block">{{ __('empolyee__') }}</label>
                                        <select class="form-control select2 is-valid" id="kt_select2_empolyee__" name="employee__">
                                            <option value="0" selected disabled>{{ __('empolyee__') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 my-2 my-md-0 pr-0">
                                    <div class="align-items-center">
                                        <label class="mr-3 mb-0 d-none d-md-block">{{ __('Month') }}</label>
                                        <input type="" autocomplete="off"  name="month_payslip" id="month_filter" class=" month_filter form-control" value="{{ $date_before }}" placeholder="">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-xl-3 mt-5 mt-lg-0">
                                    <label class="mr-3 mb-0 d-none d-md-block cc_cursor">&nbsp</label>
                                    <div class="row ml-2">
                                        <button type="submit" class="btn btn-primary px-6 font-weight-bold mr-3">{{ __('xin_search') }}
                                            <span> {{  \App\Classes\Theme\Metronic::getSVG('media/svg/icons/General/Search.svg') }}</span>
                                        </button>
                                        <button  id="resetForm" class="btn btn-light-primary px-6 font-weight-bold">{{ __('Reset') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!--end::Search Form-->
            <!--begin: Datatable-->
            <div class="datatable datatable-bordered datatable-head-custom 222" id="payroll_list"></div>
            <!--end: Datatable-->
        </div>
    </div>
@endsection
@section('scripts')
    <script>

        window._tables = {
            'payroll_list': {
                'url': '{{ route('payrolls.ajax.lists') }}',
                'columns': [
                    {
                        field: 'STT',
                        title: '{{ __('STT') }}',
                        sortable: false,
                        width:30,
                        template: function (row, index) {
                            return ++index;
                        }
                    },
                    {
                        field: 'employee_name',
                        title: '{{ __('employee_name') }}',
                        sortable: false,
                        template: function (row) {
                            return row.employee_salary.last_name +' '+ row.employee_salary.first_name
                        }
                    },
                    {
                        field: 'ct',
                        title: '{{ __('Công ty') }}',
                        sortable: false,
                        template: function (row) {
                            return row.employee_company ? row.employee_company.name : '';
                        }
                    },
                    {
                        field: 'Month',
                        title: '{{ __('Month') }}',
                        sortable: false,
                        textAlign: 'center',
                        template: function (row) {
                            return row.salary_month
                        }
                    },
                    {
                        field: 'bank_nummber',
                        title: '{{ __('xin_e_details_acc_number') }}',
                        width: 150,
                        sortable: false,
                        textAlign: 'center',
                        template: function (row) {
                            return row.bank_account != null ? row.bank_account.account_number : ''
                        }
                    },
                    {
                        field: 'basic_salary',
                        title: '{{ __('Lương theo hợp đồng') }}',
                        width: 150,
                        sortable: false,
                        template: function (row) {
                            return  window._userCurrency(row.basic_salary);
                        }
                    },
                    {
                        field: 'total_salary_month',
                        title: '{{ __('xin_total_salary_month') }}',
                        width: 150,
                        sortable: false,
                        template: function (row) {
                            return  window._userCurrency(parseInt(row.grand_net_salary));
                        }
                    },
                    {
                        field: 'is_approved',
                        title: '{{ __('dashboard_xin_status') }}',
                        sortable: false,
                        autoHide: false,
                        textAlign: 'center',
                        width: 100,
                        template: function (row) {
                            return row.year_to_date;
                        }
                    },
                ],
                'search_from': '#payroll_list_search_form'
            }
        };

        $("#exportExcel").click(function () {
            $(this).attr("disabled", "disabled");
            let company_id =  $("#kt_select2_1").val();
            let employee_id = $("#kt_select2_empolyee__").val();
            let date = $("#month_filter").val();
            {{--let url = '{{ route('payrolls.exportExcelSalary') }}';--}}
            let url = '{{ route('payrolls.payroll_month') }}';
            {{--//--}}
            {{--let compnay = company_id != null ? company_id : '';--}}
            {{--let emploee = employee_id != null ? employee_id : '';--}}
            {{--let date_month = date ?? {{ $date_before }};--}}
            $.ajax({
            method: 'GET',
                url: url,
            data: {
                    date: date,
                    company_id : company_id,
                   employee_id : employee_id,
                }
           }).done(function (response) {

            if(response){
                $("#exportExcel").removeAttr('disabled');
                if(response.errors){
                    Swal.fire({
                        icon: 'error',
                        text: 'Không có dữ liệu lương tháng '+date_month,
                    });
                }else{
                window.location.href = url+'?date='+date+'&company_id='+company_id+'&employee_id='+employee_id+'&module=history';
                }
            }


            });
        });

    </script>
    <script src="{{ mix('js/payroll/payroll.js') }}" type="text/javascript"></script>
    <script src="{{ mix('js/list.js') }}" type="text/javascript"></script>

@endsection
