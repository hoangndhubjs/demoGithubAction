@extends('layout.default')

@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{ __('dashboard_locations') }}
                    <span class="d-block text-muted pt-2 font-size-sm">{{ __('xin_locations') }}</span>
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
                <button class="btn btn-primary font-weight-bolder mr-3" data-toggle="modal" data-target="#popupAssetLocation">
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
            <div class="datatable datatable-bordered datatable-head-custom table-responsive position-relative" id="table_location"></div>
        </div>
    </div>

    {{-- modal add - edit location --}}
    <div class="modal fade px-0" id="popupAssetLocation" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="popupAssetLocation"
     aria-hidden="true" data-form-url="{{ route('location.create_form_asset_location')}}">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('xin_branchs') }}</h5>
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
            'table_location': {
                'url': '{{route('location.ajax.lists')}}',
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
                        textAlign: 'center',
                        template: function(row, index){
                            return ++index;
                        }
                    },
                    {
                        field: 'location_name',
                        title: '{{__('xin_name')}} {{__('xin_branchs')}}',
                        sortable: false,
                        autoHide: false,
                        width: 150,
                        template: function (row){
                            return row.location_name;
                        }
                    },
                    {
                        field: 'company_id',
                        title: '{{__('xin_name')}} {{__('module_company_title')}}',
                        sortable: false,
                        autoHide: false,
                        width: 150,
                        template: function (row){
                            if(row.company) { return row.company.name;} else { return '';}
                        }
                    },
                    {
                        field:'location_head',
                        title: '{{__('xin_view_locationh')}}',
                        sortable: false,
                        autoHide: false,
                        width: 150,
                        template: function(row){
                            if(row.employee) { return row.employee.last_name + ' ' + row.employee.first_name;} else{ return '';}
                        }
                    },
                    {
                        field:'city',
                        title: '{{__('xin_city')}}',
                        sortable: false,
                        autoHide: false,
                        width: 80,
                        template: function(row){
                            return row.city;
                        }
                    },
                    {
                        field:'added_by',
                        title: '{{__('xin_added_by')}}',
                        sortable: false,
                        autoHide: false,
                        width: 120,
                        template: function(row){
                            if(row.employee_addedby) { return row.employee_addedby.last_name  + ' ' + row.employee_addedby.first_name;} else { return '';}
                        }
                    },
                    {
                        field: 'assets_location_id',
                        title: '{{ __('xin_action') }}',
                        sortable: false,
                        textAlign: 'center',
                        width: 150,
                        template: function (row) {
                            let html = "";
                            html += "<a href='#' data-toggle='modal' data-target='#popupAssetLocation' data-id='"+row.location_id+"' class='btn btn-sm btn-clean btn-icon' title='{{ __('edit') }}'>"+window.iconEdit+"</a>";
                            html += "<a href='#' onclick='deleteRequest("+row.location_id+")' data-id='"+row.location_id+"' class='btn btn-sm btn-clean btn-icon' title='{{ __('cancel') }}'>"+window.iconDelete+"</a>";
                            return html;
                        }
                    },
                ],'search_from': '#advanceForm'
            }
        };
        $("#kt__search_query").keyup(function() {
            let string = $(this).val();
            window._tables.table_location.datatable.reload();
        });
        function reloadTable() {
            window._tables.table_location.datatable.reload();
        }

        $('#popupAssetLocation').on('shown.bs.modal', function(e){
            window._loading('#popupAssetLocation .card-custom');
            let button  = $(e.relatedTarget);
            let location_id = button.data('id');
            console.log(location_id);
            let url = $('#popupAssetLocation').data('form-url');
            let branch = __('xin_branchs').toLowerCase();
            if(location_id == null){
                $('#exampleModalLabel').html("{{__('xin_add_new')}} " + branch);
            }else{
                $('#exampleModalLabel').html("{{__('xin_update')}} " + branch);
            }
            $.ajax({
                url: url,
                data: { location_id:location_id },
                type: 'GET',
            }).done(function (res){
                window._loading('#popupAssetLocation .card-custom', false);
                $('#popupAssetLocation .card-custom').html(res);
            }).fail(function (jqXHR, status){
                window._loading('#popupAssetLocation .card-custom', false);
                toastr.error(__('order_form_fetch_error'));
                window._display_alert('#popupAssetLocation .card-custom', __("order_form_fetch_error"));
            });
        });

        $('#popupAssetLocation').on('hidden.bs.modal', function (e) {
            $("#popupAssetLocation .card-custom").html("");
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
                        url: '{{ route("location.destroy") }}',
                        data: { location_id:id },
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
