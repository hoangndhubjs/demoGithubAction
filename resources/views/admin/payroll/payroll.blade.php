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
        .notFoundData {
            border-top: 1px solid #f1f1f1;
            height: 100%;
        }
        .notFoundData .nodata {
            color: #3A528F;
            position: relative;
        }
        .create_salary {
            text-align: center;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .datepicker table tr td span.disabled, .datepicker table tr td span.disabled:hover {
            background: none;
            color: #777777;
            cursor: default;
            opacity: 0.60;
            cursor: no-drop !important;
        }
        .text-genarate-mobile{
            text-align: right;
        }
        @media (max-width: 700px) {
            .text-genarate-mobile{
                text-align: unset;
            }
        }
        @media (max-width: 460px) {
            #exportExcelPayroll {
                margin-top: 1rem;
            }
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
    </style>
    <div class="card card-custom gutter-b h-100">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{__($page_title)}}
                    <span class="d-block text-muted pt-2 font-size-sm">{{__('listsPayroll')}}</span></h3>
            </div>

            <div class="card-toolbar">
            @if($check_exits > 0)
                    <button type="button"  data-toggle="modal" data-target="#openFileExcel" class="btn btn-light-primary font-weight-bolder mr-5">
                        {{  \App\Classes\Theme\Metronic::getSVG('media/svg/icons/Files/Uploaded-file.svg') }}
                        {{ __('Tải file Excel') }}
                    </button>
                <!--begin::Button-->
                <button onclick="payment(null, 1)" class="btn btn-primary font-weight-bolder mr-5">
                    <span class="svg-icon svg-icon-md">
                        {{  \App\Classes\Theme\Metronic::getSVG('media/svg/icons/Shopping/Wallet.svg') }}
                    </span>
                    {{__('xin_payroll_make_payment')}}
                </button>
                <!--end::Button-->
                <!--begin::Button-->
{{--                <button onclick="sendMail(null,1)"  class="btn btn-light-primary font-weight-bolder mr-5">--}}
{{--                     <span class="svg-icon svg-icon-md">--}}
{{--                        {{  \App\Classes\Theme\Metronic::getSVG('media/svg/icons/Communication/Mail-opened.svg') }}--}}
{{--                    </span>{{__('send_pay_stubs')}}--}}
{{--                </button>--}}
                <!--end::Button-->
                <button id="exportExcelPayroll" class="btn btn-light-primary font-weight-bolder" data-toggle="modal" data-target="#update_overtime_request">
                    <span class="svg-icon svg-icon-md">
                        {{  \App\Classes\Theme\Metronic::getSVG('media/svg/icons/Files/Export.svg') }}
                    </span>
                    {{__('exportExcel')}}
                </button>
                @endif
            </div>
        </div>

        <div class="card-body">
            @if($check_exits > 0)
            <div class="showDataTable">
            <!--begin: Search Form-->
            <div class="mb-7">
                <div class="row">
                    <div class="col-lg-10 col-xs-12">
                        <form action="" id="payroll_list_search_form">
                            <div class="row align-items-center">
                                <div class="col-lg-12 col-xl-12">
                                    <div class="row align-items-center">
                                        <div class="col-md-2 my-2 my-md-0 pr-0">
                                            <div class="align-items-center">
                                                <label class="mr-3 mb-0 d-none d-md-block cc_cursor">{{ __('left_company') }}</label>
                                                <select class="form-control select2 is-valid value_company" id="kt_select2_1" name="company_id">
                                                    <option value="0" selected disabled>{{ __('xin_acc_all') }}</option>
                                                    @foreach($all_companies as $company_id)
                                                        <option {{ $company_id->company_id == 1 ?? 'selected' }} value="{{ $company_id->company_id }}">{{ $company_id->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2 my-2 my-md-0 pr-0">
                                            <div class="align-items-center">
                                                <label class="mr-3 mb-0 d-none d-md-block">{{ __('Phòng ban') }}</label>
                                                <select class="form-control select2 is-valid" id="kt_select2_department" name="department_id">
                                                    <option value="0" selected disabled>{{ __('Phòng ban') }}</option>
                                                    @foreach ($allDeparment as $department_id)
                                                        <option value="{{ $department_id->department_id }}">{{ $department_id->department_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2 my-2 my-md-0 pr-0">
                                            <div class="align-items-center">
                                                <label class="mr-3 mb-0 d-none d-md-block">{{ __('Tình trạng') }}</label>
                                                <select class="form-control select2 is-valid value_status_payslip" id="kt_select2_status" name="status_payslip">
                                                    <option value="0" selected disabled>{{ __('kpi_status') }}</option>
                                                    <option value="2">Đã thanh toán</option>
                                                    <option value="1">Chưa thanh toán</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2 my-2 my-md-0 pr-0">
                                            <div class="align-items-center">
                                                <label class="mr-3 mb-0 d-none d-md-block">{{ __('Month') }}</label>
                                                <input type="" autocomplete="off"  name="month_payslip" id="month_filter" class="month_filter form-control" value="{{ $dateSub }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-xl-3 mt-5 mt-lg-0">
                                            <label class="mr-3 mb-0 d-none d-md-block cc_cursor">&nbsp</label>
                                            <button type="submit" class="btn btn-primary px-6 font-weight-bold mr-3">{{ __('xin_search') }}
                                                <span> {{  \App\Classes\Theme\Metronic::getSVG('media/svg/icons/General/Search.svg') }}</span>
                                            </button>
                                            <button  id="resetForm" class="btn btn-light-primary px-6 font-weight-bold">{{ __('xin_reset') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-2 col-xs-3 text-genarate-mobile">
                        <label class="d-flex">&nbsp;</label>
                        <button id="btnRegenerate" class="btn btn-danger">{{ __('regenerate_payslips') }}</button>
                    </div>

                </div>

            </div>
            <!--end::Search Form-->
            <!--begin: Datatable-->
            <div class="datatable datatable-bordered datatable-head-custom" id="payroll_list"></div>
            <!--end: Datatable-->
            <!-- Modal-add-plus&minus -->
            <div class="modal fade" id="openFileExcel" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                    <div class="modal-dialog" role="document" style="max-width: 60%;margin: 1.75rem auto;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div class="title_plusMinus">
                                    <h5 class="modal-title" id="exampleModalLabel">{{ __('update_excel_plus_minus') }}</h5>
                                </div>
                                <div class="group_button row">
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
                                <button type="button" class="save_excel_upload d-none btn btn-primary font-weight-bold mr-5">{{ __('xin_save') }}</button>
                                <button type="button" class="reset_form btn btn-light-primary font-weight-bold">{{ __('update_again') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="notFoundData border-top mb-2">
                <div class="mt-2">
                    <p class="nodata">Hiện tại chưa có thông tin lương của tháng {{ $date_before }}. Vui lòng bấm nút dưới đây để tạo phiếu lương!</p>
                </div>
                <div class="create_salary">
                    <button id="create_pay_stubs" class="btn btn-outline-primary btn-light-primary btn-hover-light-primary">
                        <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Files\File.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <polygon points="0 0 24 0 24 24 0 24"/>
                            <path d="M5.85714286,2 L13.7364114,2 C14.0910962,2 14.4343066,2.12568431 14.7051108,2.35473959 L19.4686994,6.3839416 C19.8056532,6.66894833 20,7.08787823 20,7.52920201 L20,20.0833333 C20,21.8738751 19.9795521,22 18.1428571,22 L5.85714286,22 C4.02044787,22 4,21.8738751 4,20.0833333 L4,3.91666667 C4,2.12612489 4.02044787,2 5.85714286,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                            <rect fill="#000000" x="6" y="11" width="9" height="2" rx="1"/>
                            <rect fill="#000000" x="6" y="15" width="5" height="2" rx="1"/>
                        </g>
                    </svg><!--end::Svg Icon--></span>
                        {{ __('Tạo phiếu lương') }}
                    </button>
                </div>
            </div>
             @endif
        </div>
    </div>
    <!-- begin: Modal -->
    <div class="modal fade" id="detailSalaryPopup" tabindex="-1" role="dialog" aria-labelledby="detailSalaryPopup" aria-hidden="true" data-form-url="{{ route('payrolls.ajax.create_form')}}">
        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">
                <div class="modal-body form-container" style="min-height:150px">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
    <!-- end: Modal -->
@endsection
@section('scripts')
    <script>
        window._tables = {
            'payroll_list': {
                'url': '{{ route('payrolls.ajax.lists') }}',
                'columns': [
                    {
                        field: 'RecordID',
                        title: '#',
                        sortable: false,
                        width: 20,
                        type: 'number',
                        selector:  {
                            class: 'checkvaluePayroll'
                        },
                        template: function (row) {
                            return row.payslip_id;
                        },
                        textAlign: 'center',
                    },
                    {
                        field: 'STT',
                        title: '{{ __('STT') }}',
                        sortable: false,
                        width:30,
                        template: function (row, index) {
                            return ++index;
                        }
                    },
                    {
                        field: 'dashboard_employee_id',
                        title: '{{ __('dashboard_employee_id') }}',
                        sortable: false,
                        width:92,
                        textAlign: 'center',
                        template: function (row) {
                            return row.employee_salary.employee_id;
                        }
                    },
                    {
                        field: 'employee_name',
                        title: '{{ __('employee_name') }}',
                        sortable: false,
                        width:104,
                        template: function (row) {
                           return row.employee_salary.first_name +' '+ row.employee_salary.last_name
                        }
                    },
                    {
                        field: 'Month',
                        title: '{{ __('Month') }}',
                        sortable: false,
                        textAlign: 'center',
                        template: function (row) {
                            return row.salary_month;
                        }
                    },
                    {
                        field: 'salary_type',
                        title: '{{ __('xin_salary_type') }}',
                        width: 110,
                        sortable: false,
                        textAlign: 'center',
                        template: function (row) {
                            let status = {
                                0: {'color': 'text-success'},
                                1: {'color': 'text-success'},
                                2: {'color': 'text-primary'},
                                3: {'color': 'text-primary'},
                                4: {'color': 'text-primary'},
                            };
                            let status_color = {
                                0: {'class': 'bg-success'},
                                1: {'class': 'bg-success'},
                                2: {'class': 'bg-primary'},
                                3: {'class': 'bg-primary'},
                                4: {'class': 'bg-primary'},
                            };
                            let status_type = {
                                0: {'lable':'Lương cơ bản'},
                                1: {'lable':'Lương cơ bản'},
                                2: {'lable':'Lương thử việc'},
                                3: {'lable':'Lương Partime'},
                                4: {'lable':'Lương thực tập'},
                            };
                            return '<span class="font-weight-bold dots_ps ' + status[row.wages_type].color + '">' + status_type[row.wages_type].lable + '<span class="dots_as '+ status_color[row.wages_type].class +'">&nbsp;</span></span>';
                        }
                    },
                    {
                        field: 'basic_salary',
                        title: '{{ __('xin_payroll_basic_salary') }}',
                        width: 120,
                        sortable: false,
                        textAlign: 'center',
                        template: function (row) {
                            return row.wages_type == 2 ? window._userCurrency(parseInt(row.trail_salary)) : window._userCurrency(row.basic_salary);
                        }
                    },
                    {
                        field: 'total_salary_month',
                        title: '{{ __('xin_total_salary_month') }}',
                        width: 130,
                        sortable: false,
                        textAlign: 'center',
                        template: function (row) {
                            // return '';
                            return  window._userCurrency(parseInt(row.grand_net_salary));
                        }
                    },
                    {
                        field: 'is_approved',
                        title: '{{ __('dashboard_xin_status') }}',
                        sortable: false,
                        autoHide: false,
                        textAlign: 'center',

                        template: function (row) {
                            let status = {
                                2: {'class': ' label-light-success'},
                                1: {'class': ' label-light-warning'},
                            };
                            let status_type = {
                                1: {'lable':'Chưa thanh toán'},
                                2: {'lable':'Đã thanh toán'},
                            };
                            return '<span class="label label-lg w-100 font-weight-bold ' + status[row.status].class + ' label-inline">' + status_type[row.status].lable + '</span>';
                        }
                    },
                    {
                        field: 'action',
                        title: '{{ __('xin_action') }}',
                        sortable: false,
                        autoHide: false,
                        textAlign: 'center',
                        width: 140,
                        template: function (row) {
                            let iconsEye  =  '<span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\\wamp64\\www\\keenthemes\\themes\\metronic\\theme\\html\\demo1\\dist/../src/media/svg/icons\\General\\Visible.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\n' +
                                '    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\n' +
                                '        <rect x="0" y="0" width="24" height="24"/>\n' +
                                '        <path d="M3,12 C3,12 5.45454545,6 12,6 C16.9090909,6 21,12 21,12 C21,12 16.9090909,18 12,18 C5.45454545,18 3,12 3,12 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>\n' +
                                '        <path d="M12,15 C10.3431458,15 9,13.6568542 9,12 C9,10.3431458 10.3431458,9 12,9 C13.6568542,9 15,10.3431458 15,12 C15,13.6568542 13.6568542,15 12,15 Z" fill="#000000" opacity="0.3"/>\n' +
                                '    </g>\n' +
                                '</svg><!--end::Svg Icon--></span>';
                            let iconsTT = '<span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\\wamp64\\www\\keenthemes\\themes\\metronic\\theme\\html\\demo1\\dist/../src/media/svg/icons\\Communication\\Mail-opened.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\n' +
                                '    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\n' +
                                '        <rect x="0" y="0" width="24" height="24"/>\n' +
                                '        <path d="M6,2 L18,2 C18.5522847,2 19,2.44771525 19,3 L19,12 C19,12.5522847 18.5522847,13 18,13 L6,13 C5.44771525,13 5,12.5522847 5,12 L5,3 C5,2.44771525 5.44771525,2 6,2 Z M7.5,5 C7.22385763,5 7,5.22385763 7,5.5 C7,5.77614237 7.22385763,6 7.5,6 L13.5,6 C13.7761424,6 14,5.77614237 14,5.5 C14,5.22385763 13.7761424,5 13.5,5 L7.5,5 Z M7.5,7 C7.22385763,7 7,7.22385763 7,7.5 C7,7.77614237 7.22385763,8 7.5,8 L10.5,8 C10.7761424,8 11,7.77614237 11,7.5 C11,7.22385763 10.7761424,7 10.5,7 L7.5,7 Z" fill="#000000" opacity="0.3"/>\n' +
                                '        <path d="M3.79274528,6.57253826 L12,12.5 L20.2072547,6.57253826 C20.4311176,6.4108595 20.7436609,6.46126971 20.9053396,6.68513259 C20.9668779,6.77033951 21,6.87277228 21,6.97787787 L21,17 C21,18.1045695 20.1045695,19 19,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,6.97787787 C3,6.70173549 3.22385763,6.47787787 3.5,6.47787787 C3.60510559,6.47787787 3.70753836,6.51099993 3.79274528,6.57253826 Z" fill="#000000"/>\n' +
                                '    </g>\n' +
                                '</svg><!--end::Svg Icon--></span>';
                            let iconsSendMail = '<span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\\wamp64\\www\\keenthemes\\themes\\metronic\\theme\\html\\demo1\\dist/../src/media/svg/icons\\Shopping\\Wallet.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\n' +
                                '    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\n' +
                                '        <rect x="0" y="0" width="24" height="24"/>\n' +
                                '        <circle fill="#000000" opacity="0.3" cx="20.5" cy="12.5" r="1.5"/>\n' +
                                '        <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 6.500000) rotate(-15.000000) translate(-12.000000, -6.500000) " x="3" y="3" width="18" height="7" rx="1"/>\n' +
                                '        <path d="M22,9.33681558 C21.5453723,9.12084552 21.0367986,9 20.5,9 C18.5670034,9 17,10.5670034 17,12.5 C17,14.4329966 18.5670034,16 20.5,16 C21.0367986,16 21.5453723,15.8791545 22,15.6631844 L22,18 C22,19.1045695 21.1045695,20 20,20 L4,20 C2.8954305,20 2,19.1045695 2,18 L2,6 C2,4.8954305 2.8954305,4 4,4 L20,4 C21.1045695,4 22,4.8954305 22,6 L22,9.33681558 Z" fill="#000000"/>\n' +
                                '    </g>\n' +
                                '</svg><!--end::Svg Icon--></span>';
                            let html = "";
                            // data-target='#detailSalaryPopup'
                            html += "<a href='javscript:void(0)' onclick='link_detail_salary("+row.payslip_id+")' data-id='"+row.payslip_id+"' class='btn btn-sm btn-clean btn-icon' title='{{ __('Chi tiết lương') }}'>"+iconsEye+"</a>";
                            {{--html += "<a href='javascript:;' onclick='sendMail("+row.payslip_id+",2)' data-payslip="+row.payslip_id+"  class='sendEmailSingle btn btn-sm btn-clean btn-icon' title='{{ __('Gửi bảng lương Email') }}'>"+iconsTT+"</a>";--}}
                            html += "<a href='javscript:void(0)' onclick='payment("+row.payslip_id+",2)'  data-payment="+row.employee_id+" class='payroll_payment btn btn-sm btn-clean btn-icon' title='{{ __('Thanh toán') }}'>"+iconsSendMail+"</a>";


                            {{--html += "<a href='#' onclick='updateCompensations("+row.advance_salary_id+")' class='btn btn-sm btn-clean btn-icon' title='{{ __('Thanh toán') }}'>"+iconsWallet+"</a>";--}}
                                return html;
                        }
                    },
                ],
                'search_from': '#payroll_list_search_form'
            }
        };
        //blank
        function link_detail_salary(payslip_id) {
            window.open("{{ route('detail_salary_user') }}?payslip_id="+payslip_id, "_blank");
        }
        //Modal
        $('#detailSalaryPopup').on('shown.bs.modal', function (e) {
            window._loading("#detailSalaryPopup .modal-body");
            let button = $(e.relatedTarget);
            let id = button.data('id');
            let url = $('#detailSalaryPopup').data('form-url');
            $.ajax({
                url: url,
                data: {
                    id: id,
                    type : 'detail'
                }
            }).done(function (response) {
                    window._loading("#detailSalaryPopup .modal-body", false);
                    $("#detailSalaryPopup .modal-body").html(response);
                })
                .fail(function (jqXHR, status){
                    window._loading("#detailSalaryPopup .modal-body", false);
                    toastr.error(__("order_form_fetch_error"));
                    window._display_alert("#detailSalaryPopup .modal-body", __("order_form_fetch_error"));
                });
        });
         // salary payroll
        var payment = function(id, status){
            let value_checked = [];
            $(".checkvaluePayroll > input:checked").each(function (e) {
                if ($(this).val() != 'on'){
                    value_checked.push($(this).val())
                }
            });
            if (status == 1) {
                if (value_checked.length > 0) {
                    var title_status = 'Thanh toán nhân viên đã chọn';
                } else {
                    var title_status = 'Thanh toán cho toàn bộ nhân viên';
                }
            }else{
                var title_status = 'Thanh toán phiếu lương?';
            }

            Swal.fire({
                title: title_status,
                // text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Đồng ý'
            }).then((result) => {
                if (result.isConfirmed) {
                    let payslip_id = id;
                    $.ajax({
                        method: 'GET',
                        url: '{{ route('payrolls.update_payslip') }}',
                        data: {
                            // company_id: getCompany_id,
                            payslipId_arr: value_checked,
                            payslip_id: payslip_id,
                            status: status,
                            month_salary  : '{{ $date }}'
                        }
                    }).done(function (result) {
                        if (result == 'true'){
                            // toastr.success(result.data);
                            Swal.fire({
                                icon: 'success',
                                title: 'Thanh toán thành công',
                            })
                            window._tables.payroll_list.datatable.reload();
                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Đã có lỗi xẩy ra!',
                            })
                        }
                    })

                }
            })
        }
        $("#create_pay_stubs").on('click', function () {
            $(this).hide();
            $(".create_salary").append('<div class="load_salary spinner-border m-5" role="status">\n' +
                '                        <span class="sr-only">Đang tạo bảng lương...</span>\n' +
                '                    </div>');
            $.ajax({
                method: 'GET',
                url: '{{ route('payrolls.add_payslip') }}',
                data: {
                    month_salary: '{{ $date }}',
                }
            }).done(function (result){
                if (result.status){
                    location.reload();
                }
            }).fail(function (jqXHR, status){
                $("#create_pay_stubs").show();
                $(document).find(".load_salary").hide();
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Đã có lỗi xẩy ra. Vui lòng thử lại!',
                });
            });
            // setTimeout(function (){
            //     $(this).show();
            //     $(".load_salary").remove();
            // }, 2000);
        });
        var sendMail = function(payslip_id,  status) {
            let value_checked = [];
             $(".checkvaluePayroll > input:checked").each(function (e) {
                 if ($(this).val() != 'on'){
                     value_checked.push($(this).val())
                 }
             });
            Swal.fire({
                icon: 'warning',
                title: 'Đang gửi',
                text: 'Email sẽ gửi vào hàng chờ để gửi',
            })
             $.ajax({
                 method: 'GET',
                 url: '{{ route('sendMailPayroll') }}',
                 data: {
                     payslipId_arr: value_checked,
                     single_payslip : payslip_id
                 }
             }).done(function (reponse_data) {
                //  if (result == true){

                //      setTimeout(function () {
                //          window._tables.payroll_list.datatable.reload();
                //      }, 2000);
                //  }else{
                //      Swal.fire({
                //          icon: 'error',
                //          title: 'Oops...',
                //          text: 'Đã có lỗi xẩy ra!',
                //      })
                //  }
             })
        }
        //excel
        $("#exportExcelPayroll").click(function () {
            // $(this).attr("disabled", "disabled");
            let company_id =  $("#kt_select2_1").val();
            let department_id = $("#kt_select2_department").val();
            let date = $("#month_filter").val();
            let status = $("#kt_select2_status").val();
            let url = '{{ route('payrolls.payroll_month') }}';
            $.ajax({
                method: 'GET',
                url: url,
                data: {
                    date: date,
                    company_id : company_id,
                    department_id : department_id,
                    status : status ? status : 2
                }
            }).done(function (response) {
                if(response){
                    $("#exportExcel").removeAttr('disabled');
                    if(response.errors){
                        Swal.fire({
                            icon: 'error',
                            text: 'Không có dữ liệu lương tháng '+date_month,
                        });
                    }else{
                        window.location.href = url+'?date='+date+'&department_id='+department_id+'&status='+status+'&company_id='+company_id+'&module=payroll';
                    }
                }


            });
        });
        // Regenerate payslip
        $("#btnRegenerate").click(function () {
            let self = this;
            $(self).attr('disabled', true);
            $.ajax({
                method: 'GET',
                url: '{{ route('payrolls.add_payslip') }}',
                data: {
                    month_salary: '{{ $date }}',
                }
            }).done(function (result){
                if (result.status){
                    Swal.fire({
                        icon: 'success',
                        text: 'Cập nhật thành công dữ liệu.'
                    });
                    window._tables.payroll_list.datatable && window._tables.payroll_list.datatable.reload();
                    $(self).attr('disabled', false);
                }
            });
            // window.location.reload();
        })
        //upload Excel
        $('#kt_dropzone_2').dropzone({
            url: "{{ route('employee_managements.readFileExcel') }}", // Set the url for your upload script location
            paramName: "excel_upload", // The name that will be used to transfer the file
            maxFiles: 1,
            maxFilesize: 100, // MB
            addRemoveLinks: true,
            acceptedFiles: ".xlsx",
            params: function () {
                let excel_upload = $('#excel_upload').val();
                let csrf = $('meta[name="csrf-token"]').attr('content');
                return { excel_upload, _token: csrf };
            },
            init: function () {
                dropzone = this;
                this.on("maxfilesexceeded", function(file) {
                    alert("Bạn có thể upload tối đa 1 file!");
                    this.removeFile(file);
                })
                this.on("addedfile", function (file) {
                    if ($.inArray(file.type, ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']) == -1) {
                        this.removeFile(file);
                    }
                });
                this.on("complete", function(file) {
                    let type_accept = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                    if(file.status == 'error' && type_accept != file.type){
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'File tải lên không đúng định dạng!',
                        })
                    }else if(file.status == 'error'){
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Đã có lỗi xẩy ra! Vui lòng thử lại hoặc liên hệ kỹ thuật',
                        })
                    }
                    this.removeAllFiles(true);
                })
                this.on('success', function(file, response) {
                    this.removeFile(file);
                    if (response.length > 10) {
                        $(".appenScroll").addClass("scroll_excel");
                    }else if(response.length <= 0){
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Đã có lỗi xẩy ra! Vui lòng thử lại hoặc liên hệ kỹ thuật',
                        })
                    }else{
                        $(".footer-dropzone").removeClass("d-none");
                        $(".save_excel_upload").removeClass("d-none");
                    }
                    var  html = '<table class="table">';
                    html += '<thead>\n' +
                        '<th>STT</th>\n' +
                        '<th>Tên nhân viên</th>\n' +
                        '<th>ID nhân viên</th>\n' +
                        '<th>Tiêu đề khoản cộng</th>\n' +
                        '<th>Số tiền</th>\n' +
                        '<th>Tháng</th>\n' +
                        '<th>Tiêu đề khoản trừ</th>\n' +
                        '<th>Số trừ</th>\n' +
                        '<th>Tháng</th>\n' +
                        '</thead>\n' +
                        '<tbody>';
                    var count_idx = 1;
                    for (var ins = 0; ins <= response.length; ins++){
                        var data_reponse = response[ins];
                        var year_month = '';
                        if (data_reponse['minus']['amount_option'] == 2){
                            year_month = 'Khấu trừ hàng tháng';
                        }else{
                            year_month =  data_reponse['minus']['year_month'] ?? moment(data_reponse['minus']['year_month']).format("DD-YYYY");
                        }
                        var year_month_plus = data_reponse['plus']['year_month'] ?? moment(data_reponse['plus']['year_month']).format("DD-YYYY");
                        html += '<tr>\n' +
                            '<th scope="row">'+ count_idx++ +'</th>\n' +
                            '<td>'+data_reponse['name']+'</td>\n' +
                            '<td>'+data_reponse['employee_id']+'</td>\n' +
                            '<td>'+data_reponse['plus']['allowance_title']+'</td>\n' +
                            '<td>'+ window._userCurrency(data_reponse['plus']['allowance_amount']) +'</td>\n' +
                            '<td>'+year_month_plus+'</td>\n' +
                            '<td>'+data_reponse['minus']['title']+'</td>\n' +
                            '<td>'+ window._userCurrency(data_reponse['minus']['money']) +'</td>\n' +
                            '<td>'+year_month+'</td>\n' +
                            '</tr>';
                        if (ins == response.length - 1) break;
                    }
                    html += '</tbody></table>';
                    $(".table_upfile").html(html);
                    $('#kt_dropzone_2').hide();
                    $(".table_upfile").append("<input type='hidden' name='value_update' value='"+JSON.stringify(response)+"' />");
                });
            }
        });
        // update file upload
        $(document).on('click', '.dz-success-mark > .dz-remove', function (e) {
            $("body > .dz-remove").trigger('click');
        })
        $(".save_excel_upload").on('click', function (e) {
            let obj_excel = $(".table_upfile > input[name=value_update]").val();
            $.ajax({
                type: "POST",
                url: '{{ route('employee_managements.updateFileExcel') }}',
                data: {obj_excel:obj_excel},
                cache: false,
            }).done(function (data) {
                if (data){
                    $("#openFileExcel").modal('hide');
                    Swal.fire(
                        'Cập nhật thành công!',
                        '',
                        'success'
                    )
                }else{
                    Swal.fire(
                        'Cập nhật thất bại!',
                        'Vui lòng thử lại hoặc liên hệ kỹ thuật!',
                        'error'
                    )
                }
            }).fail(function (jqXHR, reponse){
                toastr.error(__("error"));
            });
        })
        //reset
        $(".reset_form").on('click', function () {
            $(this).hide();
            $(".table_upfile").html('');
            $('#kt_dropzone_2').show();
            $(".appenScroll").removeClass("scroll_excel");
            $(".footer-dropzone").addClass("d-none");
            $(".save_excel_upload").addClass("d-none");
        })
        $("#popupExcel").on('shown.bs.modal', function (e){
        });
    </script>
    <script src="{{ mix('js/payroll/payroll.js') }}" type="text/javascript"></script>
    <script src="{{ mix('js/list.js') }}" type="text/javascript"></script>

@endsection
