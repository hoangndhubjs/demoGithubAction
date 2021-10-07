@extends('layout.default')
@section('content')
    <style>
        .dots_ps {
            position: relative;
        }
        .dots_as {
            position: absolute;
            width: 5px;
            height: 5px;
            content: 'a';
            left: -10px;
            top: 50%;
            transform: translate(-50%, -50%);
            border-radius: 100%;
        }
        .close_compens {
            position: absolute;
            top: 30%;
            right: 3%;
        }
        .excel_upload svg{
            width: 5rem;
            height: 5rem;
        }
        .excel_upload svg:hover {
            cursor: pointer;
        }
        .up-ex{padding: 10rem;}
        .scroll_excel{
            height: 500px;
            overflow: scroll;
        }
        .scroll_excel::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius:5px;
        }
        .scroll_excel::-webkit-scrollbar {
            width: 5px;
        }
        /*#changeIsActive option {*/
        /*    color: #000;*/
        /*    margin: 10px;*/
        /*}*/
        /*#changeIsActive:hover {*/
        /*    color: #6E8192;*/
        /*    background: unset;*/
        /*}*/
    </style>
    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{__($page_title)}}
                    <span class="d-block text-muted pt-2 font-size-sm">{{__('listsPayroll')}}</span></h3>
            </div>
            <div class="card-toolbar">
                <!--begin::Button-->
                {{--<button id="exportExcel" class="btn btn-light-primary font-weight-bolder mr-5" data-toggle="modal" data-target="">
                    <span class="svg-icon svg-icon-md">
                        {{  \App\Classes\Theme\Metronic::getSVG('media/svg/icons/Files/Export.svg') }}
                    </span>
                    {{__('exportExcel')}}
                </button>--}}
                <!--end::Button-->

                <!--begin::Button-->
                <button id="exportExcel" class="btn btn-primary font-weight-bolder" data-toggle="modal" data-target="#employee_modal">
                    <span class="svg-icon svg-icon-md">
                        {{  \App\Classes\Theme\Metronic::getSVG('media/svg/icons/Code/Plus.svg') }}
                        <!--end::Svg Icon-->
                    </span>{{__('Thêm mới')}}</a>
                </button>
                <!--end::Button-->
            </div>
        </div>

        <div class="card-body">
            <!--begin: Search Form-->
            <div class="mb-7">
                <form action="" id="payroll_list_search_form">
                    <div class="row align-items-center">
                        <div class="col-lg-9 col-xl-9">
                            <div class="row align-items-center">
                                <div class="col-md-3 my-2 my-md-0 pr-0">
                                    <div class="align-items-center">
                                        <label class="mr-3 mb-0 d-none d-md-block">{{ __('Nhập tên nhân viên') }}</label>
                                        <input type="" autocomplete="off"  name="employee_name" class=" form-control" value="" placeholder="{{ __('Nhập tên nhân viên') }}">
                                    </div>
                                </div>
                                <div class="col-md-3 my-2 my-md-0 pr-0">
                                    <div class="align-items-center">
                                        <label class="mr-3 mb-0 d-none d-md-block cc_cursor">{{ __('Phòng ban') }}</label>
                                        <select class="form-control select2 is-valid" id="department_select" name="department_id">
                                            <option value="0" selected disabled>{{ __('Phòng ban') }}</option>
                                            @foreach($allDeparment as $department)
                                                <option value="{{ $department->department_id }}" >{{ $department->department_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 my-2 my-md-0 pr-0">
                                    <div class="align-items-center">
                                        <label class="mr-3 mb-0 d-none d-md-block cc_cursor">{{ __('Tình trạng hoạt động') }}</label>
                                        <select class="form-control select2 is-valid" id="kt_selectStatus" name="is_active">
                                            <option value="0" selected disabled><span class="text-danger">{{ __('Trạng thái') }}</span></option>
                                            <option value="0"><span class="text-danger">{{ __('Nghỉ việc') }}</span></option>
                                            <option value="1" ><span class="text-success">{{ __('Hoạt động') }}</span></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 my-2 my-md-0 pr-0">
                                    <div class="align-items-center">
                                        <label class="mr-3 mb-0 d-none d-md-block">{{ __('xin_roles') }}</label>
                                        <select class="form-control select2 is-valid" id="kt_select2_empolyee_deparment" name="role">
                                            <option value="0" selected disabled>{{ __('xin_roles') }}</option>
                                            @foreach($listRole as $key => $role)
                                                <option value="{{ $key }}" >{{ $role }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-xl-3 text-right">
                            <label class="mr-3 mb-0 d-none d-md-block cc_cursor">&nbsp</label>
                            <button type="submit" class="btn btn-primary font-weight-bold mr-3">{{ __('xin_search') }}
                                <span> {{  \App\Classes\Theme\Metronic::getSVG('media/svg/icons/General/Search.svg') }}</span>
                            </button>
                            <button  id="resetForm" class="btn btn-light-primary px-6 font-weight-bold">{{ __('xin_reset') }}</button>
                        </div>
                    </div>
                </form>
            </div>
            <!--end::Search Form-->
            <!--begin: Datatable-->
            <div class="datatable datatable-bordered datatable-head-custom 222" id="payroll_list"></div>

            <!--end: Datatable-->
        </div>
        <!--begin::Modal-->
        <div class="modal fade" id="employee_modal" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="employee_modal" aria-hidden="true" data-form-url="{{ route('employee_managements.ajax.create_form_employee')}}">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __("setup_profile_employee") }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i aria-hidden="true" class="ki ki-close"></i>
                        </button>
                    </div>
                    <div class="modal-body form-container" style="min-height:150px">
                    </div>
                </div>
            </div>
        </div>
        <!--end::Modal-->
        <!--begin::Modal Change Password-->
        <div class="modal fade" id="change_password_modal" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="change_password_modal" aria-hidden="true" data-form-url="{{ route('employee_managements.ajax.change_password_form_employee')}}">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __("xin_e_details_cpassword") }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i aria-hidden="true" class="ki ki-close"></i>
                        </button>
                    </div>
                    <div class="modal-body form-container" style="min-height:150px">
                    </div>
                </div>
            </div>
        </div>
        <!--end::Modal Change Password-->
        <!-- Modal-->
        <!-- Modal-add-plus&minus -->
        <div class="modal fade" id="openFileExcel" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
            <div class="modal-dialog" role="document" style="max-width: 60%;margin: 1.75rem auto;">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="title_plusMinus">
                            <h5 class="modal-title" id="exampleModalLabel">{{ __('update_excel_plus_minus') }}</h5>
                        </div>
                        <div class="group_button row">
                            <button type="button" class="save_excel_upload d-none btn btn-primary font-weight-bold mr-5">{{ __('xin_save') }}</button>
                            <button type="button" class="close mr-5" data-dismiss="modal" aria-label="Close">
                                <i aria-hidden="true" class="ki ki-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="modal-body appenScroll">
{{--                        up-ex d-flex justify-content-center--}}
{{--                        <form action="" id="exup">--}}
{{--                            <div class="excel_upload" id="up_now">--}}
{{--                                <input type="file" class="d-none" name="file_excel" id="excel_upload">--}}
{{--                                {{ Metronic::getSVG("media/svg/icons/Files/Upload-folder.svg", "openFile") }}--}}
{{--                            </div>--}}
{{--                        </form>--}}
                        <form action="">
                            @csrf
                        <div class="form-group row">
{{--                            <label class="col-form-label col-lg-12 col-sm-12 text-lg-right">Multiple File Upload</label>--}}
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="dropzone dropzone-default dropzone-primary" id="kt_dropzone_2">
                                    <input type="hidden" name="excel_upload" id="excel_upload" value="">
                                    <div class="dropzone-msg dz-message needsclick">
                                        <h3 class="dropzone-msg-title">Chọn file tải lên.</h3>
                                        <span class="dropzone-msg-desc">Định dạng xlsx</span>
                                    </div>
                                </div>
                                <div class="table_upfile">
                                </div>
                            </div>
                        </div>
                        </form>
                    </div>
                    <div class="modal-footer footer-dropzone d-none">
{{--                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>--}}
                        <button type="button" class="reset_form btn btn-primary font-weight-bold">{{ __('xin_reset') }}</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@section('scripts')
    <script>

        window._tables = {
            'payroll_list': {
                'url': '{{ route('employee_managements.list_staff') }}',
                'columns': [
                    {
                        field: 'STT',
                        title: '{{ __('STT') }}',
                        sortable: false,
                        width:30,
                        textAlign: 'center',
                        template: function (row, index) {
                            return ++index;
                        }
                    },
                    {
                        field: 'employee_name',
                        title: '{{ __('employee_name') }}',
                        sortable: false,
                        width: 120,
                        template: function (row) {
                            return row.first_name +' '+ row.last_name
                        }
                    },
                    {
                        field: 'employee_id',
                        title: '{{ __('dashboard_employee_id') }}',
                        sortable: false,
                        width: 120,
                        textAlign: 'center',
                        template: function (row) {
                            return row.employee_id
                        }
                    },
                    {
                        field: 'position',
                        title: '{{ __('xin_hr_jb_positions') }}',
                        width: 180,
                        sortable: false,
                        textAlign: 'left',
                        template: function (row) {
                            let department = row.department ? row.department.department_name : '';
                            let designation = row.designation ? row.designation.designation_name : '';
                            let html = '<p class="m-0">Công ty: <strong>'+row.company.name+'</strong> </p>';
                            html += '<p class="m-0">Phòng ban: <strong>'+department+'</strong> </p>';
                            html += '<p class="m-0">Chức vụ: <strong>'+designation+'</strong> </p>';
                            return html;
                        }
                    },
                    {
                        field: 'xin_salary',
                        title: '{{ __('xin_title_salary_profile') }}',
                        // width: 180,
                        sortable: false,
                        textAlign: 'left',
                        template: function (row) {
                            if(row.wages_type == 1){
                                var salary = row.basic_salary ? row.basic_salary.split(".").join("") : 0
                            }else{
                                var salary = row.salary_trail_work ? row.salary_trail_work.split(".").join("") : 0
                            }
                            return window._userCurrency(parseInt(salary));
                        }
                    },
                    {
                        field: 'xin_e_details_contact',
                        title: '{{ __('xin_e_details_contact') }}',
                        width: 180,
                        sortable: false,
                        textAlign: 'left',
                        template: function (row) {
                            return row.contact_no +"<br/>"+ row.email
                        }
                    },
                    {
                        field: 'status_employee',
                        title: '{{ __('status_employee') }}',
                        width: 120,
                        sortable: false,
                        template: function (row) {
                            let is_active = {
                                0: {'status': 'Nghỉ việc'},
                                1: {'status':'Hoạt động'},
                            };
                            let is_active_color = {
                                0: {'background': 'label-light-danger'},
                                1: {'background':'label-light-success'},
                            }
                            let is_active_bg = {
                                0: {'bg': 'btn-danger'},
                                1: {'bg':'btn-success'},
                            }
                            let data1 = row.is_active == 1 ? 'selected' : '';
                            let data2 = row.is_active == 0 ? 'selected' : '';
                            // let combobox = '<div class="form-group select2_">\n' +
                            //     '    <select style="width: 120px"  class="form-control form-select select2_active '+is_active_bg[row.is_active].bg+' " >\n' +
                            //     '      <option '+data1+' value="1">Hoạt động</option>\n' +
                            //     '      <option '+data2+'  value="0">Nghỉ việc</option>\n' +
                            //     '    </select>\n' +
                            //     '  </div>';
                            let boxdropdown = '<div class="dropdown">\n' +
                                '  <button class="btn btn-secondary dropdown-toggle '+is_active_bg[row.is_active].bg+'" type="button" id="dropdownMenuActive" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">\n' +
                                '    '+is_active[row.is_active].status+'  \n' +
                                '  </button>\n' +
                                '  <div class="dropdown-menu" data-userId="'+row.user_id+'"  aria-labelledby="dropdownMenuButton">\n' +
                                '    <a class="dropdown-item changeIsActive" data-active="1" href="#">Hoạt động</a>\n' +
                                '    <a class="dropdown-item changeIsActive" data-active="0" href="#">Nghỉ việc</a>\n' +
                                '  </div>\n' +
                                '</div>';
                            // let html = '<span class="label label-lg font-weight-bold '+is_active_color[row.is_active].background+' label-inline" >'+is_active[row.is_active].status+'</span>';
                            return  boxdropdown;
                            /*var status = {
                                1: {'class': ' label-light-danger'},
                                2: {'class': ' label-light-warning'},
                                3: {'class': ' label-light-primary'},
                            };
                            return '<span class="label label-lg font-weight-bold ' + status[row.leave_time_types].class + ' label-inline">' + row.leave_types + '</span>';*/
                        }
                    },
                    {
                        field: 'qltt',
                        title: '{{ __('Quản lý trực tiếp') }}',
                        width: 150,
                        sortable: false,
                        template: function (row) {
                            return  (row.report_to)?row.report_to.first_name+' '+row.report_to.last_name:'';
                        }
                    },
                    {
                        field: 'xin_roles',
                        title: '{{ __('xin_roles') }}',
                        width: 80,
                        sortable: false,
                        template: function (row) {
                            let role = (row.role) ? row.role.name : '';
                            return role;
                        }
                    },
                    {
                        field: 'is_approved',
                        title: '{{ __('dashboard_xin_status') }}',
                        sortable: false,
                        autoHide: false,
                        textAlign: 'center',
                        width: 200,
                        template: function (row) {
                            let html = '';
                            html = "<a href='{{ url('employee_managements/immigration') }}/"+row.user_id+"'  class='btn btn-sm btn-clean btn-icon'>"+'<span data-toggle="tooltip" title="{{ __('Xem Chi Tiết') }}" class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\\wamp64\\www\\keenthemes\\themes\\metronic\\theme\\html\\demo1\\dist/../src/media/svg/icons\\Shopping\\Money.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\n' +
                                '    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\n' +
                                '        <rect x="0" y="0" width="24" height="24"/>\n' +
                                '        <path d="M7,3 L17,3 C19.209139,3 21,4.790861 21,7 C21,9.209139 19.209139,11 17,11 L7,11 C4.790861,11 3,9.209139 3,7 C3,4.790861 4.790861,3 7,3 Z M7,9 C8.1045695,9 9,8.1045695 9,7 C9,5.8954305 8.1045695,5 7,5 C5.8954305,5 5,5.8954305 5,7 C5,8.1045695 5.8954305,9 7,9 Z" fill="#000000"/>\n' +
                                '        <path d="M7,13 L17,13 C19.209139,13 21,14.790861 21,17 C21,19.209139 19.209139,21 17,21 L7,21 C4.790861,21 3,19.209139 3,17 C3,14.790861 4.790861,13 7,13 Z M17,19 C18.1045695,19 19,18.1045695 19,17 C19,15.8954305 18.1045695,15 17,15 C15.8954305,15 15,15.8954305 15,17 C15,18.1045695 15.8954305,19 17,19 Z" fill="#000000" opacity="0.3"/>\n' +
                                '    </g>\n' +
                                '</svg><!--end::Svg Icon--></span>'+"</a>";

                            html += "<a  href='javascript:;' data-toggle='modal' data-target='#employee_modal' data-id="+row.user_id+" class='btn btn-sm btn-clean btn-icon'>"+'<span data-toggle="tooltip" title="{{ __('xin_update') }}" class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2020-10-29-133027/theme/html/demo1/dist/../src/media/svg/icons/Design/Edit.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\n' +
                                '    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\n' +
                                '        <rect x="0" y="0" width="24" height="24"/>\n' +
                                '        <path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "/>\n' +
                                '        <rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"/>\n' +
                                '    </g>\n' +
                                '</svg><!--end::Svg Icon--></span>'+"</a>";
                            html += "<a  href='#' data-toggle='modal' data-target='#change_password_modal' data-id="+row.user_id+" class='btn btn-sm btn-clean btn-icon'>"+'<span data-toggle="tooltip" title="{{ __('xin_e_details_cpassword') }}" class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2020-10-29-133027/theme/html/demo1/dist/../src/media/svg/icons/Design/Edit.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\n' +
                                    '<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\n' +
                                        '<rect x="0" y="0" width="24" height="24"/>\n' +
                                        '<polygon fill="#000000" opacity="0.3" transform="translate(8.885842, 16.114158) rotate(-315.000000) translate(-8.885842, -16.114158) " points="6.89784488 10.6187476 6.76452164 19.4882481 8.88584198 21.6095684 11.0071623 19.4882481 9.59294876 18.0740345 10.9659914 16.7009919 9.55177787 15.2867783 11.0071623 13.8313939 10.8837471 10.6187476"/>\n' +
                                        '<path d="M15.9852814,14.9852814 C12.6715729,14.9852814 9.98528137,12.2989899 9.98528137,8.98528137 C9.98528137,5.67157288 12.6715729,2.98528137 15.9852814,2.98528137 C19.2989899,2.98528137 21.9852814,5.67157288 21.9852814,8.98528137 C21.9852814,12.2989899 19.2989899,14.9852814 15.9852814,14.9852814 Z M16.1776695,9.07106781 C17.0060967,9.07106781 17.6776695,8.39949494 17.6776695,7.57106781 C17.6776695,6.74264069 17.0060967,6.07106781 16.1776695,6.07106781 C15.3492424,6.07106781 14.6776695,6.74264069 14.6776695,7.57106781 C14.6776695,8.39949494 15.3492424,9.07106781 16.1776695,9.07106781 Z" fill="#000000" transform="translate(15.985281, 8.985281) rotate(-315.000000) translate(-15.985281, -8.985281) "/>\n' +
                                    '</g>\n' +
                                '</svg><!--end::Svg Icon--></span>'+"</a>";
                              if(row.status_finger==0){
                            html += "<a  href='#' data-toggle='modal' data-target='#update_finger' data-active='1' data-id="+row.user_id+" class='btn btn-sm btn-clean btn-icon changeFinger'>"+'<span data-toggle="tooltip" title="{{ __('xin_e_details_finger') }}" class="svg-icon svg-icon-primary svg-icon-2x">' +

                                '<i class="fas fa-fingerprint" style="color:#3699FF" ></i></span>'+"</a>";
                              }else{
                            html += "<a  href='#' data-toggle='modal' data-target='#update_finger' data-active='0' data-id="+row.user_id+" class='btn btn-sm btn-clean btn-icon changeFinger'>"+'<span data-toggle="tooltip" title="{{ __('xin_e_no_finger') }}" class="svg-icon svg-icon-primary svg-icon-2x">' +

                                    '<i class="fas fa-fingerprint" style="color:#3699FF; text-decoration: line-through red 5px;" ></i></span>'+"</a>";
                              }
                            return html;
                        }
                    },
                ],
                'search_from': '#payroll_list_search_form'
            }
        };
        $('#resetForm').click(function(){
            $('#payroll_list_search_form').trigger("reset");
            $('#payroll_list_search_form select').trigger("change");
        });
        $('.select2_active').select2({
            minimumResultsForSearch: -1
        });
        $(document).on('click', '.changeIsActive', function () {
            let employee_id = $(this).parent().attr("data-userId");
            console.log(employee_id);
            let is_active = $(this).attr("data-active");
            $.ajax({
                type: 'post',
                url : '{{ route('employee_managements.ajax.changeIsActive') }}',
                data: {
                    employee_id : employee_id,
                    is_active : is_active
                },
            }).done(function (data) {
                if(data.success === true){
                    window._tables.payroll_list.datatable.reload();
                }else{
                    toastr.error(__("error"));
                }
            }).fail(function (jqXHR, reponse){
                toastr.error(__("error"));
            });
        })
        $(document).on('click', '.changeFinger', function () {
            $(this).hide()

            let user_id = $(this).attr("data-id");
            console.log(user_id)
            let is_finger = $(this).attr("data-active");
            console.log(is_finger);
            $.ajax({
                type: 'post',
                url : '{{ route('employee_managements.ajax.changeIsFinger') }}',
                data: {
                    user_id : user_id,
                    is_finger : is_finger
                },
            }).done(function(data) {
                if(data.success === true){
                    if(is_finger==1){
                        toastr.success(__("update_finger_success"));
                        window._tables.payroll_list.datatable.reload();

                    }else{
                     toastr.success(__("no_finger"));
                     window._tables.payroll_list.datatable.reload();
                    }
                }else{
                    toastr.error(__("error"));
                }
            }).fail(function (jqXHR, reponse){
                toastr.error(__("error"));
            });
        })
        $('#employee_modal').on('shown.bs.modal', function (e) {
            console.log(window._loading("#employee_modal .modal-body"));
            let button = $(e.relatedTarget);
            let id = button.data('id');
            let url = $('#employee_modal').data('form-url');
            $.ajax({
                url: url,
                data: {
                    id: id
                }
            })
            .done(function (response) {

                console.log(window._loading("#employee_modal .modal-body", false));
                $("#employee_modal .modal-body").html(response);
            })
            .fail(function (jqXHR, status){
                window._loading("#employee_modal .modal-body", false);
                toastr.error(__("order_form_fetch_error"));
                window._display_alert("#employee_modal .modal-body", __("order_form_fetch_error"));
            });
        });

        $('#change_password_modal').on('shown.bs.modal', function (e) {
            window._loading("#change_password_modal .modal-body");
            let button = $(e.relatedTarget);
            let id = button.data('id');
            let url = $('#change_password_modal').data('form-url');
            $.ajax({
                url: url,
                data: {
                    id: id
                }
            })
            .done(function (response) {
                window._loading("#change_password_modal .modal-body", false);
                $("#change_password_modal .modal-body").html(response);
            })
            .fail(function (jqXHR, status){
                window._loading("#change_password_modal .modal-body", false);
                toastr.error(__("order_form_fetch_error"));
                window._display_alert("#change_password_modal .modal-body", __("order_form_fetch_error"));
            });
        });
        $("body").tooltip({ selector: '[data-toggle=tooltip]' });

        $(document).ready(function (){
           $("#changeIsActive").each(function () {
               $(this).find("option").text('111');
           })
        });

    </script>

    <script src="{{ mix('js/payroll/payroll.js') }}" type="text/javascript"></script>
    <script src="{{ mix('js/list.js') }}" type="text/javascript"></script>

@endsection
