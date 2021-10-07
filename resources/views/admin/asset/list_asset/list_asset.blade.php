@extends('layout.default')
@section('styles')
    <style>
        .filler_asset {
            padding: 1rem 2rem;
        }
        .detail_width{
            width: 600px;
        }
        .show_asset_image .area_asset_image{
            display: grid;
            grid-template-columns: auto auto auto auto;
            grid-gap: 10px 10px;
        }
        .image_parent, .imageaa {
            position: relative;
        }
        .show_image_child {
            position: absolute;
            bottom: 5%;
            width: 50%;
            left: 15%;
            display: none;
        }
        .show_image_child .close_asset_image {
            position: absolute;
            top: 0;
            right: 5px;
            display: flex;
        }
        .show_image_child .imageaa img{
            box-shadow: 1px 3px 6px 3px #ccc;
            border-radius: 10px;
        }
        .show_asset_image .image_asset__ {
            width: 50px;
            box-shadow: 1px 1px 5px 0 #ccc;
            padding: 5px;
        }
    </style>
@endsection
@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{ __('manage_asset') }}
                    <span class="d-block text-muted pt-2 font-size-sm">Danh sách quán lý tài sản</span>
                </h3>
            </div>
            <div class="card-toolbar">
                <!--begin::Button-->
                <button class="btn btn-primary font-weight-bolder mr-3"  data-toggle="modal" data-target="#createAsset">
                    <x-icon type="svg" category="Code" icon="Plus"/> {{ __("xin_add_new") }}
                </button>
                <!--end::Button-->
            </div>
        </div>
           <div class="filler_asset">
               <form id="asset_list_search_form">
                   <div class="row">
                       <div class="form-group col-md-4">
                               <label>{{__('xin_acc_category')}}</label>
                               <select style="height: 37px" class="select_asset form-control" id="company_select2_ajax" name="assets_category_id">
                                   <option value="" disabled selected>{{ __('Chọn danh mục') }}</option>
                                       @foreach($categoryAsset as $category_id)
                                           <option  value="{{ $category_id->assets_category_id }}">{{ $category_id->category_name }}</option>
                                       @endforeach
                                   </select>
                           </div>
                       <div class="form-group col-md-4">
                           <label>{{__('employee_id')}}</label>
                           <input class="form-control" autocomplete="off"  placeholder="{{ __('dashboard_employee_id') }}" name="employee_id" type="text" value="">
                       </div>
                       <div class="form-group col-md-4">
                           <label>{{__('xin_company_asset_code')}}</label>
                           <input class="form-control" autocomplete="off"  placeholder="{{ __('xin_company_asset_code') }}" name="asset_code" type="text" value="">
                       </div>
                       <div class="form-group col-md-4">
                           <!-- data-placeholder="{{__('left_company')}} -->
                           <label>{{__('action_status')}}</label>
                           <select style="height: 37px" class="select2_asset form-control" id="company_select2_ajax" name="is_working">
                               <option value="" disabled selected>{{ __('Chọn trạng thái') }}</option>
                               <option value="0">Tồn kho</option>
                               <option value="1">Hoạt động</option>
                               <option value="2">Hỏng</option>
                           </select>
                       </div>
{{--                       <div class="form-group col-md-4">--}}
{{--                           <label>{{__('xin_search')}}</label>--}}
{{--                           <input class="form-control" autocomplete="off" id="kt_search_query"  placeholder="{{ __('xin_search') }}"  type="text" value="">--}}
{{--                       </div>--}}
                       <div class="col-md-4 d-flex align-items-center">
                           <button type="submit" class="btn btn-primary px-6 font-weight-bold mr-3">{{ __('xin_search') }}
                               <span> {{  \App\Classes\Theme\Metronic::getSVG('media/svg/icons/General/Search.svg') }}</span>
                           </button>
                           <button  id="resetForm" class="btn btn-light-primary px-6 font-weight-bold">{{ __('xin_reset') }}</button>
                       </div>
                   </div>
               </form>
           </div>
        <div class="card-body">
            <!--begin: Datatable-->
            <div class="datatable datatable-bordered datatable-head-custom" id="list_asset"></div>
            <!--end: Datatable-->
        </div>
    </div>
    <!-- begin: Modal -->
    <div class="modal fade" id="createAsset" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="createAsset" aria-hidden="true" data-form-url="{{ route('admin.asset.create_form_asset')}}">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div>
    <!-- end: Modal -->
    <!-- begin: Modal detail -->
    <div class="modal fade" id="createAsset2" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="createAsset2" aria-hidden="true" data-form-url="{{ route('admin.asset.create_form_asset')}}">
        <div class="modal-dialog modal-dialog-centered modal-lg detail_width" role="document">
            <div class="modal-content modal_content_2">
            </div>
        </div>
    </div>
    <!-- end: Modal detail -->
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $(".select2_asset, .select_asset").select2({});
            $('#resetForm').click(function(){
                $('#asset_list_search_form').trigger("reset");
                $('#asset_list_search_form select').trigger("change");
            });
        });
        window._tables = {
            'list_asset': {
                'url': '{{ route('admin.asset.listDatatableAsset') }}',
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
                        field: 'name_asset',
                        title: '{{ __('xin_asset_name') }}',
                        sortable: false,
                        width: 90,
                        textAlign: 'left',
                        template: function (row) {
                            return row.name;
                        }
                    },
                    {
                        field: 'code_asset',
                        title: '{{ __('xin_company_asset_code') }}',
                        sortable: false,
                        textAlign: 'center',
                        template: function (row) {
                            return row.company_asset_code;
                        }
                    },
                    {
                        field: 'make',
                        title: '{{ __('makeIn') }}',
                        sortable: false,
                        textAlign: 'center',
                        template: function (row) {
                           return row.manufacturer;
                        }
                    },
                    {
                        field: 'id_user',
                        title: '{{ __('dashboard_employee_id') }}',
                        sortable: false,
                        // width: 60,
                        textAlign: 'center',
                        template: function (row) {
                            return row.employee_asset ? row.employee_asset.employee_id : '';
                        }
                    },
                    {
                        field: 'value',
                        title: '{{ __('giá trị') }}',
                        sortable: false,
                        textAlign: 'center',
                        template: function (row) {
                            return window._userCurrency(row.price);
                        }
                    },{
                        field: 'category',
                        title: '{{ __('xin_acc_category') }}',
                        sortable: false,
                        textAlign: 'center',
                        template: function (row) {
                            return row.category_asset ? row.category_asset.category_name : '';
                        }
                    },{
                        field: 'action_status',
                        title: '{{ __('action_status') }}',
                        sortable: false,
                        textAlign: 'center',
                        template: function (row) {
                            let status = {
                                0: {'class': ' label-light-primary'},
                                1: {'class': ' label-light-success'},
                                2: {'class': ' label-light-danger'},
                            };
                            let title = {
                                0: {'title': ' label-light-primary'},
                                1: {'title': ' label-light-success'},
                                2: {'title': ' label-light-danger'},
                            };
                            let status_type = {
                                0: {'label': 'Tồn kho'},
                                1: {'label': 'Họat động'},
                                2: {'label': 'Hỏng '},
                            };
                            return '<span class="label label-lg w-100 font-weight-bold ' + status[row.is_working].class + ' label-inline">' + status_type[row.is_working].label + '</span>';
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
                            let detail = "<span class=\"svg-icon svg-icon-primary svg-icon-2x\"><!--begin::Svg Icon | path:C:\\wamp64\\www\\keenthemes\\themes\\metronic\\theme\\html\\demo1\\dist/../src/media/svg/icons\\General\\Settings-1.svg--><svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" width=\"24px\" height=\"24px\" viewBox=\"0 0 24 24\" version=\"1.1\">\n" +
                                "    <g stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\">\n" +
                                "        <rect x=\"0\" y=\"0\" width=\"24\" height=\"24\"/>\n" +
                                "        <path d=\"M7,3 L17,3 C19.209139,3 21,4.790861 21,7 C21,9.209139 19.209139,11 17,11 L7,11 C4.790861,11 3,9.209139 3,7 C3,4.790861 4.790861,3 7,3 Z M7,9 C8.1045695,9 9,8.1045695 9,7 C9,5.8954305 8.1045695,5 7,5 C5.8954305,5 5,5.8954305 5,7 C5,8.1045695 5.8954305,9 7,9 Z\" fill=\"#000000\"/>\n" +
                                "        <path d=\"M7,13 L17,13 C19.209139,13 21,14.790861 21,17 C21,19.209139 19.209139,21 17,21 L7,21 C4.790861,21 3,19.209139 3,17 C3,14.790861 4.790861,13 7,13 Z M17,19 C18.1045695,19 19,18.1045695 19,17 C19,15.8954305 18.1045695,15 17,15 C15.8954305,15 15,15.8954305 15,17 C15,18.1045695 15.8954305,19 17,19 Z\" fill=\"#000000\" opacity=\"0.3\"/>\n" +
                                "    </g>\n" +
                                "</svg><!--end::Svg Icon--></span>";
                            let addHistoryGuarantee = "<span class=\"svg-icon svg-icon-primary svg-icon-2x\"><!--begin::Svg Icon | path:C:\\wamp64\\www\\keenthemes\\themes\\metronic\\theme\\html\\demo1\\dist/../src/media/svg/icons\\Tools\\Tools.svg--><svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" width=\"24px\" height=\"24px\" viewBox=\"0 0 24 24\" version=\"1.1\">\n" +
                                "    <g stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\">\n" +
                                "        <rect x=\"0\" y=\"0\" width=\"24\" height=\"24\"/>\n" +
                                "        <path d=\"M15.9497475,3.80761184 L13.0246125,6.73274681 C12.2435639,7.51379539 12.2435639,8.78012535 13.0246125,9.56117394 L14.4388261,10.9753875 C15.2198746,11.7564361 16.4862046,11.7564361 17.2672532,10.9753875 L20.1923882,8.05025253 C20.7341101,10.0447871 20.2295941,12.2556873 18.674559,13.8107223 C16.8453326,15.6399488 14.1085592,16.0155296 11.8839934,14.9444337 L6.75735931,20.0710678 C5.97631073,20.8521164 4.70998077,20.8521164 3.92893219,20.0710678 C3.1478836,19.2900192 3.1478836,18.0236893 3.92893219,17.2426407 L9.05556629,12.1160066 C7.98447038,9.89144078 8.36005124,7.15466739 10.1892777,5.32544095 C11.7443127,3.77040588 13.9552129,3.26588995 15.9497475,3.80761184 Z\" fill=\"#000000\"/>\n" +
                                "        <path d=\"M16.6568542,5.92893219 L18.0710678,7.34314575 C18.4615921,7.73367004 18.4615921,8.36683502 18.0710678,8.75735931 L16.6913928,10.1370344 C16.3008685,10.5275587 15.6677035,10.5275587 15.2771792,10.1370344 L13.8629656,8.7228208 C13.4724413,8.33229651 13.4724413,7.69913153 13.8629656,7.30860724 L15.2426407,5.92893219 C15.633165,5.5384079 16.26633,5.5384079 16.6568542,5.92893219 Z\" fill=\"#000000\" opacity=\"0.3\"/>\n" +
                                "    </g>\n" +
                                "</svg><!--end::Svg Icon--></span>";

                            let html = "<a  href='javascript:;' data-id="+row.assets_id+" data-toggle='modal' data-target='#createAsset2' data-type='detail' class='btn btn-sm btn-clean btn-icon' title='{{ __('xin_details') }}'>"+detail+"</a>";
                                html += "<a  href='javascript:;' data-id="+row.assets_id+" data-toggle='modal' data-type='warranty' data-target='#createAsset' class='btn btn-sm btn-clean btn-icon' title='{{ __('add_history_guarantee') }}'>"+addHistoryGuarantee+"</a>";
                                html += "<a href='javascript:;' data-id="+row.assets_id+" data-toggle='modal' data-target='#createAsset' class='btn btn-sm btn-clean btn-icon' title='{{ __('edit') }}'>"+window.iconEdit+"</a>";
                            html += "<a onclick='deleteAsset("+row.assets_id+")' href='javascript:;' class='btn btn-sm btn-clean btn-icon' title='{{ __('cancel') }}'>"+window.iconDelete+"</a>";

                            return html;
                        }
                    }
                ],
                'search_from': '#asset_list_search_form',
                'search_keys': '#kt_search_query',
            }
        };
        $('#createAsset').on('shown.bs.modal', function (e) {
            window._loading("#createAsset .modal-body");
            let button = $(e.relatedTarget);
            let id = button.data('id');
            let type = button.data('type');
            // type === 'detail' && $(".header_asset").remove();
            // if (type != undefined) $(".title_module").html('Chỉnh sửa lịch sử bảo hành');
            let url = $('#createAsset').data('form-url');
            $.ajax({
                url: url,
                data: {
                    id: id,
                    type : type
                }
            }).done(function (response) {
                window._loading("#createAsset .modal-content", false);
                $("#createAsset .modal-content").html(response);
            })
                .fail(function (jqXHR, status){
                    window._loading("#createAsset .modal-content", false);
                    toastr.error(__("order_form_fetch_error"));
                    window._display_alert("#createAsset .modal-body", __("order_form_fetch_error"));
                });
        });
        $('#createAsset').on('hidden.bs.modal', function (e) {
            $("#createAsset .modal-body").html("");
        });

        // detail
        $('#createAsset2').on('shown.bs.modal', function (e) {
            window._loading("#createAsset2 .modal-body");
            let button = $(e.relatedTarget);
            let id = button.data('id');
            let type = button.data('type');
            let url = $('#createAsset').data('form-url');
            $.ajax({
                url: url,
                data: {
                    id: id,
                    type : type
                }
            }).done(function (response) {
                window._loading("#createAsset2 .modal-content", false);
                $("#createAsset2 .modal-content").html(response);
            }).fail(function (jqXHR, status){
                    window._loading("#createAsset2 .modal-content", false);
                    toastr.error(__("order_form_fetch_error"));
                    window._display_alert("#createAsset .modal-body", __("order_form_fetch_error"));
            });
        });

        var deleteAsset = function (id) {
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
                            url: '{{ route("admin.asset.ajax.delete_asset") }}',
                            data: {id: id}
                        }).done(function (response) {
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
                            setTimeout(function () {
                                Swal.close()
                            }, 800);
                            window._tables.overtime_list.datatable.reload();
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
    </script>
    <script src="{{ mix('js/list.js') }}" type="text/javascript"></script>
@endsection



