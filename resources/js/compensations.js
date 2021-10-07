"use strict";
    var initCompenations = function (){
        var dateTimeSetup = function () {
            $('.form-container').on('change','.js-make-change', function () {
                let el = $(this).closest('.element');
                let input = el.find('.datepciker_compensation');
                input.attr('data-default-value', $(this).val());
                let value = input.datepicker('getDate');
                input.datepicker('setDate', value);
            });
            $(".value_compensations").each(function (elment, item) {
                let data_value = $(item).find("input[type='radio']:checked")[0].defaultValue;
                $(this).parents().find(".datepciker_compensation").attr("data-default-value", data_value);
            });
            $(".datepciker_compensation").datepicker({
                // todayHighlight: true,
                orientation: "bottom left",
                changeYear: true,
                format: "dd-mm-yyyy",
                autoclose: true,
                setDate: new Date(),
                endDate: "toDay",
                language: window._locale
            }).on('changeDate', function (e) {
                let date = e.date;
                if (date){
                    let compensations_date = moment(date.toISOString()).format("YYYY-MM-DD");
                    let status_radio = $(this).attr('data-default-value');
                    $.ajax({
                        url: '/compensations/compensations_check',
                        type: 'GET',
                        data: {
                            date : compensations_date,
                            status : status_radio
                        },
                    }).done(function (data) {
                        let target = e.target;
                        let parent = e.target.offsetParent;
                        let title_error = '';
                        if(data[0] && data[0].status === false){
                            title_error = data[0].message;
                        }else if(data.status === 2){
                            title_error = 'Ngày bạn chọn đã được thanh toán lương';
                        }
                        $(parent).find(".duplicateTime").remove();
                        if($.isEmptyObject(data) == false){
                            if ((data[0].status === false) || (data.status === 2)){
                                $(parent).append('<div class="duplicateTime text-danger">'+title_error+'</div>');
                                $(target).addClass('is-invalid');
                                $(target).removeClass('is-valid');
                                // $(".add_compensations").attr("disabled","disabled");
                            }
                        }else{
                            $(target).addClass('is-valid');
                            $(target).removeClass('is-invalid');
                            $(parent).find(".duplicateTime").remove();
                        }
                    });
                }
            });
        };
        var fromCompenations = function () {
            $("#compensations-add").click(function () {
                let count_element = $(".element").length + 1;
                let compensations_html = '';
                if(count_element >= 2 && count_element <= 3) {
                    compensations_html += '<div class="element">\n' +
                '                   <div class="form-group row">\n' +
                        '                <div class="col-lg-3 element_mobile">\n' +
                        '                    <label class="d-block required">Loại bù công</label>\n' +
                        '                    <select class="form-control select_compensation" id="type_of_work" name="type_of_work">\n' +
                        '                       <option value="off">Offline</option>\n' +
                        '                       <option value="on">Online</option>\n' +
                        '                    </select>\n' +
                        '                </div>\n' +
                        '                <div class="col-lg-3 element_mobile">\n' +
                        '                    <label class="d-block required">Ngày muốn bù công <span class="text-danger"> *</span></label>\n' +
                        '                    <input type="" autocomplete="off" name="compensation_date[]" readonly  class="datepciker_compensation form-control" placeholder="Chọn ngày muốn bù công">\n' +
                        '                </div>\n' +
                        '                <div class="col-lg-6 element_mobile">\n' +
                        '                    <label class="d-block required">Lý do bù công <span class="text-danger"> *</span></label>\n' +
                        '                    <textarea class="reason form-control" name="reason[]" id="" cols="30" rows="1"></textarea>\n' +
                        '                </div>\n' +
                        '            </div>\n' +
                        '            <div class="form-group row">\n' +
                        '                <div class="col-lg-12 value_compensations">\n' +
                        '                    <label class="d-block required">Loại bù công <span class="text-danger"> *</span></label>\n' +
                        '                    <div class="radio-inline mt-4">\n' +
                        '                        <label class="radio">\n' +
                        '                            <input type="radio" value="1" checked="checked" class="js-make-change"  name="radios'+count_element+'[]"/>\n' +
                        '                            <span></span>\n' +
                        '                            Đủ công\n' +
                        '                        </label>\n' +
                        '                        <label class="radio">\n' +
                        '                            <input type="radio" value="2" class="js-make-change" name="radios'+count_element+'[]"/>\n' +
                        '                            <span></span>\n' +
                        '                            Nửa công sáng\n' +
                        '                        </label>\n' +
                        '                        <label class="radio">\n' +
                        '                            <input type="radio" value="3" class="js-make-change" name="radios'+count_element+'[]"/>\n' +
                        '                            <span></span>\n' +
                        '                            Nửa công chiều\n' +
                        '                        </label>\n' +
                        '                    </div>\n' +
                        '            <span class="btn font-weight-bold btn-icon close_compens"><span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2020-10-29-133027/theme/html/demo1/dist/../src/media/svg/icons/Home/Trash.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\n' +
                        '    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\n' +
                        '        <rect x="0" y="0" width="24" height="24"/>\n' +
                        '        <path d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z" fill="#000000" fill-rule="nonzero"/>\n' +
                        '        <path d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"/>\n' +
                        '    </g>\n' +
                        '</svg><!--end::Svg Icon--></span></span></div>\n' +
                        '                </div></div>';
                        '            <span class="btn font-weight-bold btn-icon close_compens">'+window.iconDelete+'</div>';
                    $(".compensations_form").append(compensations_html);
                    dateTimeSetup();
                }
                if(count_element > 3){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Bạn có thể tạo tối đa 3 mục',
                    });
                }
            });
            $(document).on('click', '.close_compens', function () {
                let radio = $('.value_compensations:last').find('input[type=radio]').attr('name');
                $('.value_compensations:last').find('input[type=radio]').attr('name','radios'+radio.slice(6,7) - 1+'[]');
                $(this).parents('.element').remove();
                let count_element = $(".element").length;
                if(count_element == 1) {
                  $(".delete_element").html('');
                }
            });
        };
        return {
            // public functions
            init: function() {
                dateTimeSetup();
                fromCompenations();
                // requestAjaxForm();
            }
        };
    }();
    //
    $(document).on("keyup", "textarea[name='reason[]']", function () {
        $(this).parent().find(".text_reson_compensation").remove();
        $(this).each(function () {
            if($(this).val() === ''){
                $(this).parent().append('<div class="text_reson_compensation text-danger">Bạn chưa nhập lý do</div>');
                $(this).addClass('is-invalid');
                $(this).removeClass('is-valid');
            }else{
                $(this).parent().find(".text_reson_compensation").remove();
                $(this).addClass('is-valid');
                $(this).removeClass('is-invalid');
            }
        });
    });
    //
    $(".add_compensations").click(function () {
        // var compensation_update_id = $("input[name=compensation_id]").val();
        var compensation_date = [];
        let reason = [];
        $("input[name='compensation_date[]']").each(function () {
            compensation_date.push($(this).val());
        });
        $(".text_reson_compensation").remove();
        $("#formCompensations").find("textarea[name='reason[]']").each(function () {
            if($(this).val() === ''){
                $(this).parent().append('<div class="text_reson_compensation text-danger">Bạn chưa nhập lý do</div>');
                $(this).addClass('is-invalid');
                $(this).removeClass('is-valid');
            }else{
                $(this).addClass('is-valid');
                $(this).removeClass('is-invalid');
            }
            reason.push($(this).val());
        });
        $(this).attr("disabled","disabled");
        if ($(".element").find("input[name='compensation_date[]']").hasClass("is-invalid") == true){
            $(".add_compensations").removeAttr("disabled");
            return false;
        }
        $(".duplicateTime").remove();
        $("input[name='compensation_date[]']").each(function () {
            let valueDate = $(this).val();
            if($(this).val() == ''){
                $(this).parent().append('<div class="duplicateTime text-danger">Chọn ngày bù công</div>');
                $(this).addClass('is-invalid');
                $(this).removeClass('is-valid');
                $(".add_compensations").removeAttr("disabled");
            }else if((compensation_date.filter(x => x==valueDate).length >= 2) == true){
                $(this).parent().append('<div class="duplicateTime text-danger">Ngày chọn bị trùng</div>');
                $(this).addClass('is-invalid');
                $(this).removeClass('is-valid');
                $(".add_compensations").removeAttr("disabled");
            }else{
                $(this).addClass('is-valid');
                $(this).removeClass('is-invalid');
                $(this).parent().find(".duplicateTime").remove();
                $(".add_compensations").removeAttr("disabled");
            }
        });

        if ($(".element").find("input[name='compensation_date[]']").hasClass("is-invalid") == false){
            // ajax
            var value_checked = [];
            $('.value_compensations input:radio').each(function () {
                var $this = $(this),
                    id = $this.val();
                if ($(this).prop('checked')) {
                    value_checked.push(id);
                }
            });
            $.ajax({
                type: "POST",
                url: '/compensations/add_compensations',
                data:{
                    value_checked : value_checked,
                    date_compensations : compensation_date,
                    reason : reason,
                    type_of_work : $('#type_of_work').val()
                    // id_update : compensation_update_id
                },
                cache: false,
            }).done(function (result_data) {
                console.log(result_data);
                if (result_data.duplicateTime || (result_data.no_create_full && result_data.no_create_full.length > 0)){
                    $(".duplicateTime").remove();
                    $("input[name='compensation_date[]']").each(function () {
                        // console.log(result_data.no_create_full.length);
                        let alert_type = result_data.no_create_full ? 'Không thể tạo đơn đủ công' : 'Thời gian trùng';
                        $(this).parent().append('<div class="duplicateTime text-danger">'+alert_type+'</div>');
                        if ((result_data.duplicateTime && result_data.duplicateTime.includes($(this).val()) == true) || (result_data.no_create_full && result_data.no_create_full.length > 0)){
                            $(this).addClass('is-invalid');
                            // console.log('111');
                        }else{
                            $(this).removeClass('is-invalid');
                            $(this).parent().find(".duplicateTime").remove();
                            // console.log('222');
                        }
                        $(".add_compensations").removeAttr("disabled");
                    });
                }else if(result_data.success == true){
                    toastr.success(result_data.data);
                    setTimeout(function () {
                        $('.reset_form').trigger('click');
                        window._tables.overtime_list.datatable.reload();
                    }, 2000);
                }
            });
            return false;
        }
    });
    //
    $(document).ready(function () {
       initCompenations.init();

        $('.select_compensation').selectpicker();
    });
