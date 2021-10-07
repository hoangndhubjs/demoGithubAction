@extends('layout.default')

@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{ __("Danh sách đặt cơm theo tháng") }}
                    <span class="d-block text-muted pt-2 font-size-sm">Tháng {{ date('m-Y')}}</span>
                </h3>
            </div>
            <div class="card-toolbar">
                <!--begin::Button-->
                {{-- <button class="btn btn-primary font-weight-bolder mr-3" data-toggle="modal" data-target="#add_new_dishes">
                    <x-icon type="svg" category="Devices" icon="Printer"/> {{ __("Xuất excel") }}
                </button> --}}
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <!--begin: Search Form-->
            <div class="mb-7">
                <div class="row align-items-center">
                    <div class="col-lg-12 col-xl-12">
                        <form id="user_order_month_search_form">
                            <div class="row align-items-center">
                                <div class="col-md-3 my-2 my-md-0">
                                    <select name="month" class="form-control selectpicker">
                                        @for($i=1; $i <= 12; $i++)
                                                <option value="{{ $i < 10 ? '0'.$i : $i }}" @if ($i == date('m'))
                                                    selected
                                                @endif>Tháng {{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-3 my-2 my-md-0">
                                    <input type="text" class="form-control" name="key_word" placeholder="Nhập tên, mã nhân viên">
                                </div>
                                <div class="col-xxl-3 col-lg-6 col-sm-6 mt-5 my-md-0 d-flex">
                                    <button type="submit" class="btn btn-primary mr-3 px-6 font-weight-bold">{{ __('search') }}</button>
                                    <button type="button" id="reset_form" class="btn btn-light-primary px-6 font-weight-bold">{{ __('xin_reset') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--end::Search Form-->
            <!--begin: Datatable-->
            <div class="datatable datatable-bordered datatable-head-custom table-responsive position-relative" id="user_order_rice_month"></div>
            <!--end: Datatable-->
            <!--begin: Order Food -->
            <!--end: Order Food -->
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $('.selectpicker').select2();
        window._tables = {
            'user_order_rice_month': {
                'url': '{{ route('orders.ajax.user_order_rice_month') }}',
                'columns': [
                    {
                        field: 'STT',
                        title: '{{ __('STT') }}',
                        sortable: false,
                        width: 50,
                        textAlign: 'center',
                        template: function (row, index) {
                            return ++index;
                        }
                    },
                    {
                        field: 'full_name',
                        title: '{{ __('employee_name') }}',
                        sortable: false,
                        width: 160,
                        template: function (row) {
                            return row.last_name + ' ' + row.first_name;
                        }
                    },
                    {
                        field: 'employee_id',
                        title: '{{ __('employee_id') }}',
                        autoHide: false,
                        sortable: false,
                        width: 100,
                        textAlign: 'center',
                        template: function (row) {
                            return row.employee_id;
                        }
                    },
                    {
                        field: 'total_order_rice',
                        title: '{{ __('number_of_orders_rice') }}',
                        sortable: false,
                        width: 135,
                        textAlign: 'center',
                        template: function (row) {
                            return row.total_order_rice;
                        }
                    },
                    {
                        field: 'total_price',
                        title: '{{ __('Giá tiền') }}',
                        sortable: false,
                        autoHide: false,
                        textAlign: 'center',
                        width: 100,
                        template: function (row) {
                            return _userCurrency(parseInt(row.total_price));
                        }
                    },
                ],
                'search_from': '#user_order_month_search_form'
            }
        };
    </script>
    <script>
        $('#reset_form').click(function(){
            $('#user_order_month_search_form')[0].reset();
            $(":input.form-control").trigger('change');
            window._tables.user_order_rice_month && window._tables.user_order_rice_month.datatable.reload();
        });
    </script>
    <script src="{{ mix('js/list.js') }}" type="text/javascript"></script>
    {{-- <script src="{{ mix('js/orders/meal_order.js') }}" type="text/javascript"></script> --}}
@endsection

