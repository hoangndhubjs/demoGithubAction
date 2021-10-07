{{--
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 4 & Angular 8
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
Renew Support: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
 --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" {{ Metronic::printAttrs('html') }} {{ Metronic::printClasses('html') }}>
    <head>
        <meta charset="utf-8"/>

        {{-- Title Section --}}
        <title><x-company-name/> | @yield('title', $page_title ?? '')</title>

        {{-- Meta Data --}}
        <meta name="description" content="@yield('page_description', $page_description ?? '')"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no" />


        {{-- Favicon --}}
        <x-favicon />
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Fonts --}}
        {{ Metronic::getGoogleFontsInclude() }}

        {{-- Global Theme Styles (used by all pages) --}}
        @foreach(config('layout.resources.css') as $style)
            <link href="{{ config('layout.self.rtl') ? mix(Metronic::rtlCssPath($style)) : mix($style) }}" rel="stylesheet" type="text/css"/>
        @endforeach

        {{-- Layout Themes (used by all pages) --}}
        @foreach (Metronic::initThemes() as $theme)
            <link href="{{ config('layout.self.rtl') ? mix(Metronic::rtlCssPath($theme)) : mix($theme) }}" rel="stylesheet" type="text/css"/>
        @endforeach

        {{-- Includable CSS --}}
        @yield('styles')
        @stack('stackCSS')
        {{-- MATOMO --}}
        <!-- Matomo -->
{{--        <script type="text/javascript">--}}
{{--            var _paq = window._paq = window._paq || [];--}}
{{--            /* tracker methods like "setCustomDimension" should be called before "trackPageView" */--}}
{{--            _paq.push(['trackPageView']);--}}
{{--            _paq.push(['enableLinkTracking']);--}}
{{--            (function() {--}}
{{--                var u="//matomo.thaolx.com/";--}}
{{--                _paq.push(['setTrackerUrl', u+'matomo.php']);--}}
{{--                _paq.push(['setSiteId', '1']);--}}
{{--                var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];--}}
{{--                g.type='text/javascript'; g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);--}}
{{--            })();--}}
{{--        </script>--}}
        <!-- End Matomo Code -->
    </head>

    <body {{ Metronic::printAttrs('body') }} {{ Metronic::printClasses('body') }}>

        @if (config('layout.page-loader.type') != '')
            @include('layout.partials._page-loader')
        @endif

        @include('layout.base._layout')

        <script>var HOST_URL = "{{ route('quick-search') }}";</script>

        {{-- Global Config (global config for global JS scripts) --}}
        <script>
            var KTAppSettings = {!! json_encode(config('layout.js'), JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) !!};
            window._translations = {!! json_encode(app('hrm')->getTranslation(), JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) !!};
            window._currencies = {!! json_encode(app('hrm')->getCurrencies(), JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) !!};
            window._dateFormat = "{!! app('hrm')->getJSDateFormat() !!}";
            window._settings = {!! json_encode(app('hrm')->getOptions(), JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) !!};
            window._locale = '{{ app()->getLocale() }}';
        </script>

        {{-- Global Theme JS Bundle (used by all pages)  --}}
        @foreach(config('layout.resources.js') as $script)
            <script src="{{ mix($script) }}" type="text/javascript"></script>
        @endforeach

        {{-- Includable JS --}}
        @yield('scripts')
        @stack('stackJS')
        <script src="{{ mix('js/app.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.selectSearch').select2({ width: '100%' });

            $('.datepickerDefautl').datepicker({
                todayHighlight: true,
                format: window._dateFormat.toLowerCase(),
                default: 'toDay',
                setDate: new Date(),
                orientation: "bottom left",
                language: window._locale
            });

            // input number
            $(".numberRq").on("keypress keyup blur", function(event) {
                $(this).val($(this).val().replace(/[^\d].+/, ""));
                if ((event.which < 48 || event.which > 57)) {
                    event.preventDefault();
                }
            });

            $('#meeting_notification').on('shown.bs.modal', function (e) {
                let button = $(e.relatedTarget);
                let id = button.data('id');
                if(id == undefined){
                    id = $('#id-meeting').val();
                }
                let element_image = '';
                let count = 0;
                $.ajax({
                    url: '/meeting/detail/'+id,
                    type:'GET',
                    data : {meeting_id : id},
                    success:function (meeting) {
                        $('#moment_lh_notifi').html(meeting[1]);
                        $("#metting_title_notifi").html(window._htmlEscapeEntities(meeting[0]['meeting_title']));
                        $("#metting_company_notifi").html(meeting[0]['meeting_date']);
                        $("#metting_note_notifi").html(window._htmlEscapeEntities(meeting[0]['meeting_note']));
                        $("#metting_start_time").html(meeting[0]['meeting_time']);
                        $("#metting_end_time").html(meeting[0]['meeting_end']);
                        let countMember = meeting['user_info'].length;
                        for ( count=0 ; count <= countMember; count++ ){
                            let info = meeting['user_info'][count];
                            let memberMore = countMember-5;
                            let hidden_class = count+1 > 5 ? 'hiddenMemeber':'';
                            $(".more_member:last").addClass('12');
                            if(meeting['user_info'][count] != undefined ){
                                element_image += '<div class="symbol symbol-30 symbol-circle member_meetingday '+hidden_class+' " data-toggle="tooltip" title="'+info['user_name']+'" data-original-title="'+info['user_name']+'"><img alt="'+info['user_name']+'" src="'+info['image_user']+'"></div>';
                                if(count+1 == countMember && countMember > 5){
                                    element_image += '<div class="symbol symbol-30 symbol-circle symbol-light more_member" data-toggle="tooltip" title="Xem thêm thành viên" data-original-title="Xem thêm thành viên"><span class="symbol-label font-weight-bold">'+ memberMore +'+</span></div>';
                                }
                            }
                        }
                        $("#member_meeting").html(element_image);
                    }
                });
            });
        });
        $(document).on('click',  ".more_member",function () {
            $(this).remove();
            $(".member_meetingday").removeClass('hiddenMemeber');
        });
        $(document).on("click", ".checkin_checkout button", function (e) {
            $(this).attr('disable', 'disable');
            let dataType  = $(this).attr("data-type");
            let urlChekin_out = '/attendanceClasses';
            e.preventDefault();
            var clock_state = '';
            // var obj = $(this), action = obj.attr('name');
            if(navigator.geolocation){
                navigator.geolocation.getCurrentPosition(function(position) {
                    // Get the coordinates of the current possition.  position.coords.longitude
                    var lat = position.coords.latitude;
                    var lng = position.coords.longitude;
                    console.log(lat, lng);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: "GET",
                        url: '/attendanceClasses',
                        data: "is_ajax=1&type=set_clocking&dataType="+dataType+"&latitude="+lat+"&longitude="+lng+"",
                        cache: false,
                    }).done(function (result) {
                        if(result.success === true){
                            toastr.success(result.data);
                            setTimeout(function (){
                                $(".checkin_checkout button").removeAttr('disable');
                            }, 5000)
                        }else{
                            toastr.error(__('error_title'));
                        }
                    }).fail(function (jqXHR, status){
                        toastr.error(__('error_title'));
                        $(".checkin_checkout button").removeAttr('disable');
                    });
                }, showError);
            } else {
                x.innerHTML = "Vị trí địa lý không được trình duyệt này hỗ trợ.";
            }

        });
        function showError(error) {
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    alert("Yêu cầu truy cập vị trí bị từ chối");
                   break;
                case error.POSITION_UNAVAILABLE:
                    alert("Thông tin không có s.");
                    break;
                case error.TIMEOUT:
                    alert("Yêu cầu nhận vị trí của bạn đã hết thời gian chờ.");
                    break;
                case error.UNKNOWN_ERROR:
                    alert("Đã xảy ra lỗi không xác định.");
                    break;
            }
        }
    </script>
    </body>
</html>

