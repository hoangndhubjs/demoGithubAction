@extends('layout.default')

@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{ __('asset_category') }}
                    <span class="d-block text-muted pt-2 font-size-sm">{{ __('property_listings') }}</span>
                </h3>
            </div>
            <div class="card-toolbar">
                <!--begin::Button-->
                <button class="btn btn-primary font-weight-bolder mr-3" data-toggle="modal" data-target="#popupAssetCategory">
                    <x-icon type="svg" category="Code" icon="Plus"/> {{ __("xin_add_new") }}
                </button>
                <!--end::Button-->
            </div>
        </div>
        <hr>
        <div class="card-body">
            <div class="datatable datatable-bordered datatable-head-custom table-responsive position-relative" id="assetCategory"></div>
        </div>
    </div>


    <div class="modal fade px-0" id="popupAssetCategory" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="popupAssetCategory" aria-hidden="true" data-form-url="{{ route('admin.asset.create_form_asset_category')}}">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('asset_category') }}</h5>
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
            'assetCategory': {
                'url': '{{ route('admin.asset.list-category') }}',
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
                        field: 'category_name',
                        title: '{{ __('xin_name') }}',
                        sortable: false,
                        autoHide: false,
                        width: 220,
                        template: function (row) {
                            return row.category_name ? row.category_name : 0;
                        }
                    },
                    {
                        field: 'assets_count',
                        title: '{{ __('xin_quantity') }}',
                        sortable: false,
                        width: 220,
                        template: function (row) {
                            return row.assets_count ? row.assets_count : 0;
                        }
                    },
                    {
                        field: 'assets_category_id',
                        title: '{{ __('dashboard_xin_status') }}',
                        sortable: false,
                        textAlign: 'center',
                        width: 120,
                        template: function (row) {
                            let html = "";
                            html = "<a href='javascript:void(0);' data-toggle='modal' data-target='#popupAssetCategory' data-id='"+row.assets_category_id+"' class='btn btn-sm btn-clean btn-icon' title='{{ __('edit') }}'>"+'<x-icon type="flaticon" icon="edit" class="text-warning"/>'+"</a>";
                            html += "<a href='javascript:void(0);' onclick='deleteRequest("+row.assets_category_id+")' class='btn btn-sm btn-clean btn-icon' title='{{ __('cancel') }}'>"+'<x-icon type="flaticon" icon="delete" class="text-danger"/>'+"</a>";
                            return html;
                        }
                    }
                ],
            }
        };

        function reloadTable() {
            window._tables.assetCategory.datatable.reload();
        }

        $('#popupAssetCategory').on('shown.bs.modal', function (e) {
            window._loading("#popupAssetCategory .card-custom");
            let button = $(e.relatedTarget);
            let id = button.data('id');
            let url = $('#popupAssetCategory').data('form-url');
            $.ajax({
                url: url,
                data: {
                    id: id
                }
            })
                .done(function (response) {
                    window._loading("#popupAssetCategory .card-custom", false);
                    $("#popupAssetCategory .card-custom").html(response);
                })
                .fail(function (jqXHR, status){
                    window._loading("#popupAssetCategory .card-custom", false);
                    toastr.error(__("order_form_fetch_error"));
                    window._display_alert("#popupAssetCategory .card-custom", __("order_form_fetch_error"));
                });
        });

        $('#popupAssetCategory').on('hidden.bs.modal', function (e) {
            $("#popupAssetCategory .card-custom").html("");
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
                        url: '{{ route("admin.asset.delete_asset_cate") }}',
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
    <script src="{{ mix('js/list.js') }}" type="text/javascript"></script>
@endsection



