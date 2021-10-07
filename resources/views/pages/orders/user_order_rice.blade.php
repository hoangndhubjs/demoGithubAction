@extends('layout.default')

@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{ __("Danh sách đặt cơm hôm nay") }}
                    <span class="d-block text-muted pt-2 font-size-sm">Ngày {{ date('d-m-Y')}}</span>
                </h3>
            </div>
            <div class="card-toolbar">
                <!--begin::Button-->
                <button class="btn btn-primary font-weight-bolder mr-3" data-toggle="modal" data-target="#create_meal_order" {{ $menu ?? 'disabled' }}>
                    <x-icon type="svg" category="Design" icon="Flatten"/> {{ __("place_order") }}
                </button>
                <button onclick="confirmExportExcel()" class="btn btn-primary font-weight-bolder mr-3" data-placement="bottom" data-toggle="tooltip" data-theme="dark" title="Chốt và xuất excel">
                    <x-icon type="svg" category="Navigation" icon="Double-check"/> {{ __("Chốt đơn") }}
                </button>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <!--begin: Search Form-->
            <div class="mb-7">
                <div class="row align-items-center">
                    <div class="col-lg-12 col-xl-12">
                        <form id="user_order_search_form">
                            <div class="row align-items-center">
                                <div class="col-xl-2 col-md-3 my-2 my-md-0">
                                    <select class="form-control selectpicker" id="status" name="status" style="width: 100%">
                                        <option value="">{{__('xin_choose_status')}}</option>
                                        <option value="0">Chưa chốt</option>
                                        <option value="1">Đã chốt</option>
                                    </select> 
                                </div>
                                <div class="col-xl-2 col-md-3 my-2 my-md-0">
                                    <select class="form-control selectpicker" id="type" name="type" style="width: 100%">
                                        <option value="">{{__('Chọn buổi ăn')}}</option>
                                        <option value="1">Buổi trưa</option>
                                        <option value="2">Buổi tối</option>
                                    </select>
                                </div>
                                <div class="col-xxl-3 col-lg-6 col-sm-6 mt-5 my-md-0 d-flex">
                                    <button type="submit" class="btn btn-primary px-6 mr-3 font-weight-bold">{{ __('search') }}</button>
                                    <button type="button" id="reset_form" class="btn btn-light-primary px-6 font-weight-bold">{{ __('xin_reset') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--end::Search Form-->
            <!--begin: Datatable-->
            <div class="datatable datatable-bordered datatable-head-custom table-responsive position-relative" id="user_order_rice"></div>
            <!--end: Datatable-->
            <!--begin: Order Food -->
            <!--end: Order Food -->
        </div>
    </div>
    <!-- begin: Modal -->
    <div class="modal fade" id="create_meal_order" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="create_meal_order" aria-hidden="true" data-form-url="{{ route('orders.ajax.create_meal_order')}}">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __("place_order") }}</h5>
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
@endsection

@section('scripts')
    <script>
        window.meal_order_remove_url = '{{ route("orders.ajax.delete_meal_order") }}';
        window.export_order_url = '{{ route("orders.export-order-rice") }}';
        window._tables = {
            'user_order_rice': {
                'url': '{{ route('orders.ajax.user_order_rice') }}',
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
                        field: 'name_user',
                        title: '{{ __('Tên nhân viên') }}',
                        autoHide: false,
                        sortable: false,
                        width: 85,
                        template: function (row) {
                            return row.employee.first_name + ' ' + row.employee.last_name;
                        }
                    },
                    {
                        field: 'create_date',
                        title: '{{ __('xin_created_date') }}',
                        sortable: false,
                        autoHide: false,
                        width: 85,
                        template: function (row) {
                            return _userDate(row.create_date);
                        }
                    },
                    {
                        field: 'type_label',
                        title: '{{ __('Buổi') }}',
                        sortable: false,
                        width: 85,
                        autoHide: false,
                        template: function (row) {
                            let types = {
                                1: 'label-danger',
                                2: 'label-warning'
                            }
                            return "<span class='label label-lg "+types[row.type]+" label-inline'>"+row.type_label+"</span>"
                        }
                    },
                    {
                        field: 'mon_chinh',
                        title: '{{ __('Món chính') }}',
                        sortable: false,
                        width: 85,
                        template: function (row) {
                            var content = "";
                            row.mon_chinh.forEach(function (item) {
                                content += row.foods[item].title+"<br/>";
                            })
                            return content;
                        }
                    },
                    {
                        field: 'mon_phu',
                        title: '{{ __('Món phụ') }}',
                        sortable: false,
                        width: 85,
                        template: function (row) {
                            var content = "";
                            row.mon_phu.forEach(function (item) {
                                content += row.foods[item].title+"<br/>";
                            })
                            return content;
                        }
                    },
                    {
                        field: 'mon_rau',
                        title: '{{ __('Món rau') }}',
                        sortable: false,
                        width: 85,
                        template: function (row) {
                            var content = "";
                            row.mon_rau.forEach(function (item) {
                                content += row.foods[item].title+"<br/>";
                            })
                            return content;
                        }
                    },
                    {
                        field: 'price',
                        title: '{{ __('Giá tiền') }}',
                        sortable: false,
                        width: 85,
                        template: function (row) {
                            return _userCurrency(row.price);
                        }
                    },
                    {
                        field: 'action',
                        title: '{{ __('Hành động') }}',
                        sortable: false,
                        autoHide: false,
                        width: 90,
                        textAlign: 'center',
                        template: function (row) {
                            let html = "";
                            if (row.status === 1) {
                                return "<span class='label label-lg label-success label-inline'>"+row.status_label+"</span>";
                            } else {
                                html = "<a href='#' data-toggle='modal' data-target='#create_meal_order' data-id='"+row.id+"' class='btn btn-sm btn-clean btn-icon' title='{{ __('edit') }}'>"+window.iconEdit+"</a>";
                                html += "<a href='#' onclick='deleteOrder("+row.id+")' class='btn btn-sm btn-clean btn-icon' title='{{ __('cancel') }}'>"+window.iconDelete+"</a>";
                                return html;
                            }
                        }
                    }
                ],
                'search_from': '#user_order_search_form'
            }
        };
    </script>
    <script>
        $('#reset_form').click(function(){
            $('#user_order_search_form')[0].reset();
            $(":input.form-control").trigger('change');
            window._tables.user_order_rice && window._tables.user_order_rice.datatable.reload();
        });
    </script>
    <script src="{{ mix('js/list.js') }}" type="text/javascript"></script>
    <script src="{{ mix('js/orders/meal_order.js') }}" type="text/javascript"></script>
@endsection

