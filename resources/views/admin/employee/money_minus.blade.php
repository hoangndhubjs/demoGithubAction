@extends('layout.default')

@section('content')

    <div class="row">
        <div class="col-xl-12 mb-5">
            <div class="card card-custom card-stretch">
                <div class="">
                    <div class="card-header flex-wrap border-0 pt-6 pb-0">
                        <div class="card-title">
                            <h3 class="card-label">{{ $page_title }}</h3>
                        </div>
                        <div class="card-toolbar">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex flex-row">

        @include('admin.employee.nav.nav')

        <div class="flex-row-fluid ml-lg-8">
            <div class="card card-custom card-stretch">

                <div class="card-header py-3">
                    <div class="card-title align-items-start flex-column">
                        <h3 class="card-label font-weight-bolder text-dark">
                            {{ __('money_minus')  }}
                        </h3>
                    </div>

                    <div class="card-toolbar py-3">
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#popupMoneyMinus">
                            {{  \App\Classes\Theme\Metronic::getSVG('media/svg/icons/Code/Plus.svg') }}
                            {{ __('xin_add_new') }}
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="datatable datatable-bordered datatable-head-custom table-responsive position-relative" id="moneyMinus"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade px-0" id="popupMoneyMinus" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="popupMoneyMinus" aria-hidden="true" data-form-url="{{ route('employee_managements.create_form_money_minus', request()->route('id'))}}">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('money_minus') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body1">
                    <div class="card card-custom" style="min-height: 50px">
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
@section('scripts')
    <script type="text/javascript">
        var stt = 1;
        window._tables = {
            'moneyMinus': {
                'url': '{{ route('employee_managements.moneyMinusList', request()->route('id')) }}',
                'columns': [
                    {
                        field: 'stt',
                        title: '{{ __('STT') }}',
                        sortable: false,
                        width: 40,
                        textAlign: "center",
                        template: function (row, index) {
                            return ++index;
                        }
                    },
                    {
                        field: 'title',
                        title: '{{ __('xin_payroll_hourly_wage_title_single') }}',
                        sortable: false,
                        width: 200,
                        // textAlign: "center",
                        template: function (row) {
                            return row.title;
                        }
                    },
                    {
                        field: 'money',
                        title: '{{ __('xin_amount') }}',
                        sortable: false,
                        width: 200,
                        // textAlign: "center",
                        template: function (row) {
                            return window._userCurrency(parseInt(row.money));
                        }
                    },
                    {
                        field: 'month',
                        title: '{{ __('month') }}',
                        sortable: false,
                        template: function (row) {
                            return row.year_month ? moment(row.year_month).format("MM-YYYY") : '';
                        }
                    },
                    {
                        field: 'amount_option',
                        title: '{{ __('kpi_status') }}',
                        sortable: false,
                        width: 200,
                        // textAlign: "center",
                        template: function (row) {
                            let text = {
                                0: {'text': ''},
                                1: {'text': 'Khấu trừ tháng hiện tại'},
                                2: {'text': 'Khấu trừ hàng tháng'},
                            };
                            return text[row.amount_option].text;
                        }
                    },
                    {
                        field: 'action',
                        title: '{{ __('dashboard_xin_status') }}',
                        sortable: false,
                        textAlign: 'center',
                        template: function (row) {
                            var status = {
                                2: {'class': ' label-light-danger'},
                                1: {'class': ' label-light-primary'},
                            };
                            let html = "";
                            html = "<a href='javascript:void(0);' data-toggle='modal' data-target='#popupMoneyMinus' data-id='"+row.id+"' class='btn btn-sm btn-clean btn-icon' title='{{ __('edit') }}'>"+window.iconEdit+"</a>";
                            html += "<a href='javascript:void(0);' onclick='deleteRequest("+row.id+")' class='btn btn-sm btn-clean btn-icon' title='{{ __('cancel') }}'>"+window.iconDelete+"</a>";
                            return html;
                        }
                    }
                ],
            }
        };
        //open file
        {{--$(".openFile").click(function () {--}}
        {{--    $("#excel_upload[type='file']").trigger('click');--}}
        {{--    $("#excel_upload[type='file']").change(function () {--}}
        {{--        // $( > #val').text(this.value.replace(/C:\\fakepath\\/i, ''))--}}
        {{--        var file_name = this.value.replace(/C:\\fakepath\\/i, '')--}}
        {{--        $.ajax({--}}
        {{--            url : '{{ route('readFileExcel') }}',--}}
        {{--            type: 'post',--}}
        {{--            data: {--}}
        {{--                file : file_name--}}
        {{--            },--}}
        {{--        }).done(function (data) {--}}
        {{--            console.log(data);--}}
        {{--        })--}}
        {{--    });--}}
        {{--    // var formData = new FormData($("#exup")[0]);--}}
        {{--    // console.log(formData, $("#excel_upload").val());--}}
        {{--})--}}
        {{--$("#popupExcel").on('shown.bs.modal', function (e){--}}
        {{--});--}}
        function reloadTable() {
            window._tables.moneyMinus.datatable.reload();
        }

        $('#popupMoneyMinus').on('shown.bs.modal', function (e) {
            window._loading("#popupMoneyMinus .card-custom");
            let button = $(e.relatedTarget);
            let id = button.data('id');
            let url = $('#popupMoneyMinus').data('form-url');
            $.ajax({
                url: url,
                data: {
                    id: id
                }
            })
                .done(function (response) {
                    window._loading("#popupMoneyMinus .card-custom", false);
                    $("#popupMoneyMinus .card-custom").html(response);
                })
                .fail(function (jqXHR, status){
                    window._loading("#popupMoneyMinus .card-custom", false);
                    toastr.error(__("order_form_fetch_error"));
                    window._display_alert("#popupMoneyMinus .card-custom", __("order_form_fetch_error"));
                });
        });

        $('#popupMoneyMinus').on('hidden.bs.modal', function (e) {
            $("#popupMoneyMinus .card-custom").html("");
        });

        function deleteRequest(id) {
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
                        url: '{{ route("employee_managements.deleteMoneyMinusUser") }}',
                        data: { id: id }
                    }).done(function(response) {
                        if (response.success) {
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
                        reloadTable();
                    });
                    return false;
                },
                allowOutsideClick: function () { return !Swal.isLoading() }
            });
        };

    </script>
    <script type="text/javascript" src="{{ mix('js/employee/profile.js') }}"></script>
    <script src="{{ mix('js/list.js') }}" type="text/javascript"></script>
@endsection

