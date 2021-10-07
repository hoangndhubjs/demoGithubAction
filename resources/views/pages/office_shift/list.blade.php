@extends('layout.default')

@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{ __("xin_role_list") }} {{__("left_office_shift")}}
                    <span class="d-block text-muted pt-2 font-size-sm"> {{ __("left_company") }} {{ Auth::user()->company->name}}</span>
                </h3>
            </div>
            <div class="card-toolbar">
                <!--begin::Button-->
                <button class="btn btn-primary font-weight-bolder mr-3" data-toggle="modal" data-target="#add_office_shift">
                    <x-icon type="svg" category="Code" icon="Plus"/> {{ __("xin_add_new") }}
                </button>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <!--begin: Datatable-->
            <div class="datatable datatable-bordered datatable-head-custom table-responsive position-relative" id="office_shift"></div>
            <!--end: Datatable-->
        </div>
    </div>
    <!-- begin: Modal -->
    <div class="modal fade" id="add_office_shift" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="add_office_shift" aria-hidden="true" data-form-url="{{ route('office-shift.ajax.add_office_shift')}}">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __("xin_add_new") }} {{ __("left_office_shift") }}</h5>
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
        window.office_shift_remove_url = '{{ route("office-shift.ajax.delete_office_shift") }}';
        window.update_default_shift = '{{ route("office-shift.ajax.update_default_shift") }}';
        window._tables = {
            'office_shift': {
                'url': '{{ route('office-shift.ajax.lists') }}',
                'columns': [
                    {
                        field: 'company_id',
                        title: '{{ __('left_company') }}',
                        sortable: false,
                        width: 75,
                        template: function (row) {
                            return row.company.name;
                        }
                    },
                    {
                        field: 'shift_name',
                        title: '{{ __('xin_type') }}',
                        sortable: false,
                        autoHide: false,
                        width: 70,
                        template: function (row) {
                            if(row.default_shift == 1){
                                return row.shift_name + " <span class=" + "badge" + " style=color:#fff;background-color:#28a745"+">" + '{{ __('xin_default') }}' + "</span>";
                            } else {
                                return row.shift_name;
                            }
                        }
                    },
                    {
                        field: 'monday_in_time',
                        title: '{{ __('xin_monday') }}',
                        width: 90,
                        sortable: false,
                        template: function (row) {
                            if(row.monday_in_time == "") {
                                return "Chưa có lịch";
                            } else{
                                return row.monday_in_time + ' {{ __('dashboard_to') }} ' + row.monday_out_time;
                            }
                        }
                    },
                    {
                        field: 'tuesday_in_time',
                        title: '{{ __('xin_tuesday') }}',
                        width: 90,
                        sortable: false,
                        template: function (row) {
                            if(row.tuesday_in_time == "") {
                                return "Chưa có lịch";
                            } else{
                                return row.tuesday_in_time  + ' {{ __('dashboard_to') }} ' + row.tuesday_out_time;
                            }
                        }
                    },
                    {
                        field: 'wednesday_in_time',
                        title: '{{ __('xin_wednesday') }}',
                        width: 90,
                        sortable: false,
                        template: function (row) {
                            if(row.wednesday_in_time == "") {
                                return "Chưa có lịch";
                            } else{
                                return row.wednesday_in_time  + ' {{ __('dashboard_to') }} ' + row.wednesday_out_time;
                            }
                        }
                    },
                    {
                        field: 'thursday_in_time',
                        title: '{{ __('xin_thursday') }}',
                        width: 90,
                        sortable: false,
                        template: function (row) {
                            if(row.thursday_in_time == "") {
                                return "Chưa có lịch";
                            } else{
                                return row.thursday_in_time  + ' {{ __('dashboard_to') }} ' + row.thursday_out_time;
                            }
                        }
                    },
                    {
                        field: 'friday_in_time',
                        title: '{{ __('xin_friday') }}',
                        width: 90,
                        sortable: false,
                        template: function (row) {
                            if(row.friday_in_time == "") {
                                return "Chưa có lịch";
                            } else{
                                return row.friday_in_time  + ' {{ __('dashboard_to') }} ' + row.friday_out_time;
                            }
                        }
                    },
                    {
                        field: 'saturday_in_time',
                        title: '{{ __('xin_saturday') }}',
                        width: 90,
                        sortable: false,
                        template: function (row) {
                            if(row.saturday_in_time == "") {
                                return "Chưa có lịch";
                            } else{
                                return row.saturday_in_time  + ' {{ __('dashboard_to') }} ' + row.saturday_out_time;
                            }
                        }
                    },
                    {
                        field: 'sunday_in_time',
                        title: '{{ __('xin_sunday') }}',
                        sortable: false,
                        width: 80,
                        template: function (row) {
                            if(row.sunday_in_time == "") {
                                return "Chưa có lịch";
                            } else{
                                return row.sunday_in_time  + ' {{ __('dashboard_to') }} ' + row.sunday_out_time;
                            }
                        }
                    },
                    {
                        field: 'action',
                        title: '{{ __('xin_action') }}',
                        sortable: false,
                        autoHide: false,
                        textAlign: 'center',
                        width: 100,
                        template: function (row) {
                            let html = "";
                                if(row.default_shift == 0){
                                    html += "<a href='#' onclick='updateDefaultShift("+row.office_shift_id+")' class='btn btn-sm btn-clean btn-icon' title='{{ __('Đặt mặc định') }}'>"+'<span class="svg-icon svg-icon-primary svg-icon-2x"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\n' +
                                                '<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\n' +
                                                    '<rect x="0" y="0" width="24" height="24"/>\n' +
                                                    '<path d="M10.9630156,7.5 L11.0475062,7.5 C11.3043819,7.5 11.5194647,7.69464724 11.5450248,7.95024814 L12,12.5 L15.2480695,14.3560397 C15.403857,14.4450611 15.5,14.6107328 15.5,14.7901613 L15.5,15 C15.5,15.2109164 15.3290185,15.3818979 15.1181021,15.3818979 C15.0841582,15.3818979 15.0503659,15.3773725 15.0176181,15.3684413 L10.3986612,14.1087258 C10.1672824,14.0456225 10.0132986,13.8271186 10.0316926,13.5879956 L10.4644883,7.96165175 C10.4845267,7.70115317 10.7017474,7.5 10.9630156,7.5 Z" fill="#000000"/>\n' +
                                                    '<path d="M7.38979581,2.8349582 C8.65216735,2.29743306 10.0413491,2 11.5,2 C17.2989899,2 22,6.70101013 22,12.5 C22,18.2989899 17.2989899,23 11.5,23 C5.70101013,23 1,18.2989899 1,12.5 C1,11.5151324 1.13559454,10.5619345 1.38913364,9.65805651 L3.31481075,10.1982117 C3.10672013,10.940064 3,11.7119264 3,12.5 C3,17.1944204 6.80557963,21 11.5,21 C16.1944204,21 20,17.1944204 20,12.5 C20,7.80557963 16.1944204,4 11.5,4 C10.54876,4 9.62236069,4.15592757 8.74872191,4.45446326 L9.93948308,5.87355717 C10.0088058,5.95617272 10.0495583,6.05898805 10.05566,6.16666224 C10.0712834,6.4423623 9.86044965,6.67852665 9.5847496,6.69415008 L4.71777931,6.96995273 C4.66931162,6.97269931 4.62070229,6.96837279 4.57348157,6.95710938 C4.30487471,6.89303938 4.13906482,6.62335149 4.20313482,6.35474463 L5.33163823,1.62361064 C5.35654118,1.51920756 5.41437908,1.4255891 5.49660017,1.35659741 C5.7081375,1.17909652 6.0235153,1.2066885 6.2010162,1.41822583 L7.38979581,2.8349582 Z" fill="#000000" opacity="0.3"/>\n' +
                                                '</g>\n' +
                                            '</svg><!--end::Svg Icon--></span>'+"</a>";
                                }
                                html += "<a href='#' data-toggle='modal' data-target='#add_office_shift' data-id='"+row.office_shift_id+"' class='btn btn-sm btn-clean btn-icon' title='{{ __('edit') }}'>"+window.iconEdit+"</a>";
                                html += "<a href='#' onclick='deleteOfficeShift("+row.office_shift_id+")' class='btn btn-sm btn-clean btn-icon' title='{{ __('cancel') }}'>"+window.iconDelete+"</a>";
                            return html;
                        }
                    }
                ],
            }
        };
    </script>
    <script src="{{ mix('js/list.js') }}" type="text/javascript"></script>
    <script>
        $('#add_office_shift').on('show.bs.modal', function (e) {
            window._loading("#add_office_shift .modal-body");
            let button = $(e.relatedTarget);
            let id = button.data('id');
            let url = $('#add_office_shift').data('form-url');
            $.ajax({
                url: url,
                data: {
                    id: id
                }
            })
                .done(function (response) {
                    window._loading("#add_office_shift .modal-body", false);
                    $("#add_office_shift .modal-body").html(response);
                })
                .fail(function (jqXHR, status){
                    window._loading("#add_office_shift .modal-body", false);
                    toastr.error(__("order_form_fetch_error"));
                    window._display_alert("#add_office_shift .modal-body", __("order_form_fetch_error"));
                });
        });
        $('#add_office_shift').on('hidden.bs.modal', function (e) {
            $("#add_office_shift .modal-body").html("");
        });

        var updateDefaultShift = function (id) {
            Swal.fire({
                title: __("Bạn có chắc chắn muốn thay đổi thời gian mặc định?"),
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: __("yes"),
                cancelButtonText: __("no"),
                reverseButtons: true,
                showLoaderOnConfirm: true,
                preConfirm: function (data) {
                    Swal.showLoading();
                    $.ajax({
                        method: 'POST',
                        url: window.window.update_default_shift,
                        data: { id: id }
                    }).done(function(response) {
                        if (response.success) {
                            Swal.fire(
                                __("update_title"),
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
                        setTimeout(function () {Swal.close()}, 2000);
                        window._tables.office_shift.datatable.reload();
                    });
                    return false;
                },
                allowOutsideClick: function () { return !Swal.isLoading() }
            });
        }

        var deleteOfficeShift = function (id) {
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
                        url: window.office_shift_remove_url,
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
                        setTimeout(function () {Swal.close()}, 2000);
                        window._tables.office_shift.datatable.reload();
                    });
                    return false;
                },
                allowOutsideClick: function () { return !Swal.isLoading() }
            });
        }
    </script>
@endsection



