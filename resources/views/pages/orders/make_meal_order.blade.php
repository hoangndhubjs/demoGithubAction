@if(isset($menu))
    <form method="POST" action="{{ route('orders.ajax.create_meal_order') }}" id="form_create_meal_order">
        @if($order)
        <input type="hidden" name="id" value="{{$order->id}}"/>
        @endif
        <div class="form-group row">
            <div class="col-lg-4">
                <label class="">{{ __("order_primary_food") }}</label><span class="text-danger"> *</span>
                <select class="form-control select2 select2_primary_choose" name="primary[]" style="width: 100%" required multiple>
                    @foreach($menu->foods->get(1) as $primary)
                        <option value="{{ $primary->id }}" {{ in_array($primary->id, $order->mon_chinh ?? []) ? 'selected' : null }}>{{ $primary->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-4">
                <label class="">{{ __("order_secondary_food") }}</label><span class="text-danger"> *</span>
                <select class="form-control select2" data-plugin-select2="" name="secondary" style="width: 100%" required>
                    @foreach($menu->foods->get(2) as $secondary)
                        <option value="{{ $secondary->id }}" {{ in_array($secondary->id, $order->mon_phu ?? []) ? 'selected' : null }}>{{ $secondary->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-4">
                <label class="">{{ __("order_vegatable_food") }}</label><span class="text-danger"> *</span>
                <select class="form-control select2" data-plugin-select2="" name="tertiary" style="width: 100%" required>
                    @foreach($menu->foods->get(3) as $tertiary)
                        <option value="{{ $tertiary->id }}" {{ in_array($tertiary->id, $order->mon_rau ?? []) ? 'selected' : null }}>{{ $tertiary->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-lg-12">
                <label>{{ __("xin_note") }}</label>
                <textarea name="note" class="form-control" rows="3">{{ $order->note ?? '' }}</textarea>
            </div>
        </div>
        <div class="form-group text-right">
            <span>{{ __("Total") }}:&nbsp;</span><span id="order_price">{{ app('hrm')->getCurrencyConverter()->getUserFormat($order->price ?? config('constants.MINIMUM_MEAL_ORDER_PRICE', 0)) }}</span>
        </div>
        <div class="form-group text-right mb-0">
            <button type="submit" class="btn btn-primary"><x-icon type="svg" category="Design" icon="Flatten"/>  {{ $order ? __("update_order") : __("place_order") }}</button>
        </div>
    </form>
    <!-- JS Form -->
    <script>
        if (typeof barrierSelect === 'undefined') {
            let barrierSelect = {{ config('constants.MINIMUM_PRIMARY_MEAL_CAN_SELECT', 0) }};
            let modal_id = '{{ $modalId ?? null }}';
            $('.select2_primary_choose').select2({
                closeOnSelect: false
            }).on('change', function (e) {
                let price = {{ config('constants.MINIMUM_MEAL_ORDER_PRICE', 0) }};
                let selected = $(this).find('option:selected').length;
                if (selected > barrierSelect) {
                    price += (selected-barrierSelect)*{{ config('constants.OVER_PRICE_WHEN_SELECT_OVER_BARRIER', 0) }};
                }
                $('#order_price').html(_userCurrency(price));
            });
            $('[data-plugin-select2]').select2();
            FormValidation.formValidation(
                document.getElementById('form_create_meal_order'),
                {
                    fields: {
                        'primary[]': {
                            validators: {
                                callback: {
                                    message: __("order_primary_must_at_least_2_options"),
                                    callback: function (input) {
                                        let selected = $(input.element).find('option:selected').length;
                                        return selected > 1;
                                    }
                                }
                            }
                        }
                    },
                    plugins: {
        				trigger: new FormValidation.plugins.Trigger(),
        				submitButton: new FormValidation.plugins.SubmitButton(),
                		// defaultSubmit: new FormValidation.plugins.DefaultSubmit(), // Uncomment this line to enable normal button submit after form validation
        				bootstrap: new FormValidation.plugins.Bootstrap({})
                    }
                }
            ).on('core.form.valid', function () {
                KTApp.block('#form_create_meal_order');
                $.ajax({
                    url: '{{ route('orders.ajax.create_meal_order')}}',
                    data: $('#form_create_meal_order').serialize(),
                    method: 'POST'
                }).done(function (response) {
                    KTApp.unblock('#form_create_meal_order');
                    if (response.success) {
                        window._display_alert_success('#form_create_meal_order', __("create_meal_order_success"));
                        if(typeof window._tables.meal_order !== "undefined"){
                            window._tables.meal_order.datatable.reload();
                        } else if (typeof window._tables.user_order_rice !== "undefined"){
                            window._tables.user_order_rice.datatable.reload();
                        } else {
                            window.location.reload()
                        }
                        setTimeout(function () {
                            $('#create_meal_order').modal('hide');
                        }, 1000);
                    } else {
                        window._display_alert_error('#form_create_meal_order', __("field_wrong_format"));
                    }
                    $('#form_create_meal_order').find('[type=submit]').attr('disabled', 'disabled');
                }).fail(function (jqXHR, status) {
                    KTApp.unblock('#form_create_meal_order');
                    toastr.error(__("error"));
                });
            });
        }
    </script>
@else
    <h3 class="text-center">{{ __("order_no_menu") }}</h3>
@endif
