@extends('layout.default')
@section('styles')
    <style>
        .loading-image-gif {
            width: 42px;
            display: block;
            margin: 15px auto 0 auto;
        }

        .title-load {
            text-align: center;
        }

        .loading-running {
            padding: 50px;
        }

        .content-load {
            text-align: center;
            padding: 8px 16px;
        }

        #loading_error, #loading_success {
            display: none;
        }

        .image-success {
            margin-bottom: 15px;
        }
    </style>
@endsection
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{__($page_title)}}
                    <span class="d-block text-muted pt-2 font-size-sm">{{__('export_data_checkin_out_and_payroll2')}}</span>
                </h3>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-7">
                <form method="POST">
                    <div class="row align-items-center">
                        <div class="col-lg-12">
                            <div class="row align-items-center">
                                <div class="col-md-3 my-2 my-md-0 pr-0">
                                    <div class="align-items-center">
                                        <label class="mr-3 mb-0 d-none d-md-block">{{ __('Month') }}</label>
                                        <input class="form-control datepicker--month"
                                               placeholder="{{__('Month')}}"
                                               name="month" type="text"
                                               value="{{ $defaultDate ? date('m-Y', strtotime($defaultDate)) : date('m-Y') }}"
                                               autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="mr-3 mb-0 d-none d-md-block cc_cursor">&nbsp</label>
                                    <button type="submit" id="formTimeKeeping" class="btn btn-primary font-weight-bold mr-3 formTimeKeeping">
                                        {{ __('Xu???t d??? li???u') }}
                                    </button>
                                    <button type="button" onclick="deleteData()" class="btn btn-danger font-weight-bold mr-3 ">
                                        {{ __('X??a d??? li???u') }}
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="loading_export" tabindex="-1" role="dialog" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="loading-running">
                    <div id="loading_run">
                        <h3 class="title-load">D??? li???u ??ang ???????c xu???t ra</h3>
                        <p class="content-load">C?? th??? m???t 20 ?????n 30 ph??t ????? ghi d??? li???u v??o Google Sheet. Vui l??ng kh??ng t???t trang Web n??y ????? tr??nh tr?????ng h???p l???i x???y ra !!</p>
                        <img class="loading-image-gif" src="{{ asset('media/gif-loading.gif') }}" alt="">
                    </div>

                    <div id="loading_error">
                        <h3 class="title-load">D??? li???u ??ang b??? gi??n ??o???n</h3>
                        <p class="content-load">Vui l??ng ???n "X??c Nh???n" ????? ti???p t???c xu???t d??? li???u v??o Google Sheet</p>
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary font-weight-bold confirmEx">
                                {{ __('X??c Nh???n') }}
                            </button>
                        </div>
                    </div>

                    <div id="loading_success">
                        <h3 class="title-load">D??? li???u ???? ???????c xu???t ra th??nh c??ng.</h3>
                        <img class="loading-image-gif image-success" src="{{ asset('media/success.gif') }}" alt="">
                        <div class="d-flex justify-content-center">
                            <button type="submit" onclick="reloadPage()" class="btn btn-secondary font-weight-bold" data-dismiss="modal">
                                {{ __('????ng') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $('.datepicker--month').datepicker({
            todayHighlight: true,
            format: 'mm-yyyy',
            disableTouchKeyboard: true,
            autoclose: true,
            language: 'vi',
            zIndexOffset: 100,
            viewMode: "months",
            minViewMode: "months",
            endDate: new Date()
        });


        // call submit l???n ?????u ti??n
        $('#formTimeKeeping').click(function (e) {
            $('#loading_export').modal('show');
            $(this).attr("disabled", true);
            e.preventDefault();
            read_data()
        });

        // l??c xu???t b??? l???i call l???i
        $('.confirmEx').click(function (e) {
            $('#loading_export').modal('show');
            $('#loading_run').show();
            $('#loading_error').hide();
            $(".confirmEx").attr("disabled", true);
            e.preventDefault();
            read_data()
        });

        function read_data() {
            let month_ = $('.datepicker--month').val();

            $.ajax({
                url: '{{ url('api/data/read-data') }}',
                data: {month: month_},
                type: "POST",
                success: function (result_data) {

                },
                complete: function () {
                    connectGoogleSheet(month_);
                }
            });
        }

        async function connectGoogleSheet(month_) {
            await exportData(month_);
        }

        async function exportData(month_) {
            const result = await $.ajax({
                url: '{{ url('api/data/exportData') }}',
                data: {month: month_},
                type: "POST",
                success: function (result_data) {
                    $('#loading_run').hide();
                    $('#loading_success').show();
                    $('.formTimeKeeping').removeAttr("disabled");
                },
                error: function () {
                    $('#loading_run').hide();
                    $('#loading_error').show();
                    $('#loading_success').hide();
                    $(".confirmEx").removeAttr("disabled");
                },
            });

            return result;
        }

        var deleteData = function () {
            Swal.fire({
                title: 'B???n ch???c ch???n mu???n x??a th??ng tin?',
                text: 'Th??ng tin ???????c l???y t??? Google Sheet v??? ????? ?????ng b??? d??? li???u b???ng ch???m c??ng, n???u b???n x??a th?? b???n c?? th??? xu???t l???i d??? li???u m???i !! D??? li???u n??y ch??? x??a ??? HRM, kh??ng x??a trong file Sheet',
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: __("yes"),
                cancelButtonText: __("no"),
                reverseButtons: true,
                showLoaderOnConfirm: true,
                allowOutsideClick: false,
                allowEscapeKey: false,
                preConfirm: function (data) {
                    Swal.showLoading();
                    $.ajax({
                        method: 'POST',
                        url: '{{ url("api/data/clearData") }}',
                        data: { month: $('.datepicker--month').val() }
                    }).done(function(response) {
                        if (response.success) {
                            Swal.fire(
                                __("deleted_title"),
                                response.data ?? 'X??a th??ng tin th??nh c??ng.',
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
                    });
                    return false;
                },
            });
        }

        var reloadPage = function () {
            window.location.reload();
        }
    </script>
@endsection


