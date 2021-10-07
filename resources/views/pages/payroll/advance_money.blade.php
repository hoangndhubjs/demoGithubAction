@extends('layout.default')
@section('content')
    <style>
        ul.list_advanced {
            margin: 0;
            padding-left: 0;
            list-style: none;
        }
    </style>
    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{__($page_title)}}
                    <span class="d-block text-muted pt-2 font-size-sm">{{__('Danh sách tạm ứng')}}</span></h3>
            </div>
            <div class="card-toolbar">
                <form action="" id="advanceForm" class="d-flex flex-wrap">
                    <div class="mx-2 mb-2">
                        <input class="form-control" id="date_advance_money" autocomplete="off" name="month"  placeholder="{{ __('Chọn tháng') }}" type="text" value="">
                    </div>
                    <div class="mx-2  mb-2">
                        <button type="submit" class="btn btn-primary px-6 font-weight-bold mr-3">
                            <span> {{  \App\Classes\Theme\Metronic::getSVG('media/svg/icons/General/Search.svg') }}</span>
                            {{ __('xin_search') }}
                        </button>
                    </div>
                    <div class="mr-10 ml-2 ml-md-0 mb-2">
                        <button  id="resetForm" class="btn btn-light-primary px-6 font-weight-bold">{{ __('xin_reset') }}</button>
                    </div>
                </form>
                <a href="#" class="btn btn-primary font-weight-bolder ml-2 mr-5 mb-2" data-toggle="modal" data-target="#update_advance_request">
                    <span class="svg-icon svg-icon-md">
                        {{  \App\Classes\Theme\Metronic::getSVG('media/svg/icons/Code/Plus.svg') }}
                        {{__('xin_add_new')}}
                    </span>
                </a>
                <!--end::Button-->

            </div>
        </div>
        <div class="card-body">
            <!--begin: Datatable-->
            <div class="datatable datatable-bordered datatable-head-custom 222" id="advance_list"></div>
            <!--end: Datatable-->
        </div>
    </div>
    <!-- begin: Modal -->
    <div class="modal fade" id="update_advance_request" tabindex="-1" role="dialog" aria-labelledby="update_advance_request" aria-hidden="true" data-form-url="{{ route('payrolls.ajax.create_form')}}">
        <div class="modal-dialog modal-dialog-centered dialog_compensations" style="max-width:700px;" role="document">
            <div class="modal-content">
                <div class="modal-header justify-content-center border-bottom-0">
                    <h2>{{ __("Thêm mới tạm ứng") }}</h2>
                </div>
                <div class="modelnote">
                    <p class="font-italic text-danger px-5 mb-0">
                        <span>
                            Lưu ý: Bạn chỉ được ứng lương tối đa 50% từ đầu tháng đến thời điểm hiện tại. Bạn được nhận tối đa <span id="salary_advance">xxx.xxx</span>
                        </span>
                        <span id="advanced_user">
{{--                            <ul class="list_advanced">--}}
{{--                                <li><span>1.</span> Bạn đã ứng 10.000.000 tháng 10</li>--}}
{{--                            </ul>--}}
                        </span>
                    </p></div>
                <div class="modal-body form-container" style="min-height:150px">
                </div>
            </div>
        </div>
    </div>
    <!-- end: Modal -->
@endsection
@section('scripts')
    <script>
        window._tables = {
            'advance_list': {
                'url': '{{ route('payrolls.listAdvance_money') }}',
                'columns': [
                    {
                        field: 'STT',
                        title: '{{ __('STT') }}',
                        sortable: false,
                        width:30,
                        textAlign: 'center',
                        // sortable: true,
                        template: function (row, index) {
                            return ++index;
                        }
                    },
                    {
                        field: 'created_at',
                        title: '{{ __('Ngày tạo yêu cầu') }}',
                        sortable: false,
                        width:130,
                        textAlign: 'center',
                        template: function (row) {
                            return moment(row.created_at).format('DD-MM-YYYY');
                        }
                    },
                    {
                        field: 'advane',
                        title: '{{ __('Tháng tạm ứng') }}',
                        sortable: false,
                        textAlign: 'center',
                        template: function (row) {
                            return row.month_year
                        }
                    },
                    {
                        field: 'ct',
                        title: '{{ __('Công ty') }}',
                        sortable: false,
                        textAlign: 'center',
                        template: function (row) {
                            return row.company_asset.company_name
                        }
                    },
                    {
                        field: 'bank_nummber',
                        title: '{{ __('xin_e_details_acc_number') }}',
                        width: 150,
                        sortable: false,
                        textAlign: 'left',
                        template: function (row) {
                            if (row.bank_account != null){
                                var bank_name = row.bank_account.bank_name != null ? row.bank_account.bank_name : '';
                                var account_title = row.bank_account.account_title != null ? row.bank_account.account_title : '';
                                var account_number = row.bank_account.account_number != null ? row.bank_account.account_number : '';
                            }else{
                                var bank_name = '';
                                var account_title = '';
                                var account_number = '';
                            }
                            let bankHtml  = '<p class="m-0">'+bank_name +'</p>';
                                 bankHtml += '<p class="m-0">'+account_title+'</p>';
                                 bankHtml += '<p class="m-0">'+account_number+'</p>';
                            return  bankHtml;
                        }
                    },
                    {
                        field: 'advan_money',
                        title: '{{ __('advan_money') }}',
                        width: 150,
                        sortable: false,
                        textAlign: 'center',
                        template: function (row) {
                            return  window._userCurrency(parseInt(row.advance_amount));
                        }
                    },
                    {
                        field: 'stats',
                        title: '{{ __('kpi_status') }}',
                        width: 150,
                        sortable: false,
                        textAlign: 'center',
                        template: function (row) {
                            let status = {
                                0: {'class': ' label-light-warning'},
                                1: {'class': ' label-light-primary'},
                                2: {'class': ' label-light-success'},
                                3: {'class': ' label-light-danger'},

                            };
                            let status_type = {
                                0: {'lable': 'Chưa thanh toán'},
                                1: {'lable':'Đã duyệt'},
                                2: {'lable':'Đã thanh toán'},
                                3: {'lable':'Không duyệt'},
                            };
                            return '<span class="label label-lg font-weight-bold ' + status[row.status].class + ' label-inline">' + status_type[row.status].lable + '</span>';
                        }
                    },
                    {
                        field: 'hành động',
                        title: '{{ __('Thao tác') }}',
                        sortable: false,
                        autoHide: false,
                        textAlign: 'center',
                        width: 100,
                        template: function (row) {
                            let html = "";
                            if (row.status == 0) {
                                // html += "<a class='btn btn-sm btn-clean btn-icon' title='{{ __('Duyệt') }}'>"+window.iconsEdit+"</a>";
                                html += "<a href='javascript:;' onclick='deleteCompensation("+row.advance_salary_id+")' class='btn btn-sm btn-clean btn-icon' title='{{ __('Không duyệt') }}'>"+window.iconDelete+"</a>";
                            } else {
                                html = "";
                            }
                            return html;
                        }
                    },
                ],
                'search_from': '#advanceForm'
            }
        };
        var deleteCompensation = function (id) {
            if (id) {
                let title_ = __("Bạn có muốn xóa đơn tạm ứng này ?");
                Swal.fire({
                    title: title_ ,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: __("yes"),
                    cancelButtonText: __("no"),
                    reverseButtons: true,
                    showLoaderOnConfirm: true,
                    preConfirm: function (data) {
                        Swal.showLoading();
                        $.ajax({
                            method: 'delete',
                            url: '{{ route("payrolls.delete_advance_money") }}',
                            data: {id: id}
                        }).done(function (response) {
                            console.log(response.success);
                            if (response.success) {
                                Swal.fire(
                                    __("delete_success"),
                                    response.data ?? __("record_deleted"),
                                    "success",
                                    false,
                                    false
                                );
                                setTimeout(function () {
                                    Swal.close()
                                }, 800);
                            } else {
                                Swal.fire(
                                    __("error_title"),
                                    response.error ?? __("error"),
                                    "error",
                                    false,
                                    false
                                );
                            }

                            window._tables.advance_list.datatable.reload();
                        });
                        return false;
                    },
                    allowOutsideClick: function () {
                        return !Swal.isLoading()
                    }
                });
            }

        }
        //Modal
        $('#update_advance_request').on('shown.bs.modal', function (e) {
            window._loading("#update_advance_request .modal-body");
            let button = $(e.relatedTarget);
            let id = button.data('id');
            let url = $('#update_advance_request').data('form-url');
            $.ajax({
                url: url,
                data: {
                    id: id
                }
            }).done(function (response) {
                    window._loading("#update_advance_request .modal-body", false);
                    $("#update_advance_request .modal-body").html(response);
                })
                .fail(function (jqXHR, status){
                    window._loading("#update_advance_request .modal-body", false);
                    toastr.error(__("order_form_fetch_error"));
                    window._display_alert("#update_advance_request .modal-body", __("order_form_fetch_error"));
                });
        });
        // keyup
        $("#kt__search_query").keyup(function() {
            let string = $(this).val();
            window._tables.advance_list.datatable.reload();
        });

        $("#date_advance_money").datepicker({
            format: "mm-yyyy",
            startView: "months",
            minViewMode: "months",
            autoclose: true,
            setDate: new Date(),
            endDate: "toDay",
            language: window._locale
        })

        $('#resetForm').click(function(){
            $('#advanceForm').trigger("reset");
        });
    </script>

    <script src="{{ mix('js/payroll/payroll.js') }}" type="text/javascript"></script>
    <script src="{{ mix('js/list.js') }}" type="text/javascript"></script>

@endsection
