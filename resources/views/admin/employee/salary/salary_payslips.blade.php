@extends('layout.default')

@section('content')

    <div class="d-flex flex-column-fluid">
        <div class="w-100">
            <div class="d-flex flex-row">
                <div class="flex-row-fluid">
                    <div class="card card-custom card-stretch">
                        <div class="card-header">
                            <div class="card-title">
                                <h3 class="card-label">{{ __('xin_payslips_history')  }}
                                    <i class="mr-2"></i>
                                </h3>
                            </div>
                        </div>

                        <div class="card-body p-2">
                            <div class="datatable datatable-bordered datatable-head-custom table-responsive position-relative" id="payslip"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- begin: Modal -->
    <div class="modal fade" id="model_detail_salary" tabindex="-1" role="dialog" aria-labelledby="model_detail_salary" aria-hidden="true" data-form-url="{{ route('ajax.form_detail')}}">
        <div style="max-width: 50%" class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __("xin_income") }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body form-container" style="min-height:150px">
                </div>
            </div>
        </div>
    </div>
    <!-- end: Modal -->
{{--{{ dd($salary_user_all) }}--}}
@endsection
@section('scripts')
    <script type="text/javascript">
        function wagesTypeName(wages_type) {
            var waytype = '';
            if(wages_type == 1){
                waytype = 'Lương cơ bản';
            }else if(wages_type == 2){
                waytype = 'Lương thử việc';
            }else if(wages_type == 3){
                waytype = 'partime/học việc';
            }
            return waytype;
        }
        window._tables = {
            'payslip': {
                'url': '{{ route('salary_payslips_list') }}',
                'columns': [
                    {{--{--}}
                    {{--    field: 'action',--}}
                    {{--    title: '{{ __('xin_action') }}',--}}
                    {{--    sortable: false,--}}
                    {{--    width: 40,--}}
                    {{--    template: function (row, index) {--}}
                    {{--        return row.payslip_id;--}}
                    {{--    }--}}
                    {{--},--}}
                    {
                        field: 'month_payment',
                        title: '{{ __('xin_payroll_month_year') }}',
                        template: function (row) {
                            return  row.salary_month;
                        }
                    },
                    {
                        field: 'basic_salary',
                        width:80,
                        title: '{{ __('xin_title_salary_profile') }}',
                        template: function (row) {
                            return  window._userCurrency(row.basic_salary);
                        }
                    },
                    {
                        field: 'watype',
                        title: '{{ __('xin_salary_type') }}',
                        textAlign: "center",
                        // width: 120,
                        template: function (row) {
                            return wagesTypeName(row.wages_type);
                        }
                    },
                    {
                        field: 'total_all_late_month',
                        // autoHide: false,
                        title: '{{ __('xin_title_time_late_work') }} phút',
                        template: function (row) {
                            return row.total_all_late_month;
                        }
                    },
                    {
                        field: 'total_day_datcom',
                        // autoHide: false,
                        title: '{{ __('xin_total_day_datcom') }}',
                        template: function (row) {
                            return row.total_day_datcom;
                        }
                    },
                    {
                        field: 'total_price_datcom',
                        // autoHide: false,
                        width:80,
                        title: '{{ __('xin_total_price_datcom') }}',
                        template: function (row) {
                            return window._userCurrency(parseInt(row.total_price_datcom)) ;
                        }
                    },
                    {
                        field: 'total_all_work_month',
                        // autoHide: false,
                        width:80,
                        title: '{{ __('xin_salary_total_month') }}',
                        template: function (row) {
                            return row.total_all_work_month ;
                        }
                    },
                    {
                        field: 'total_statutory_deductions',
                        // autoHide: false,
                        width:80,
                        title: '{{ __('xin_employee_set_statutory_deductions') }}',
                        template: function (row) {
                            return window._userCurrency(parseInt(row.total_statutory_deductions)) ;
                        }
                    },
                    {
                        field: 'total_loan',
                        // autoHide: false,
                        width:80,
                        title: '{{ __('xin_employee_set_loan_deductions') }}',
                        template: function (row) {
                            return window._userCurrency(parseInt(row.total_loan)) ;
                        }
                    },
                    {
                        field: 'money_pc',
                        // autoHide: false,
                        width:80,
                        title: '{{ __('xin_payroll_allowances') }}',
                        template: function (row) {
                            return window._userCurrency(row.money_pc) ;
                        }
                    },
                    {
                        field: 'net_salary',
                        // autoHide: false,
                        width:80,
                        title: '{{ __('xin_total_salary_month') }}',
                        template: function (row) {
                            return window._userCurrency(parseInt(row.net_salary));
                        }
                    },
                    {
                        field: 'employee_id',
                        // autoHide: false,
                        width:80,
                        title: '{{ __('kpi_status') }}',
                        template: function (row) {
                            return row.status == 2 ? '<span class="text-success">Đã thanh toán</span>' : '<span class="text-success">Chưa thanh toán</span>' ;
                        }
                    },
                    {
                        field: 'action',
                        // autoHide: false,
                        width:80,
                        title: '{{ __('xin_action') }}',
                        template: function (row) {
                            let html = "";
                            let url = 'create_form_pdf/' + row.payslip_id;
                            html = "<a href='#' data-toggle='modal' data-target='#model_detail_salary' data-id='"+row.payslip_id+"' class='btn btn-sm btn-clean btn-icon' title='Chi tiết'>"+'<x-icon type="flaticon" icon="edit" class="text-warning"/>'+"</a>";
                            html += "<a target='_blank' href='"+url+"'  class='btn btn-sm btn-clean btn-icon' title='xin_for_pdf'>"+'<i class="icon-xl la la-file-pdf text-primary"></i>'+"</a>";
                            return html;
                        }
                    }
                ],
            }
        };
    //
        $('#model_detail_salary').on('shown.bs.modal', function (e) {
            window._loading("#model_detail_salary .modal-body");
            let button = $(e.relatedTarget);
            let id = button.data('id');
            let url = $('#model_detail_salary').data('form-url');
            $.ajax({
                url: url,
                data: {
                    id: id
                }
            })
                .done(function (response) {
                    window._loading("#model_detail_salary .modal-body", false);
                    $("#model_detail_salary .modal-body").html(response);
                })
                .fail(function (jqXHR, status){
                    window._loading("#model_detail_salary .modal-body", false);
                    toastr.error(__("order_form_fetch_error"));
                    window._display_alert("#model_detail_salary .modal-body", __("order_form_fetch_error"));
                });
        });
        $('#model_detail_salary').on('hidden.bs.modal', function (e) {
            $("#model_detail_salary .modal-body").html("");
        });
    </script>
    <script type="text/javascript" src="{{ mix('js/employee/profile.js') }}"></script>
    <script src="{{ mix('js/list.js') }}" type="text/javascript"></script>
@endsection
