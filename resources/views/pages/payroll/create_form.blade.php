@if($type == 'create')
<form method="POST" id="form_advance" enctype="multipart/form-data">
    @csrf
    <div class="form-group row">
        <div class="col-lg-6 pt-5">
            <label class="d-block required">{{ __("advan_money") }} <span class="text-danger"> *</span></label>
            <input autocomplete="off"  class="form-control format_number"
                   placeholder="{{__('advan_money')}}"
                   name="advance_money" type="text" value="">
        </div>
        <div class="col-lg-6 pt-5">
            <label class="d-block required">{{ __("xin_select_month") }} <span class="text-danger"> *</span></label>
            <input autocomplete="off" class="form-control month_year_advance"
                   placeholder="{{__('xin_select_month')}}"
                   name="month_year" type="text" value="{{ date('m-Y') }}">
        </div>
        <div class="col-xxl-12 col-lg-12 pt-5">
            <div class="form-group">
                <label>{{ __('xin_reason') }} <span class="text-danger"> *</span></label>
                <input class="form-control" placeholder="{{__('xin_reason')}}" name="reason" type="text" value="">
            </div>
        </div>
        <div><input type="hidden" id="salary_user"></div>
    </div>

    <div class="form-group text-center mb-0">
        <button  type="submit" class="btn btn-primary mr-3 save_">{{ __("xin_save")  }}</button>
        <button data-dismiss="modal" class="btn btn-light-primary ml-3">{{ __("cancel")  }}</button>
    </div>
</form>
    <script>
        $(document).ready(function() {
            $(".month_year_advance").datepicker({
                format: "mm-yyyy",
                startView: "months",
                minViewMode: "months",
                autoclose:true,
                todayHighlight: true,
                default: 'toDay',
                setDate: new Date(),
                orientation: "bottom left",
                language: window._locale,
                endDate: "toDay",
            });
            $(function(e){
                $('.month_year_advance').trigger('change');
                window.formart_number('.format_number');
            })

            //show bankAcoount
            $(".month_year_advance").change(function (e) {
                let employee_id = $('#select2_empolyee__').val();
                let month_year_advance = $('.month_year_advance').val();
                $.ajax({
                    type: "GET",
                    url: '{{ route('payrolls.getBankAccount') }}',
                    data: {employee_id:employee_id, month_year : month_year_advance},
                    cache: false,
                    success: function (result_data) {
                        if (result_data){
                            formValidator.revalidateField('advance_money');
                            let price = result_data.salary_advance > 0 ? window._userCurrency(result_data.salary_advance) : window._userCurrency(0);
                            $("#salary_advance").html(price.split("₫").join(" đồng"));
                            let data_advance = result_data.salary_advance > 0 ? result_data.salary_advance.toString().replace(/\./gi, "").split(/(?=(?:\d{3})+$)/).join(".") : 0;
                            $("input[name=advance_money]").val(data_advance);
                            $("#salary_user").val(result_data.salary_advance > 0 ? result_data.salary_advance : 0);
                            // console.log(result_data.salary_advanced_amounted.length);
                            if (result_data.salary_advanced_amounted.length > 0){
                                var html = '<ul class="list_advanced">';
                                var count = 1;
                                result_data.salary_advanced_amounted.forEach(function (data) {
                                    let price_advanced = window._userCurrency(parseInt(data));
                                    html += '<li><span>'+ count++ +'.</span> Bạn đã ứng: '+price_advanced+'</li>';
                                });
                                html += '</ul>';
                                $("#advanced_user").html(html);
                            }else{
                                $("#advanced_user").html("");
                            }

                        }
                    }
                });

            });
        //    form-validation
            function check(value){
                let data_price= $("#salary_user").val();
                let price_advance = data_price.split(".").join("");
                value = parseInt(value);
                data_price = parseInt(data_price);
                if (value <= 0 && data_price === 0) {
                    return {
                        valid: false,
                        message: 'Khoản tạm ứng phải lớn hơn 0đ.'
                    };
                }else if (isNaN(value) || isNaN(data_price)) {
                    return {
                        valid: false,
                        message: 'Chưa có thông tin tạm ứng.'
                    };
                } else if (value > data_price) {
                    return {
                        valid: false,
                        message: 'Bạn có thể tạm ứng tối đa ' + window._userCurrency(data_price)
                    };
                }
                return true;

            }

            formValidator =  FormValidation.formValidation(
                document.getElementById('form_advance'),
                {
                    fields: {
                        advance_money: {
                            validators: {
                                callback: {
                                    message: __('field_is_required_meetings'),
                                    callback: function(input) {
                                        return check(input.value.split(".").join(""));
                                    }
                                }

                            }
                        },
                    },

                    plugins: { //Learn more: https://formvalidation.io/guide/plugins
                        trigger: new FormValidation.plugins.Trigger(),
                        // Bootstrap Framework Integration
                        bootstrap: new FormValidation.plugins.Bootstrap(),
                        // Validate fields when clicking the Submit button
                        submitButton: new FormValidation.plugins.SubmitButton()
                        // Submit the form when all fields are valid
                        // defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                    }
                }
            ).on('core.form.valid', function () {
                let data = new FormData($('#form_advance')[0]);
                $(document).ready(function () {
                    $(".save_").text(__('saving')).attr('disabled','disabled');
                });
                $.ajax({
                    type: "POST",
                    url: "{{ route('payrolls.createRequest') }}",
                    data: data,
                    mimeType: 'multipart/form-data',
                    processData: false,
                    contentType: false,
                    cache: false,
                }).done(function(result_data) {
                    console.log(result_data.success);
                    if (result_data.success === false){
                        toastr.error(result_data.errors);
                    }else{
                        Swal.fire(
                            'Đơn tạm ứng gửi thành công',
                            'Sau khi đơn được duyệt bạn vui lòng lên gặp Hành chính nhân sự ký xác nhận nhé!',
                            'success'
                        )
                        setTimeout(function () {
                            window._tables.advance_list.datatable.reload();
                            $("#update_advance_request").modal('hide');
                            $("#form_advance").trigger("reset");
                        }, 1000);
                    }

                }).fail(function (jqXHR, status){
                    // console.log(jqXHR);
                    result_data = JSON.parse(jqXHR.responseText);
                    $(".save_").text(__('xin_save')).removeAttr('disabled');
                    toastr.error(result_data.errors ?? __("error"));
                    });
            });
        });
    </script>
@else
    <p>Nothing...</p>
@endif
