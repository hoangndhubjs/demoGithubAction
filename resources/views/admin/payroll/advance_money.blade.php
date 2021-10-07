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
        .modelnote{
            padding: 0 1.75rem;
        }
        .font_title_{
            font-size: 2rem !important;
        }
        .modal-dialog{
            max-width: 731px !important;
        }
        .disable-links {
            pointer-events: none;
        }
         ul.list_advanced {
             margin: 0;
             padding-left: 0;
             list-style: none;
         }
    </style>
    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{__($page_title)}}
                    <span class="d-block text-muted pt-2 font-size-sm">{{__('Danh sách tạm ứng')}}</span></h3>
            </div>
            <div class="card-toolbar ">
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
                @if (Auth::user()->isAdmin())
                <a href="#" onclick="updateMulti(1)" class="btn btn-light-primary font-weight-bolder mr-5">
                    <span class="svg-icon svg-icon-md">{{  \App\Classes\Theme\Metronic::getSVG('media/svg/icons/Navigation/Check.svg') }}</span>
                    {{__('Duyệt')}}
                </a>
                <a href="#" onclick="updateMulti(3)" class="btn btn-light-danger font-weight-bolder mr-5">
                    <span class="svg-icon svg-icon-md">
                        {{  \App\Classes\Theme\Metronic::getSVG('media/svg/icons/Navigation/Close.svg') }}
                    </span>
                        {{__('Không duyệt')}}
                </a>
                {{-- <a href="#" onclick="viewMulti(1)" class="btn btn-light-primary font-weight-bolder mr-5">
                    <span class="svg-icon svg-icon-primary svg-icon-2x"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24"/>
                            <circle fill="#000000" cx="5" cy="12" r="2"/>
                            <circle fill="#000000" cx="12" cy="12" r="2"/>
                            <circle fill="#000000" cx="19" cy="12" r="2"/>
                        </g>
                    </svg></span>
                        {{__('xin_view')}} {{__('xin_pdf')}}
                </a> --}}
                @endif
{{--                        <a href="#" onclick="updateMulti(2)" class="btn btn-light-primary font-weight-bolder mr-5">--}}
{{--                    <span class="svg-icon svg-icon-md">--}}
{{--                        {{  \App\Classes\Theme\Metronic::getSVG('media/svg/icons/Shopping/Wallet.svg') }}--}}
{{--                    </span>--}}
{{--                            {{__('xin_payroll_make_payment')}}--}}
{{--                        </a>--}}

                <!--begin::Button-->
                    <a href="#" class="btn btn-primary font-weight-bolder mr-5" data-toggle="modal" data-target="#update_advance_request">
                    <span class="svg-icon svg-icon-md">
                        {{  \App\Classes\Theme\Metronic::getSVG('media/svg/icons/Code/Plus.svg') }}
                    </span>
                        {{__('xin_add_new')}}
                    </a>
                <!--end::Button-->

            </div>
        </div>
        <div class="card-body">
            <!--begin: Datatable-->
            <div class="datatable datatable-bordered datatable-head-custom 222" id="advance_list"></div>
            <!--end: Datatable-->
        </div>
    </div>
    <!-- begin: Modal -->
    <div class="modal fade" id="update_advance_request" tabindex="-1" role="dialog" aria-labelledby="update_advance_request" aria-hidden="true" data-form-url="{{ route('payrolls.ajax.create_form')}}">
        <div class="modal-dialog modal-dialog-centered dialog_compensations" role="document">
            <div class="modal-content">
                <div class="modal-header justify-content-center border-bottom-0">
                    <h5 class="modal-title font_title_">{{ __("Thêm mới tạm ứng") }}</h5>
                </div>
                <div class="modelnote">
                    <smal class="font-italic text-danger">Lưu ý: Bạn chỉ được ứng lương tối đa 50% từ đầu tháng đến thời điểm hiện tại. Bạn được nhận tối đa <span id="salary_advance">xxx.xxx</span></smal>
                    <span id="advanced_user"></span>
                </div>
                <div class="modal-body form-container" style="min-height:150px">
                </div>
            </div>
        </div>
    </div>
    <!-- Modal PDF-->
    <div class="modal fade" id="advance-pdf" tabindex="-1" role="dialog" aria-labelledby="advancePdfLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" href="{{route('payrolls.pdfAdvanceSalary')}}" id="advancePdfLabel">{{__('xin_view')}} {{__('xin_pdf')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">{{__('xin_view')}} {{__('xin_pdf')}}</button>
            </div>
            </div>
        </div>
    </div>
    <!-- end: Modal -->

@endsection
@section('scripts')
    <script>
        window._tables = {
            'advance_list': {
                'url': '{{ route('payrolls.listAdvance_money') }}',
                'search': {
                    input: $('#kt_datatable_search_query'),
                    key: 'generalSearch'
                },
                'columns': [
                    {
                        field: 'RecordID',
                        title: '#',
                        sortable: false,
                        width: 20,
                        // type: 'number',
                        selector: {
                            class: 'checkvalueAdvance'
                        },
                        template: function (row) {
                            return row.advance_salary_id;
                        },
                        // afterTemplate: function (row, data, index) {
                        //
                        //
                        // },
                        textAlign: 'center',
                    },
                    {
                        field: 'STT',
                        title: '{{ __('STT') }}',
                        sortable: false,
                        width:30,
                        textAlign: 'center',
                        // sortable: true,
                        template: function (row, index) {
                            return ++index;
                        }
                    },
                    {
                        field: 'created_at',
                        title: '{{ __('Ngày tạo yêu cầu') }}',
                        sortable: false,
                        width:100,
                        textAlign: 'center',
                        template: function (row) {
                            return moment(row.created_at).format('DD-MM-YYYY');
                        }
                    },
                    {
                        field: 'advane',
                        title: '{{ __('Tháng tạm ứng') }}',
                        sortable: false,
                        textAlign: 'center',
                        template: function (row) {
                            return row.month_year
                        }
                    },
                    {
                        field: 'employee_name',
                        title: '{{ __('employee_name') }}',
                        sortable: false,
                        textAlign: 'center',
                        template: function (row) {
                            return row.employee_asset.last_name +' '+ row.employee_asset.first_name
                        }
                    },
                    {
                        field: 'ct',
                        title: '{{ __('Công ty') }}',
                        sortable: false,
                        textAlign: 'center',
                        template: function (row) {
                            return row.company_asset.company_name
                        }
                    },
                    {
                        field: 'bank_nummber',
                        title: '{{ __('xin_e_details_acc_number') }}',
                        width: 150,
                        sortable: false,
                        textAlign: 'left',
                        template: function (row) {
                            if (row.bank_account != null){
                                var bank_name = row.bank_account.bank_name != null ? row.bank_account.bank_name : '';
                                var account_title = row.bank_account.account_title != null ? row.bank_account.account_title : '';
                                var account_number = row.bank_account.account_number != null ? row.bank_account.account_number : '';
                            }else{
                                var bank_name = '';
                                var account_title = '';
                                var account_number = '';
                            }
                            let bankHtml  = '<p class="m-0">'+bank_name +'</p>';
                                 bankHtml += '<p class="m-0">'+account_title+'</p>';
                                 bankHtml += '<p class="m-0">'+account_number+'</p>';
                            return  bankHtml;
                        }
                    },
                    {
                        field: 'advan_money',
                        title: '{{ __('advan_money') }}',
                        width: 150,
                        sortable: false,
                        textAlign: 'center',
                        template: function (row) {
                            return  window._userCurrency(parseInt(row.advance_amount));
                        }
                    },
                    {
                        field: 'stats',
                        title: '{{ __('kpi_status') }}',
                        width: 150,
                        sortable: false,
                        textAlign: 'center',
                        template: function (row) {
                            let status = {
                                0: {'class': ' label-light-warning'},
                                1: {'class': ' label-light-primary'},
                                2: {'class': ' label-light-success'},
                                3: {'class': ' label-light-danger'},

                            };
                            let status_type = {
                                0: {'lable': 'Chưa thanh toán'},
                                1: {'lable':'Đã duyệt'},
                                2: {'lable':'Đã thanh toán'},
                                3: {'lable':'Không duyệt'},
                            };
                            return '<span class="label label-lg font-weight-bold ' + status[row.status].class + ' label-inline">' + status_type[row.status].lable + '</span>';
                        }
                    },
                        @if (Auth::user()->isAdmin())
                    {
                        field: 'hành động',
                        title: '{{ __('Thao tác') }}',
                        sortable: false,
                        autoHide: false,
                        textAlign: 'center',
                        width: 100,
                        template: function (row) {
                            let colors_class = row.status > 0 ? '' : 'svg-icon-primary';
                            let colors_class_danger = row.status == 0 ? 'svg-icon-danger' : '';
                            let iconsCheck  =  '<span class="svg-icon '+colors_class+'  svg-icon-2x"><!--begin::Svg Icon | path:C:\\wamp64\\www\\keenthemes\\themes\\metronic\\theme\\html\\demo1\\dist/../src/media/svg/icons\\Navigation\\Check.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\n' +
                                '    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\n' +
                                '        <polygon points="0 0 24 0 24 24 0 24"/>\n' +
                                '        <path d="M6.26193932,17.6476484 C5.90425297,18.0684559 5.27315905,18.1196257 4.85235158,17.7619393 C4.43154411,17.404253 4.38037434,16.773159 4.73806068,16.3523516 L13.2380607,6.35235158 C13.6013618,5.92493855 14.2451015,5.87991302 14.6643638,6.25259068 L19.1643638,10.2525907 C19.5771466,10.6195087 19.6143273,11.2515811 19.2474093,11.6643638 C18.8804913,12.0771466 18.2484189,12.1143273 17.8356362,11.7474093 L14.0997854,8.42665306 L6.26193932,17.6476484 Z" fill="#000000" fill-rule="nonzero" transform="translate(11.999995, 12.000002) rotate(-180.000000) translate(-11.999995, -12.000002) "/>\n' +
                                '    </g>\n' +
                                '</svg><!--end::Svg Icon--></span>';
                            // let iconsEye = '<span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/General/Other2.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">' +
                            //     '<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <rect x="0" y="0" width="24" height="24"/> <circle fill="#000000" cx="5" cy="12" r="2"/> <circle fill="#000000" cx="12" cy="12" r="2"/> <circle fill="#000000" cx="19" cy="12" r="2"/></g>' +
                            //     '</svg><!--end::Svg Icon--></span>';
                            let iconsClose  = '<span class="svg-icon '+colors_class_danger+' svg-icon-2x"><!--begin::Svg Icon | path:C:\\wamp64\\www\\keenthemes\\themes\\metronic\\theme\\html\\demo1\\dist/../src/media/svg/icons\\Navigation\\Close.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\n' +
                                '    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\n' +
                                '        <g transform="translate(12.000000, 12.000000) rotate(-45.000000) translate(-12.000000, -12.000000) translate(4.000000, 4.000000)" fill="#000000">\n' +
                                '            <rect x="0" y="7" width="16" height="2" rx="1"/>\n' +
                                '            <rect opacity="0.3" transform="translate(8.000000, 8.000000) rotate(-270.000000) translate(-8.000000, -8.000000) " x="0" y="7" width="16" height="2" rx="1"/>\n' +
                                '        </g>\n' +
                                '    </g>\n' +
                                '</svg><!--end::Svg Icon--></span>';
                            let iconsWallet = '<span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\\wamp64\\www\\keenthemes\\themes\\metronic\\theme\\html\\demo1\\dist/../src/media/svg/icons\\Shopping\\Wallet.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\n' +
                                '    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\n' +
                                '        <rect x="0" y="0" width="24" height="24"/>\n' +
                                '        <circle fill="#000000" opacity="0.3" cx="20.5" cy="12.5" r="1.5"/>\n' +
                                '        <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 6.500000) rotate(-15.000000) translate(-12.000000, -6.500000) " x="3" y="3" width="18" height="7" rx="1"/>\n' +
                                '        <path d="M22,9.33681558 C21.5453723,9.12084552 21.0367986,9 20.5,9 C18.5670034,9 17,10.5670034 17,12.5 C17,14.4329966 18.5670034,16 20.5,16 C21.0367986,16 21.5453723,15.8791545 22,15.6631844 L22,18 C22,19.1045695 21.1045695,20 20,20 L4,20 C2.8954305,20 2,19.1045695 2,18 L2,6 C2,4.8954305 2.8954305,4 4,4 L20,4 C21.1045695,4 22,4.8954305 22,6 L22,9.33681558 Z" fill="#000000"/>\n' +
                                '    </g>\n' +
                                '</svg><!--end::Svg Icon--></span>';
                            let html = "";
                            let disable = row.status > 0  ? 'disable-links' : '';
                            html += "<a href='javascript:;' onclick='updateCompensations("+row.advance_salary_id+","+1+")' class='"+disable+" btn btn-sm btn-clean btn-icon' title='{{ __('Duyệt') }}'>"+iconsCheck+"</a>";
                            html += "<a href='javascript:;' onclick='updateCompensations("+row.advance_salary_id+","+3+")' class='"+disable+" btn btn-sm btn-clean btn-icon' title='{{ __('Không duyệt') }}'>"+iconsClose+"</a>";
                            // html += "<a href='advance_pdf/?id="+row.advance_salary_id+"' class='btn btn-sm btn-clean btn-icon' title='{{ __('xin_view')}} {{ __('xin_pdf')}}'>"+iconsEye+"</a>";
                            {{--html += "<a href='#' onclick='updateCompensations("+row.advance_salary_id+","+2+")' class='btn btn-sm btn-clean btn-icon' title='{{ __('Thanh toán') }}'>"+iconsWallet+"</a>";--}}
                        return html;
                        }
                    },
                    @endif

                ],
                'search_from': '#advanceForm'
            }
        };
        var updateCompensations = function (id, status) {
            if (id) {
                if (status == 1){
                    var titles =  __("are_you_sure_approve_this");
                }else if(status == 3){
                    var titles =  __("Không duyệt đơn này");
                }
                let title_ = status == 2 ? __("Thanh toán đơn này!") :  __("are_you_sure_approve_this");
                Swal.fire({
                    title: title_ ,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: __("yes"),
                    cancelButtonText: __("no"),
                    reverseButtons: true,
                    showLoaderOnConfirm: true,
                    preConfirm: function (data) {
                        Swal.showLoading();
                        $.ajax({
                            method: 'get',
                            url: '{{ route("payrolls.advancceMoneyRequest") }}',
                            data: {id: id, status:status}
                        }).done(function (response) {
                            console.log(response.data);
                            if (response.success) {
                                Swal.fire({
                                    title: __('xin_browse') + ' ' +__('xin_theme_success'),
                                    confirmButtonText: __('xin_view') + ' ' + __('xin_pdf'),
                                    preConfirm: function(data){
                                       window.location = "{{route('payrolls.pdfAdvanceSalary')}}/?id=" + response.data;
                                    }
                                });
                            } else {
                                Swal.fire(
                                    __("error_title"),
                                    response.error ?? __("error"),
                                    "error",
                                    false,
                                    false
                                );
                            }

                            window._tables.advance_list.datatable.reload();
                            // setTimeout(function () {window._tables.advance_list.datatable.reload();}, 400);
                        });
                        return false;
                    },
                    allowOutsideClick: function () {
                        return !Swal.isLoading()
                    }
                });
            }

        }
        var updateMulti = function (status) {
            let value_checked = [];

            $(".checkvalueAdvance > input:checked").each(function (e) {
                if ($(this).val() != 'on'){
                    value_checked.push($(this).val())
                }
            })
            if (value_checked == ''){
                Swal.fire({
                    icon: 'warning',
                    title: 'Bạn chưa chọn đơn duyệt!',
                })
            }else{
                let title_ = status == 1 ?  __("Bạn có chắc duyệt nhiều đơn!") :  __("Không duyệt nhiều đơn!");
                let title__ = status == 2 ? __("Thanh toán nhiều đơn!") :  title_;
                Swal.fire({
                    title: title__,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: __("yes"),
                    cancelButtonText: __("no"),
                    reverseButtons: true,
                    showLoaderOnConfirm: true,
                    preConfirm: function (data) {
                        Swal.showLoading();
                        $.ajax({
                            method: 'get',
                            url: '{{ route("payrolls.advancceMoneyRequest") }}',
                            data: {multi_id: value_checked, status:status}
                        }).done(function (response) {
                            console.log(response.data);
                            if (response.success) {
                                Swal.fire({
                                    title: __('xin_browse') + ' ' +__('xin_theme_success'),
                                    confirmButtonText: __('xin_view') + ' ' + __('xin_pdf'),
                                    preConfirm: function(data){
                                       window.location = "{{route('payrolls.pdfAdvanceSalary')}}/?id=" + response.data;
                                    }
                                });
                            } else {
                                Swal.fire(
                                    __("error_title"),
                                    response.error ?? __("error"),
                                    "error",
                                    false,
                                    false
                                );
                            }
                            window._tables.advance_list.datatable.reload();
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
        var viewMulti = function (status) {
            let value_checked = [];

            $(".checkvalueAdvance > input:checked").each(function (e) {
                if ($(this).val() != 'on'){
                    value_checked.push($(this).val())
                }
            })
            if (value_checked == ''){
                Swal.fire({
                    icon: 'warning',
                    title: 'Bạn chưa chọn đơn!',
                })
            }else{
                let title_ = status == 1 ?  __("Bạn có chắc muốn xem nhiều đơn!") :  __("Không xem nhiều đơn!");
                let title__ = status == 2 ? __("Thanh toán nhiều đơn!") :  title_;
                Swal.fire({
                    title: title__,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: __("yes"),
                    cancelButtonText: __("no"),
                    reverseButtons: true,
                    showLoaderOnConfirm: true,
                    preConfirm: function (data) {
                        Swal.showLoading();
                        window.location = "{{route('payrolls.pdfAdvanceSalary')}}/?id=" + value_checked;
                    },
                    allowOutsideClick: function () {
                        return !Swal.isLoading()
                    }
                });
            }
        }
        //Modal
        $('#update_advance_request').on('shown.bs.modal', function (e) {
            window._loading("#update_advance_request .modal-body");
            let button = $(e.relatedTarget);
            let id = button.data('id');
            let url = $('#update_advance_request').data('form-url');
            $.ajax({
                url: url,
                data: {
                    id: id
                }
            })
                .done(function (response) {
                    window._loading("#update_advance_request .modal-body", false);
                    $("#update_advance_request .modal-body").html(response);
                })
                .fail(function (jqXHR, status){
                    window._loading("#update_advance_request .modal-body", false);
                    toastr.error(__("order_form_fetch_error"));
                    window._display_alert("#update_advance_request .modal-body", __("order_form_fetch_error"));
                });
        });
        // keyup
        $("#kt__search_query").keyup(function() {
            let string = $(this).val();
            window._tables.advance_list.datatable.reload();
        });
        $(document).ready(function() {
            // $('#advance_list').on('datatable-on-check', function (event, args) {
            //     console.log(event, args, this);
            //     return false;
            // });
            // console.log($(".checkvalueAdvance").find('input'));
            // window._tables.advance_list.datatable && window._tables.advance_list.datatable.on('kt-datatable--on-check', function (event, args) {
            //     console.log(event, args);
            // });
                // datatable-on-check
        });
    </script>

    <script src="{{ mix('js/payroll/payroll.js') }}" type="text/javascript"></script>
    <script src="{{ mix('js/list.js') }}" type="text/javascript"></script>

@endsection
