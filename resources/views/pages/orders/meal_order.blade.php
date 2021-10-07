@extends('layout.default')

@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{ __("place_order") }}
                    <span class="d-block text-muted pt-2 font-size-sm"></span>
                </h3>
            </div>
            <div class="card-toolbar">
                <!--begin::Button-->
                <button class="btn btn-primary font-weight-bolder" data-toggle="modal" data-target="#create_meal_order" {{ $menu ?? 'disabled' }}>
                    <x-icon type="svg" category="Design" icon="Flatten"/> {{ __("place_order") }}
                </button>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <!--begin: Search Form-->
            <div class="mb-7">
                <div class="row align-items-center">
                    <div class="col-lg-12 col-xl-12">
                        <form id="meal_order_search_form">
                            <div class="row align-items-center">
                                <div class="col-md-3 my-2 my-md-0">
                                    <x-daterangepicker id="order_create" name="order_create"/>
                                </div>
                                <div class="col-lg-3 col-xl-4 mt-5 mt-lg-0">
                                    <button type="submit" class="btn btn-light-primary px-6 font-weight-bold">{{ __('search') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--end::Search Form-->
            <!--begin: Datatable-->
            <div class="datatable datatable-bordered datatable-head-custom table-responsive position-relative" id="meal_order"></div>
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

@section('styles')
@endsection

@section('scripts')
    <script>
        window.meal_order_remove_url = '{{ route("orders.ajax.delete_meal_order") }}';
        window._tables = {
            'meal_order': {
                'url': '{{ route('orders.ajax.meal_orders') }}',
                'columns': [
                    {
                        field: 'create_date',
                        title: '{{ __('Ngày đặt') }}',
                        autoHide: false,
                        type: 'date',
                        format: 'YYYY-MM-DD H:m:s',
                        width: 90,
                        template: function (row) {
                            return _userDate(row.create_date);
                        }
                    },
                    {
                        field: 'mon_chinh',
                        title: '{{ __('Món chính') }}',
                        sortable: false,
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
                        title: '{{ __('Tổng tiền') }}',
                        textAlign: "right",
                        template: function (row) {
                            return _userCurrency(row.price);
                        }
                    },
                    {
                        field: 'type_label',
                        title: '{{ __('Buổi') }}',
                        sortable: false,
                        width: 80,
                        textAlign: 'center',
                        template: function (row) {
                            let types = {
                                1: 'label-danger',
                                2: 'label-warning'
                            }
                            return "<span class='label label-lg "+types[row.type]+" label-inline'>"+row.type_label+"</span>"
                        }
                    },
                    {
                        field: 'status',
                        title: '{{ __('Trạng thái') }}',
                        sortable: false,
                        autoHide: false,
                        width: 90,
                        textAlign: 'center',
                        template: function (row) {
                            let html = "";
                            if (row.status === 1) {
                                return "<span class='label label-lg label-success label-inline'>"+row.status_label+"</span>";
                            } else {
                                html = "<a href='#' data-toggle='modal' data-target='#create_meal_order' data-id='"+row.id+"' class='btn btn-sm btn-clean btn-icon' title='{{ __('edit') }}'>"+'<x-icon type="flaticon" icon="edit" class="text-warning"/>'+"</a>";
                                html += "<a href='#' onclick='deleteOrder("+row.id+")' class='btn btn-sm btn-clean btn-icon' title='{{ __('cancel') }}'>"+'<x-icon type="flaticon" icon="delete" class="text-danger"/>'+"</a>";
                                return html;
                            }
                        }
                    }
                ],
                'search_from': '#meal_order_search_form'
            }
        };
    </script>
    <script src="{{ mix('js/list.js') }}" type="text/javascript"></script>
    <script src="{{ mix('js/orders/meal_order.js') }}" type="text/javascript"></script>
@endsection

