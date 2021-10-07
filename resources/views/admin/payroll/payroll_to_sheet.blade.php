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
                    <span
                        class="d-block text-muted pt-2 font-size-sm">{{__('export_data_checkin_out_and_payroll2')}}</span>
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
                                    <button type="button" id="importData"
                                            class="btn btn-primary font-weight-bold mr-3 importData">
                                        {{ __('Ghi dữ liệu') }}
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="loading_export" tabindex="-1" role="dialog" aria-hidden="true" data-keyboard="false"
         data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="loading-running">
                    <div id="loading_run">
                        <h3 class="title-load">Dữ liệu lương đang được ghi vào HRM</h3>
                        <p class="content-load">Vui lòng không tắt trang Web này để tránh trường hợp lỗi xảy ra !!</p>
                        <img class="loading-image-gif" src="{{ asset('media/gif-loading.gif') }}" alt="">
                    </div>

                    <div id="loading_error">
                        <h3 class="title-load">Ghi dữ liệu vào HRM bị gián đoạn</h3>
                        <p class="content-load">Vui lòng ấn "Xác Nhận" để ghi dữ liệu vào HRM hoặc bạn có thể đóng page
                            để ghi lại từ đầu!!</p>
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary font-weight-bold confirmImport">
                                {{ __('Xác Nhận') }}
                            </button>
                        </div>
                    </div>

                    <div id="loading_success">
                        <h3 class="title-load">Dữ liệu đã được ghi vào HRM thành công.</h3>
                        <img class="loading-image-gif image-success" src="{{ asset('media/success.gif') }}" alt="">
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-secondary font-weight-bold"
                                    data-dismiss="modal">
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
        $('#importData').click(function (e) {
            $('#loading_export').modal('show');
            $(this).attr("disabled", true);
            e.preventDefault();
            import_data()
        });

        // lúc xuất bị lỗi call lại
        $('.confirmImport').click(function (e) {
            $('#loading_export').modal('show');
            $('#loading_run').show();
            $('#loading_error').hide();
            $(".confirmImport").attr("disabled", true);
            e.preventDefault();
            import_data()
        });

        function import_data() {
            let month_ = $('.datepicker--month').val();
            $.ajax({
                url: '{{ url('api/data/importData') }}',
                data: {month: month_},
                type: "POST",
                success: function (result_data) {
                    $('#loading_run').hide();
                    $('#loading_success').show();
                    $('.importData').removeAttr("disabled");
                },
                complete: function () {
                },
                error: function () {
                    $('#loading_run').hide();
                    $('#loading_error').show();
                    $('#loading_success').hide();
                    $(".confirmImport").removeAttr("disabled");
                },
            });
        }
    </script>
@endsection


