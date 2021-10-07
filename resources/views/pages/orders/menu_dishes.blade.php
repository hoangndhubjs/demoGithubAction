@extends('layout.default')

@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{ __("Danh sách món ăn") }}
                    <span class="d-block text-muted pt-2 font-size-sm"></span>
                </h3>
            </div>
            <div class="card-toolbar">
                <!--begin::Button-->
                @if($can_set_menu && in_array(Auth::user()->employee_id, $can_set_menu))
                    <button class="btn btn-primary font-weight-bolder mr-3" data-toggle="modal" data-target="#add_new_dishes">
                        <x-icon type="svg" category="Design" icon="Flatten"/> {{ __("xin_add_new_dishes") }}
                    </button>
                @endif
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <!--begin: Search Form-->
            <div class="mb-7">
                <div class="row align-items-center">
                    <div class="col-lg-12 col-xl-12">
                        <form id="menu_dishes_search_form">
                            <div class="row align-items-center">
                                <div class="col-md-3 my-2 my-md-0">
                                    <input class="form-control datepicker_menu_dishes" placeholder="{{__('Chọn ngày tạo')}}" name="create_date" autocomplete="off">
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
            <div class="datatable datatable-bordered datatable-head-custom table-responsive position-relative" id="menu_dishis"></div>
            <!--end: Datatable-->
        </div>
    </div>
    <!-- begin: Modal -->
    <div class="modal fade" id="add_new_dishes" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="add_new_dishes" aria-hidden="true" data-form-url="{{ route('orders.ajax.add_menu_dishes')}}">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __("Tạo món cho bữa ăn") }}</h5>
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
        let can_set_menu = @json($can_set_menu);
        let employee_id = @json(auth()->user()->employee_id);
        let array = can_set_menu.toString().split(',');
        let check_employee_id = array.includes(employee_id.toString());
       
        window.menu_dishes_remove_url = '{{ route("orders.ajax.delete_menu_dishes") }}';
        window._tables = {
            'menu_dishis': {
                'url': '{{ route('orders.ajax.menu_dishes') }}',
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
                        field: 'create_date',
                        title: '{{ __('xin_created_date') }}',
                        autoHide: false,
                        sortable: false,
                        width: 85,
                        template: function (row) {
                            return _userDate(row.create_date);
                        }
                    },
                    {
                        field: 'an_trua',
                        title: '{{ __('xin_leave_buoi') }}',
                        sortable: false,
                        width: 85,
                        autoHide: false,
                        template: function (row) {
                            let types = {
                                1: 'label-danger',
                                2: 'label-warning'
                            }
                            let meal = {
                                1: 'Bữa trưa',
                                2: 'Bữa tối'
                            }
                            return "<span class='label label-lg "+types[row.an_trua]+" label-inline'>"+meal[row.an_trua]+"</span>"
                        }
                    },
                    {
                        field: 'mon_chinh',
                        title: '{{ __('order_primary_food') }}',
                        sortable: false,
                        width: 200,
                        template: function (row) {
                            var content = "";
                            row.mon_chinh.forEach(function (index, item) {
                                if(typeof row.foods[1] !==  "undefined" && typeof row.foods[1][item] !==  "undefined"){
                                    content += row.foods[1][item].title+"<br/>";
                                }
                            })
                            if (content == "") {
                                content += "Lỗi hiển thị do trùng với món phụ hoặc rau";
                            }
                            return content;
                        }
                    },
                    {
                        field: 'mon_phu',
                        title: '{{ __('order_secondary_food') }}',
                        sortable: false,
                        width: 200,
                        template: function (row) {
                            var content = "";
                            row.mon_phu.forEach(function (index, item) {
                                if(typeof row.foods[2] !==  "undefined" && typeof row.foods[2][item] !==  "undefined"){
                                    content += row.foods[2][item].title+"<br/>";
                                }
                            })
                            if (content == "") {
                                content += "Lỗi hiển thị do trùng với món chính hoặc rau";
                            }
                            return content;
                        }
                    },
                    {
                        field: 'mon_rau',
                        title: '{{ __('order_vegatable_food') }}',
                        sortable: false,
                        width: 200,
                        template: function (row) {
                            var content = "";
                            row.mon_rau.forEach(function (index, item) {
                                if(typeof row.foods[3] !==  "undefined" && typeof row.foods[3][item] !==  "undefined"){
                                    content += row.foods[3][item].title+"<br/>";
                                }
                            })
                            if (content == "") {
                                content += "Lỗi hiển thị do trùng với món chính hoặc phụ";
                            }
                            return content;
                        }
                    },
                    {
                        field: 'action',
                        title: '{{ __('kpi_status') }}',
                        sortable: false,
                        width: 85,
                        textAlign: 'center',
                        template: function (row) {
                            let html = "";
                            if (row.status === 1) {
                                html += "<span class='label label-lg label-success label-inline'>"+'{{ __('Đã chốt') }}'+"</span>";
                            } else { 
                                if (!check_employee_id) {
                                    html += "<span class='label label-lg label-warning label-inline'>"+'{{ __('Chưa chốt') }}'+"</span>";
                                } else {
                                    html += "<a href='#' onclick='deleteMenu("+row.id+")' class='btn btn-sm btn-clean btn-icon' title='{{ __('cancel') }}'>"+window.iconDelete+"</a>";
                                }
                            }
                            return html;
                        }
                    }
                ],
                'search_from': '#menu_dishes_search_form'
            }
        };
    </script>
    <script>
         $('.datepicker_menu_dishes').datepicker({
            todayHighlight: true,
            format: 'dd-mm-yyyy',
            disableTouchKeyboard: true,
            autoclose:true,
            language:'vi'
        });
        $('#reset_form').click(function(){
            $('#menu_dishes_search_form')[0].reset();
            $(":input.form-control").trigger('change');
            window._tables.menu_dishis && window._tables.menu_dishis.datatable.reload();
        });
    </script>
    <script src="{{ mix('js/list.js') }}" type="text/javascript"></script>
    <script src="{{ mix('js/orders/meal_order.js') }}" type="text/javascript"></script>
@endsection

