@extends('layout.default')
@section('styles')
    <link rel="stylesheet" href="{{ mix('/plugins/custom/jstree/jstree.bundle.css') }}" />
@endsection
@section('content')
    <!--begin::Card-->
    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{__($page_title)}}
                    <span class="d-block text-muted pt-2 font-size-sm">{{__('')}}</span></h3>
            </div>
            <div class="card-toolbar">
                <!--begin::Button-->
                <a href="#" class="btn btn-primary font-weight-bolder" data-toggle="modal" data-target="#permission_modal">
                    <span class="svg-icon svg-icon-md">
                        {{  \App\Classes\Theme\Metronic::getSVG('media/svg/icons/Code/Plus.svg') }}
                        <!--end::Svg Icon-->
                    </span>{{__('Thêm mới')}}</a>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <!--begin: Search Form-->
            <div class="mb-7">
                <div class="row align-items-center">
                </div>
            </div>
            <!--end::Search Form-->
            <!--begin: Datatable-->
            {{--<div class="datatable datatable-bordered datatable-head-custom" id="overtime_list"></div>--}}
            <div id="kt_tree_6" class="tree-demo"></div>
            <!--end: Datatable-->
        </div>
    </div>
    <!--end::Card-->
    <!-- begin: Modal -->
    <div class="modal fade" id="permission_modal" tabindex="-1" role="dialog" aria-labelledby="permission_modal" aria-hidden="true" data-form-url="{{ route('permission.ajax.create_form')}}">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __("add_permission") }}</h5>
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
        $('#permission_modal').on('shown.bs.modal', function (e) {
            window._loading("#permission_modal .modal-body");
            let button = $(e.relatedTarget);
            let id = button.data('id');
            let url = $('#permission_modal').data('form-url');
            $.ajax({
                url: url,
                data: {
                    id: id
                }
            })
                .done(function (response) {
                    window._loading("#permission_modal .modal-body", false);
                    $("#permission_modal .modal-body").html(response);
                })
                .fail(function (jqXHR, status){
                    window._loading("#permission_modal .modal-body", false);
                    toastr.error(__("order_form_fetch_error"));
                    window._display_alert("#permission_modal .modal-body", __("order_form_fetch_error"));
                });
        });
        $('#permission_modal').on('hidden.bs.modal', function (e) {
            $("#permission_modal .modal-body").html("");
        });

        $("#kt_tree_6").jstree({
            "core": {
                "themes": {
                    "responsive": false
                },
                // so that create works
                "check_callback": true,
                "data": {
                    "url": function(node) {
                        return '{{ route('permission.ajax.list')}}';
                    },
                    "dataType" : "json",
                    "data": function(node) {
                        return { "text" : node.text };
                    }
                }
            },
            "types": {
                "default": {
                    "icon": "fa fa-folder"
                },
                "file": {
                    "icon": "fa fa-file  text-primary"
                }
            },
            "state": {
                "key": "demo3"
            },
            "plugins": ["dnd", "state", "types"]
        });
        /*$('#kt_tree_6').on("changed.jstree", function (e, data) {
            console.log(data.instance.get_selected(true)[0].text);
            console.log(data.instance.get_node(data.selected[0]).text);
        });*/
    </script>
    <script src="{{ mix('js/list.js') }}" type="text/javascript"></script>
    <script src="{{ mix('js/pages/features/miscellaneous/treeview.js') }}"></script>
@endsection
