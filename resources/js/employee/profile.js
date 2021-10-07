$(document).ready(function() {
    //update info user
    $('#saveInfo').click(function (e) {
        //
        // let profile_avatar = $('input[name="profile_avatar"]').val();
        // let contact_no = $('input[name ="contact_no"]').val();
        // let date_of_birth = $('input[name ="date_of_birth"]').val();
        // let gender = $("input[name='gender']:checked").val();
        // let marital_status = $('input[name ="marital_status"]:checked').val();
        // let address = $('textarea[name ="address"]').val();

        var form = $('#formUpdateProfile')[0];
        var data = new FormData(form);

        let url = 'profile/update';

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: url,
            data: data,
            processData: false,  // Important!
            contentType: false,
            cache: false,
            enctype: 'multipart/form-data',

            success: function (data) {
                if (data.success) {
                    toastr.success(data.success);
                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                } else {
                    toastr.error(data.error);
                }
            }
        })
    })
    KTProfile.init();
    //-----------------admin--------------------
$("#end_trail_work").datepicker({
    todayHighlight: true,
    format: window._dateFormat.toLowerCase(),
    default: 'toDay',
    orientation: "bottom left",
    language: window._locale,
    autoClose: true
});
$("input[name=ways_type]").on('change', function (e) {
    e.preventDefault();
    console.log($(this).val());
    if ($(this).val() == 2){
        $(".is_select_end_trail").removeClass('d-none').addClass('d-block');
    }else{
        $(".is_select_end_trail").removeClass('d-block').addClass('d-none');
    }
});

    FormValidation.formValidation(
        document.getElementById('updateSalary'),
        {
            fields: {
                ways_type: {
                    validators: {
                        notEmpty: {
                            message: __('field_is_required_meetings')
                        },
                    }
                },
                salary: {
                    validators: {
                        notEmpty: {
                            message: __('field_is_required_meetings')
                        },
                    }
                },
                basic_salary: {
                    validators: {
                        notEmpty: {
                            message: __('field_is_required_meetings')
                        },
                    }
                },
                end_trail_work: {
                    validators: {
                        callback: {
                            message: 'Wrong answer',
                            callback: function(input) {
                                let ways =  $("input[name=ways_type]:checked").val();
                                if (ways == 2 && input.value == ''){
                                    return {
                                        valid: false,
                                        message: __('field_is_required_meetings'),
                                    };
                                }else{
                                    return true;
                                }

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

        $(document).ready(function () {
            $(".add_meetting").text(__('saving')).attr('disabled','disabled');
        });
        $.ajax({
            type: "POST",
            url: '/employee_managements/staff_update',
            data: $('#updateSalary').serialize(),
            cache: false,
            success: function (result_data) {
                if (result_data.success == true){
                    toastr.success(result_data.data);
                    location.reload();
                }
            }
        });
    });

});

var KTProfile = function () {
    // Elements
    var avatar;
    var offcanvas;

    // Private functions
    var _initAside = function () {
        // Mobile offcanvas for mobile mode
        offcanvas = new KTOffcanvas('kt_profile_aside', {
            overlay: true,
            baseClass: 'offcanvas-mobile',
            //closeBy: 'kt_user_profile_aside_close',
            toggleBy: 'kt_subheader_mobile_toggle'
        });
    }

    var _initForm = function() {
        avatar = new KTImageInput('kt_profile_avatar');
    }

    return {
        // public functions
        init: function() {
            _initAside();
            _initForm();
        }
    };
}();
