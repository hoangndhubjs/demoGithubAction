@extends('layout.default')
@section('styles')
    <style>
        .is_select_end_trail{display: none;}
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12 mb-5">
            <div class="card card-custom card-stretch">
                <div class="">
                    <div class="card-header flex-wrap border-0 pt-6 pb-0">
                        <div class="card-title">
                            <h3 class="card-label">{{ $page_title }}
                                <span class="d-block text-muted pt-2 font-size-sm">Danh sách tiền vay</span></h3>
                        </div>
                        <div class="card-toolbar">
                            <!--begin::Button-->
                            <!--end::Button-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex flex-row">

        @include('admin.employee.nav.nav')

        <div class="flex-row-fluid ml-lg-8">
            <!--begin::Card-->
            <div class="card card-custom gutter-b h-100">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">{{__($page_title)}}
                            <span class="d-block text-muted pt-2 font-size-sm">{{__('Danh sách tiền vay')}}</span></h3>
                    </div>
                    <div class="card-toolbar">
                        <!--begin::Button-->
                        <div>
                            <a href="#" class="btn btn-primary font-weight-bolder" data-toggle="modal" data-target="#loan_deductions_form">
                    <span class="svg-icon svg-icon-md">
                        {{  \App\Classes\Theme\Metronic::getSVG('media/svg/icons/Code/Plus.svg') }}
                        <!--end::Svg Icon-->
                    </span>{{__('Thêm mới')}}</a>
                        </div>
                        <!--end::Button-->
                    </div>
                </div>
                <div class="card-body card-body-compensations">
                    <!--begin: Search Form-->
                    <div class="mb-7">
                        <div class="row align-items-center">
                        </div>
                    </div>
                    <!--end::Search Form-->
                    <!--begin: Datatable-->
                    <div class="datatable datatable-bordered datatable-head-custom" id="loan_deductions_list"></div>
                    <!--end: Datatable-->
                </div>
            </div>
            <!--end::Modal-->
            <!-- begin: Modal -->
            <div class="modal fade" id="loan_deductions_form" tabindex="-1" role="dialog" aria-labelledby="loan_deductions_form" aria-hidden="true" data-form-url="{{ route('employee_managements.createFormloanDeductions')}}">
                <div class="modal-dialog modal-dialog-centered dialog_compensations" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __("Thêm mới khoản vay") }}</h5>
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
        </div>
    </div>

@endsection
@section('scripts')
    <script>
        window._tables = {
            'loan_deductions_list': {
                'url': '{{ route('employee_managements.ajax.list_loanDeductions') }}?user_id='+{{ request()->route('id') }},
                'columns': [
                    {
                        field: 'STT',
                        title: '{{ __('STT') }}',
                        sortable: false,
                        textAlign: 'center',
                        width: 30,
                        template: function (row, index) {
                            return ++index;
                        }
                    },
                    {
                        field: 'xin_salary_loan_options',
                        title: '{{ __('xin_salary_loan_options') }}',
                        sortable: false,
                        textAlign: 'center',
                        template: function (row) {
                            let optionLoan = {
                                0: {'text': __('xin_loan_other_sd_title') },
                                1: {'text': __('xin_loan_ssc_title') },
                                2: {'text': __('xin_loan_hdmf_title') },
                            };
                            return optionLoan[row.loan_options].text
                        }
                    },
                    {
                        field: 'dashboard_xin_title',
                        title: '{{ __('dashboard_xin_title') }}',
                        sortable: false,
                        textAlign: 'center',
                        template: function (row) {
                            return row.loan_deduction_title
                        }
                    },
                    {
                        field: 'monthly_installment',
                        title: '{{ __('loan_deduction_amount') }}',
                        sortable: false,
                        textAlign: 'center',
                        template: function (row) {
                            return window._userCurrency(parseInt(row.monthly_installment))
                        }
                    },
                    {
                        field: 'xin_is_deduction_salary',
                        title: '{{ __('xin_is_deduction_salary') }}',
                        sortable: false,
                        textAlign: 'center',
                        template: function (row) {
                            let allowance_taxable = {
                                0: {'text': 'Có'},
                                1: {'text':'Không'},
                            };
                            return allowance_taxable[row.status].text
                        }
                    },
                    {
                        field: 'xin_reason',
                        title: '{{ __('xin_reason') }}',
                        sortable: false,
                        textAlign: 'center',
                        template: function (row) {
                            return row.reason
                        }
                    },
                    {
                        field: 'action',
                        title: '{{ __('xin_action') }}',
                        sortable: false,
                        autoHide: false,
                        textAlign: 'center',
                        width: 90,
                        template: function (row) {
                            let html = '';
                            html = "<a href='#' data-toggle='modal' data-target='#loan_deductions_form' data-id='"+row.loan_deduction_id+"'  class='btn btn-sm btn-clean btn-icon' title='{{ __('edit') }}'>"+'<span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2020-10-29-133027/theme/html/demo1/dist/../src/media/svg/icons/Design/Edit.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\n' +
                                '    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\n' +
                                '        <rect x="0" y="0" width="24" height="24"/>\n' +
                                '        <path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "/>\n' +
                                '        <rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"/>\n' +
                                '    </g>\n' +
                                '</svg><!--end::Svg Icon--></span>'+"</a>";
                            html += "<a  href='#' onclick='deleteRequest("+row.loan_deduction_id+")' class='btn btn-sm btn-clean btn-icon' title='{{ __('cancel') }}'>"+'<span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2020-10-29-133027/theme/html/demo1/dist/../src/media/svg/icons/Home/Trash.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\n' +
                                '    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\n' +
                                '        <rect x="0" y="0" width="24" height="24"/>\n' +
                                '        <path d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z" fill="#000000" fill-rule="nonzero"/>\n' +
                                '        <path d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"/>\n' +
                                '    </g>\n' +
                                '</svg><!--end::Svg Icon--></span>'+"</a>";
                            return html;
                        }
                    }
                ],
                'search_from': '#overtime_list_search_form'
            }
        };
        $('#loan_deductions_form').on('shown.bs.modal', function (e) {
            window._loading("#loan_deductions_form .modal-body");
            let button = $(e.relatedTarget);
            let id = button.data('id');
            let url = $('#loan_deductions_form').data('form-url');
            $.ajax({
                url: url,
                data: {
                    id: id,
                    employee_id : {{ request()->route('id') }},
                    module :'loan_deductions'
                }
            }).done(function (response) {
                window._loading("#loan_deductions_form .modal-body", false);
                $("#loan_deductions_form .modal-body").html(response);
            }).fail(function (jqXHR, status){
                window._loading("#loan_deductions_form .modal-body", false);
                toastr.error(__("order_form_fetch_error"));
                window._display_alert("#loan_deductions_form .modal-body", __("order_form_fetch_error"));
            });
        });
        $('#loan_deductions_form').on('hidden.bs.modal', function (e) {
            $("#loan_deductions_form .modal-body").html("");
        });
        var deleteRequest = function (id) {
            if (id) {
                Swal.fire({
                    title: __("are_you_sure_delete_it"),
                    text: __("you_wont_able_revert_it"),
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: __("yes"),
                    cancelButtonText: __("no"),
                    reverseButtons: true,
                    showLoaderOnConfirm: true,
                    preConfirm: function (data) {
                        Swal.showLoading();
                        $.ajax({
                            method: 'DELETE',
                            url: '{{ route("employee_managements.deleteRequestloanDeductions") }}',
                            data: {id: id}
                        }).done(function (response) {
                            if (response == true) {
                                Swal.fire(
                                    __("deleted_title"),
                                    response.data ?? __("record_deleted"),
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
                            setTimeout(function () {
                                Swal.close()
                            }, 800);
                            window._tables.loan_deductions_list.datatable.reload();
                            /*setTimeout(function () {window.location.reload()}, 400);*/
                        });
                        return false;
                    },
                    allowOutsideClick: function () {
                        return !Swal.isLoading()
                    }
                });
            }
        }
        $("#compensations_filler_month").on('change', function (e) {
            e.preventDefault();
            window._tables.loan_deductions_list.datatable.reload();
        })
    </script>
    <script src="{{ mix('js/list.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ mix('js/employee/profile.js') }}"></script>
@endsection
