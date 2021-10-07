var KTSelect2 = function() {
    // Private functions
    var date=new Date();
    var year=date.getFullYear();
    var month=date.getMonth();

    var todayDate = moment(window._start_date).startOf('day');
    var TODAY = todayDate.format('DD-MM-YYYY');

    var asset_init = function() {
        // basic
        $('.kt-select2-companies').select2({
            placeholder: "..."
        });
     $(".date-add-asset, .warranty_period, .warranty_date").datepicker({
         autoclose: true,
         format: 'dd-mm-yyyy',
         todayHighlight: true,
     });
    };
    //load ajax employee company
    $("#select_compnay_").change(function(e) {
        e.preventDefault();
        const self = $(this);
        let company_id = self.val();
        let employee_id =  self.attr('data-employee');
        var html = '';
        $.ajax({
            type: "GET",
            url: '/calendars/get_employee_company',
            data: {company_id:company_id},
            cache: false,
            success: function (result_data) {
                if(result_data){
                    // html +='<option class="text-muted" value="0" disabled selected>'+__('xin_complaint_employees')+'</option>';
                    $.each(result_data, function (key,item){
                        let selected = employee_id == item['user_id'] ? 'selected' : '';
                        console.log(selected);
                        html += '<option '+selected+' url="'+item['profile_picture']+'" value="'+item['user_id']+'">';
                        html += item['first_name'] + ' ' + item['last_name'];
                        html += '</option>';
                    });
                    // console.log(html);
                    $("#employee_select2_ajax").html(html);
                }

            }
        });
    });
    $("#select_compnay_").trigger('change');
    // Public functions
    return {
        init: function() {
            asset_init();
        }
    };
}();
//validation
var KTFormControls = function () {
    var _asset = function () {
        FormValidation.formValidation(
            document.getElementById('asset_form'),
            {
                fields: {
                    assets_category_id: {
                        validators: {
                            notEmpty: {
                                message: __('field_is_required_meetings')
                            },
                        }
                    },
                    company_asset_code: {
                        validators: {
                            notEmpty: {
                                message: __('field_is_required_meetings')
                            },
                        }
                    },
                    name: {
                        validators: {
                            notEmpty: {
                                message: __('field_is_required_meetings')
                            },
                        }
                    },
                    is_working: {
                        validators: {
                            callback:{
                                callback: function (input) {
                                    if (input.value === ""){
                                        $(function (){$("div[data-field='is_working']").remove();});
                                        $(".is_checked_status").html(__('field_is_required_meetings'));
                                        return {
                                            valid : false,
                                            message: '',
                                        };
                                    }else{
                                        return {
                                            valid : true
                                        };
                                    }

                                }
                            }
                        }
                    },
                    // price: {
                    //     validators: {
                    //         notEmpty: {
                    //             message: __('field_is_required_meetings')
                    //         },
                    //     }
                    // },
                    // invoice_number: {
                    //     validators: {
                    //         notEmpty: {
                    //             message: __('field_is_required_meetings')
                    //         },
                    //     }
                    // },
                    // company_id: {
                    //     validators: {
                    //         notEmpty: {
                    //             message: __('field_is_required_meetings')
                    //         },
                    //     }
                    // },
                    // employee_id: {
                    //     validators: {
                    //         notEmpty: {
                    //             message: __('field_is_required_meetings')
                    //         },
                    //     }
                    // },
                    // manufacturer: {
                    //     validators: {
                    //         notEmpty: {
                    //             message: __('field_is_required_meetings')
                    //         },
                    //     }
                    // },
                    // serial_number: {
                    //     validators: {
                    //         notEmpty: {
                    //             message: __('field_is_required_meetings')
                    //         },
                    //     }
                    // },
                    // warranty_end_date: {
                    //     validators: {
                    //         notEmpty: {
                    //             message: __('field_is_required_meetings')
                    //         },
                    //     }
                    // },
                    // age_life_asset: {
                    //     validators: {
                    //         notEmpty: {
                    //             message: __('field_is_required_meetings')
                    //         },
                    //     }
                    // },

                    // asset_note: {
                    //     validators: {
                    //         notEmpty: {
                    //             message: __('field_is_required_meetings')
                    //         },
                    //     }
                    // },

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
            $(function () {
                $(".add_asset").text(__('saving')).attr('disabled','disabled');
            });
            var formData = new FormData($("#asset_form")[0]);
            $.ajax({
                type: "POST",
                url: 'addOrEditAsset',
                data: formData,
                cache: false,
                mimeType: 'multipart/form-data',
                processData: false,
                contentType: false,
            }).done(function (result_asset) {
                    toastr.success(__('xin_theme_success'));
                    setTimeout(function () {
                        window._tables.list_asset.datatable.reload();
                    }, 2000);
                    $("#createAsset").modal('hide');
            }).fail(function (jqXHR, status){
                    $(".add_asset").text(__('xin_save')).removeAttr('disabled');
                    result_data = JSON.parse(jqXHR.responseText);
                    $(".save_").text(__('xin_save')).removeAttr('disabled');
                    toastr.error(result_data.errors ?? __("error"));
                });
        });
    };
    return {
        // public functions
        init_asset: function() {
            _asset();
        },

    };
}();

// Initialization
$(document).ready(function() {
    KTSelect2.init();
    KTFormControls.init_asset();
    // History.init_history();
});
