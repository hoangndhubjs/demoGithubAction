@extends('layout.default')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/smartwizard@5/dist/css/smart_wizard_all.min.css" rel="stylesheet"
        type="text/css" />

@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/smartwizard@5/dist/js/jquery.smartWizard.min.js" type="text/javascript">
    </script>
@endsection

@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{ __('dashboard_designation') }}
                    <span class="d-block text-muted pt-2 font-size-sm">{{ __('xin_designations') }}</span>
                </h3>
            </div>
            <div class="card-toolbar">
                <!--begin::Button-->
                <form action="" id="advanceForm">
                   <div class="input-group input-group-sm input-group-solid mr-5 bg-white border" style="max-width: 175px">
                           <input type="text" name="query_string" id="kt__search_query" class="form-control pl-4" placeholder="Search..."/>
                           <div class="input-group-append">
                            <span class="input-group-text">
                               {{  \App\Classes\Theme\Metronic::getSVG('media/svg/icons/General/Search.svg') }}
                            </span>
                            </div>
                   </div>
                </form>
                <button class="btn btn-primary font-weight-bolder mr-3" data-toggle="modal" data-target="#popupAssetDesignation">
                    <span class="svg-icon svg-icon-md">
                        {{  \App\Classes\Theme\Metronic::getSVG('media/svg/icons/Code/Plus.svg') }}
                        <!--end::Svg Icon-->
                    </span> {{ __("xin_add_new") }}
                </button>
                <!--end::Button-->
            </div>
        </div>
        <hr>
        <div class="card-body">
            <div class="datatable datatable-bordered datatable-head-custom table-responsive position-relative" id="table_designation"></div>
        </div>
    </div>

    {{-- modal add - edit designation --}}
    <div class="modal fade px-0" id="popupAssetDesignation" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="popupAssetDesignation"
     aria-hidden="true" data-form-url="{{ route('designation.create_form_asset_designation')}}">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="edit-modal">
                    <div class="card card-custom" style="min-height: 50px">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script type="text/javascript">
        window._tables = {
            'table_designation': {
                'url': '{{ route('designation.ajax.lists') }}',
                'search': {
                    input: $('#kt_datatable_search_query'),
                    key: 'generalSearch'
                },
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
                        field: 'company_name',
                        title: '{{__('left_company')}}',
                        sortable: false,
                        autoHide: false,
                        width: 130,
                        template: function (row) {
                            console.log(row);
                            if(row.company_asset) { return row.company_asset.name;} else { return '';}
                        }
                    },
                    {
                        field: 'department_name',
                        title: '{{ __('left_department') }}',
                        sortable: false,
                        autoHide: false,
                        width: 130,
                        template: function (row) {
                            if(row.department_asset) { return row.department_asset.department_name;} else { return '';}
                        }
                    },
                    {
                        field: 'designation_name',
                        title: '{{ __('xin_name') }} {{ __('left_designation') }}',
                        sortable: false,
                        autoHide: false,
                        width: 130,
                        template: function (row) {
                            return row.designation_name;
                        }
                    },
                    {
                        field: 'assets_designation_id',
                        title: '{{ __('xin_action') }}',
                        sortable: false,
                        textAlign: 'center',
                        width: 150,
                        template: function (row) {
                            let html = "";
                            html += "<a href='#' data-toggle='modal' data-target='#popupAssetDesignation' data-id='"+row.designation_id+"' class='btn btn-sm btn-clean btn-icon' title='{{ __('edit') }}'>"+window.iconEdit+"</a>";
                            html += "<a href='#' onclick='deleteRequest("+row.designation_id+")' data-id='"+row.designation_id+"' class='btn btn-sm btn-clean btn-icon' title='{{ __('cancel') }}'>"+window.iconDelete+"</a>";
                            return html;
                        }
                    }
                ],'search_from': '#advanceForm'
            }
        };
        $("#kt__search_query").keyup(function() {
            let string = $(this).val();
            window._tables.table_designation.datatable.reload();
        });
        function reloadTable() {
            window._tables.table_designation.datatable.reload();
        }

        $('#popupAssetDesignation').on('shown.bs.modal', function (e) {
            window._loading("#popupAssetDesignation .card-custom");
            let button = $(e.relatedTarget);
            let designation_id = button.data('id');
            let url = $('#popupAssetDesignation').data('form-url');
            let designation = __('left_designation').toLowerCase();
            if(designation_id == null){
                $('#exampleModalLabel').html("{{__('xin_add_new')}} " + designation);
            }else{
                $('#exampleModalLabel').html("{{__('xin_update')}} " + designation);
            }
            $.ajax({
                url: url,
                data: {
                    designation_id: designation_id
                },
                type: 'GET',
            })
                .done(function (response) {
                    window._loading("#popupAssetDesignation .card-custom", false);
                    $("#popupAssetDesignation .card-custom").html(response);

                })
                .fail(function (jqXHR, status){
                    window._loading("#popupAssetDesignation .card-custom", false);
                    toastr.error(__("order_form_fetch_error"));
                    window._display_alert("#popupAssetDesignation .card-custom", __("order_form_fetch_error"));
                });
        });

        $('#popupAssetDesignation').on('hidden.bs.modal', function (e) {
            $("#popupAssetDesignation .card-custom").html("");
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
                        url: '{{ route("designation.destroy") }}',
                        data: { designation_id: id }
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
