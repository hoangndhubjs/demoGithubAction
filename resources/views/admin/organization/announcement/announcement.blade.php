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
                <h3 class="card-label">{{ __('dashboard_announcements') }}
                    <span class="d-block text-muted pt-2 font-size-sm">{{ __('left_announcements') }}</span>
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
                <button class="btn btn-primary font-weight-bolder mr-3" data-toggle="modal" data-target="#popupAssetAnnouncement">
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
            <div class="datatable datatable-bordered datatable-head-custom table-responsive position-relative" id="table_announcement"></div>
        </div>
    </div>

    {{-- modal add - edit announcement --}}
    <div class="modal fade px-0" id="popupAssetAnnouncement" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="popupAssetAnnouncement"
     aria-hidden="true" data-form-url="{{ route('announcement.create_form_asset_announcement')}}">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{__('left_announcements')}}</h5>
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
            'table_announcement': {
                'url': '{{ route('announcement.ajax.lists') }}',
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
                        field: 'title',
                        title: '{{__('xin_title')}}',
                        sortable: false,
                        autoHide: false,
                        width: 300,
                        template: function (row) {
                            console.log(row);
                            return row.title;
                        }
                    },
                    {
                        field: 'company',
                        title: '{{ __('left_company') }}',
                        sortable: false,
                        autoHide: false,
                        width: 100,
                        template: function (row) {
                            if(row.company_asset){ return row.company_asset.name;}
                            else{ return '' ;}

                        }
                    },
                    {
                        field: 'department',
                        title: '{{ __('left_department') }}',
                        sortable: false,
                        autoHide: false,
                        width: 100,
                        template: function (row) {
                            if(row.department_asset){ return row.department_asset.department_name;}
                            else {return '';}
                        }
                    },
                    {
                        field: 'location',
                        title: '{{ __('xin_branchs') }}',
                        sortable: false,
                        autoHide: false,
                        width: 100,
                        template: function (row) {
                            if(row.location_asset) { return row.location_asset.location_name;}
                            else { return '';}
                        }
                    },
                    {
                        field: 'assets_announcement_id',
                        title: '{{ __('xin_action') }}',
                        sortable: false,
                        textAlign: 'center',
                        width: 150,
                        template: function (row) {
                            let html = "";
                            html += "<a href='#' data-toggle='modal' data-target='#popupAssetAnnouncement' data-id='"+row.announcement_id+"' class='btn btn-sm btn-clean btn-icon' title='{{ __('edit') }}'>"+window.iconEdit+"</a>";
                            html += "<a href='#' onclick='deleteRequest("+row.announcement_id+")' data-id='"+row.announcement_id+"' class='btn btn-sm btn-clean btn-icon' title='{{ __('cancel') }}'>"+window.iconDelete+"</a>";
                            return html;
                        }
                    }
                ], 'search_from': '#advanceForm'
            }
        };

        function reloadTable() {
            window._tables.table_announcement.datatable.reload();
        }

        $('#popupAssetAnnouncement').on('shown.bs.modal', function (e) {
            window._loading("#popupAssetAnnouncement .card-custom");
            let button = $(e.relatedTarget);
            let announcement_id = button.data('id');
            let url = $('#popupAssetAnnouncement').data('form-url');
            let announcement = __('left_announcements').toLowerCase();
            if(announcement_id == null){
                $('#exampleModalLabel').html("{{__('xin_add_new')}} " + announcement);
            }else{
                $('#exampleModalLabel').html("{{__('xin_update')}} " + announcement);
            }
            $.ajax({
                url: url,
                data: {
                    announcement_id: announcement_id
                },
                type: 'GET',
            })
                .done(function (response) {
                    window._loading("#popupAssetAnnouncement .card-custom", false);
                    $("#popupAssetAnnouncement .card-custom").html(response);

                })
                .fail(function (jqXHR, status){
                    window._loading("#popupAssetAnnouncement .card-custom", false);
                    toastr.error(__("order_form_fetch_error"));
                    window._display_alert("#popupAssetAnnouncement .card-custom", __("order_form_fetch_error"));
                });
        });

        $('#popupAssetAnnouncement').on('hidden.bs.modal', function (e) {
            $("#popupAssetAnnouncement .card-custom").html("");
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
                        url: '{{ route("announcement.destroy") }}',
                        data: { announcement_id: id }
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
    $("#kt__search_query").keyup(function() {
            let string = $(this).val();
            window._tables.table_announcement.datatable.reload();
    });
    </script>
    {{-- <script src="{{ mix('js/payroll/payroll.js') }}" type="text/javascript"></script> --}}
    <script src="{{ mix('js/list.js') }}" type="text/javascript"></script>
@endsection
