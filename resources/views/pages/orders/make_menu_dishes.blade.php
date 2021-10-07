<form id="form_add_menu_dishes">
    <div class="form-group row">
        <div class="col-lg-4">
            <label class="">{{ __("order_primary_food") }}</label><span class="text-danger"> *</span>
            <textarea class="form-control" placeholder="Mỗi món chính là một dòng" name="main_dishes" rows="5" id="main_dishes"></textarea>
        </div>
        <div class="col-lg-4">
            <label class="">{{ __("order_secondary_food") }}</label><span class="text-danger"> *</span>
            <textarea class="form-control" placeholder="Mỗi món phụ là một dòng" name="side_dishes" rows="5" id="side_dishes"></textarea>
        </div>
        <div class="col-lg-4">
            <label class="">{{ __("order_vegatable_food") }}</label><span class="text-danger"> *</span>
            <textarea class="form-control" placeholder="Mỗi món rau là một dòng" name="vegetable_dishes" rows="5" id="vegetable_dishes"></textarea>
        </div>
    </div>

    <div class="form-group text-right mb-0">
        <button type="submit" class="btn btn-primary" id="sm_make_menu_dishes"><x-icon type="svg" category="Design" icon="Flatten"/> Tạo danh sách</button>
    </div>

    <!-- JS Form -->
    <script>
        if(typeof formAddNewDishes === "undefined") {
            let formAddNewDishes = null;
        }
        if(typeof submitButton === "undefined") {
            let submitButton = null;
        }
        if(typeof formValidator === "undefined") {
            let formValidator = null;
        }
        formAddNewDishes = document.getElementById('form_add_menu_dishes');
        submitButton = formAddNewDishes.querySelector('[type="submit"]');

        formValidator = FormValidation.formValidation(
            formAddNewDishes,
            {
                fields: {
                    'main_dishes': {
                        validators: {
                            notEmpty: {
                                message: __("Món chính không được bỏ trống"),
                            }
                        }
                    },
                    'side_dishes': {
                        validators: {
                            notEmpty: {
                                message: __("Món phụ không được bỏ trống"),
                            }
                        }
                    },
                    'vegetable_dishes': {
                        validators: {
                            notEmpty: {
                                message: __("Món rau không được bỏ trống"),
                            }
                        }
                    },
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    submitButton: new FormValidation.plugins.SubmitButton(),
                    bootstrap: new FormValidation.plugins.Bootstrap({})
                }
            }
        ).on('core.form.valid', function () {
            $('#sm_make_menu_dishes').prop('disabled', true);
            $('#sm_make_menu_dishes').html(__("saving"));
            KTApp.block('#form_add_menu_dishes');
            $.ajax({
                url: '{{ route('orders.ajax.add_menu_dishes')}}',
                data: $('#form_add_menu_dishes').serialize(),
                method: 'POST'
            }).done(function (response) {
                KTApp.unblock('#form_add_menu_dishes');
                $('#sm_make_menu_dishes').prop('disabled', false);
                $('#sm_make_menu_dishes').html("Tạo danh sách");
                if (response.success) {
                    window._display_alert_success('#form_add_menu_dishes', __("Thêm đơn thành công"));
                    if (window._tables.menu_dishis.datatable) {
                        window._tables.menu_dishis.datatable.reload();
                    } else {
                        window.location.reload()
                    }
                    setTimeout(function () {
                        $('#add_new_dishes').modal('hide');
                    }, 1000);
                } else {
                    window._display_alert_error('#form_add_menu_dishes', __("field_wrong_format"));
                }
                $('#form_add_menu_dishes').find('[type=submit]').attr('disabled', 'disabled');
            }).fail(function (jqXHR, status) {
                response = jqXHR.responseJSON;
                $('#sm_make_menu_dishes').prop('disabled', false);
                $('#sm_make_menu_dishes').html("Tạo danh sách");
                KTApp.unblock('#form_add_menu_dishes');
                toastr.error(response.errors);
            });
        });
    </script>
</form>
