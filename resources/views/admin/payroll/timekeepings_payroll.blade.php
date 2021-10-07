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
                                        {{ __('Xuất dữ liệu') }}
                                    </button>
                                    <button type="button" onclick="deleteData()" class="btn btn-danger font-weight-bold mr-3 ">
                                        {{ __('Xóa dữ liệu') }}
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
                        <h3 class="title-load">Dữ liệu đang được xuất ra</h3>
                        <p class="content-load">Có thể mất 20 đến 30 phút để ghi dữ liệu vào Google Sheet. Vui lòng không tắt trang Web này để tránh trường hợp lỗi xảy ra !!</p>
                        <img class="loading-image-gif" src="{{ asset('media/gif-loading.gif') }}" alt="">
                    </div>

                    <div id="loading_error">
                        <h3 class="title-load">Dữ liệu đang bị gián đoạn</h3>
                        <p class="content-load">Vui lòng ấn "Xác Nhận" để tiếp tục xuất dữ liệu vào Google Sheet</p>
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary font-weight-bold confirmEx">
                                {{ __('Xác Nhận') }}
                            </button>
                        </div>
                    </div>

                    <div id="loading_success">
                        <h3 class="title-load">Dữ liệu đã được xuất ra thành công.</h3>
                        <img class="loading-image-gif image-success" src="{{ asset('media/success.gif') }}" alt="">
                        <div class="d-flex justify-content-center">
                            <button type="submit" onclick="reloadPage()" class="btn btn-secondary font-weight-bold" data-dismiss="modal">
                                {{ __('Đóng') }}
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


        // call submit lần đầu tiên
        $('#formTimeKeeping').click(function (e) {
            $('#loading_export').modal('show');
            $(this).attr("disabled", true);
            e.preventDefault();
            read_data()
        });

        // lúc xuất bị lỗi call lại
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
                title: 'Bạn chắc chắn muốn xóa thông tin?',
                text: 'Thông tin được lấy từ Google Sheet về để đồng bộ dữ liệu bảng chấm công, nếu bạn xóa thì bạn có thể xuất lại dữ liệu mới !! Dữ liệu này chỉ xóa ở HRM, không xóa trong file Sheet',
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
                                response.data ?? 'Xóa thông tin thành công.',
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


