@extends('layout.default')

@section('styles')

@endsection

@section('content')

    <div class="d-flex flex-row">

        @include('admin.setting.nav_config')

        <div class="flex-row-fluid ml-lg-8">
            <div class="card card-custom card-stretch">
                <form class="form" id="updateAccountPaymentGateway" method="POST" action="{{ route('config.setting.update-account-payment-gateway') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="col-md-12 p-0 m-0 row">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ __('xin_acc_paypal_email') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control"  placeholder="{{ __('xin_acc_paypal_email') }}" name="paypal_email" value="{{ $setting->paypal_email }}"/>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ __('xin_acc_paypal_sandbox_active') }}</label>
                                    <select class="form-control selectSearch resetSelect" data-placeholder="{{ __('xin_acc_paypal_sandbox_active') }}" name="paypal_sandbox">
                                        <option value="yes" {{ $setting->paypal_sandbox == 'yes' ? 'selected' : '' }}>{{ __('xin_yes') }}</option>
                                        <option value="no" {{ $setting->paypal_sandbox == 'no' ? 'selected' : '' }}>{{ __('xin_no') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{!! __('xin_employees_active') !!}</label>
                                    <select class="form-control selectSearch resetSelect" data-placeholder="{{ __('xin_employees_active') }}" name="paypal_active">
                                        <option value="yes" {{ $setting->paypal_active == 'yes' ? 'selected' : '' }}>{{ __('xin_yes') }}</option>
                                        <option value="no" {{ $setting->paypal_active == 'no' ? 'selected' : '' }}>{{ __('xin_no') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{ __('xin_acc_paypal_ipn_url') }}</label>
                                    <input type="text" disabled class="form-control" placeholder="{{ __('xin_acc_paypal_email') }}" name="paypal_ipn_url" value="{{ request()->url().'/admin/gateway/paypal_process/paypal_ipn' }}"/>
                                </div>
                            </div>

                            <div class="card-title col-md-12 mt-10">
                                <div class="navi-text">
                                    <div class="font-weight-bold">
                                        {{ __('xin_acc_stripe_info') }}
                                    </div>
                                </div>
                                <hr>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ __('xin_acc_stripe_secret_key') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control"  placeholder="{{ __('xin_acc_stripe_secret_key') }}" name="stripe_secret_key" value="{{ $setting->stripe_secret_key }}"/>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ __('xin_acc_stripe_publlished_key') }}</label>
                                    <input type="text" class="form-control"  placeholder="{{ __('xin_acc_stripe_publlished_key') }}" name="stripe_publishable_key" value="{{ $setting->stripe_publishable_key }}"/>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{!! __('xin_employees_active') !!}</label>
                                    <select class="form-control selectSearch resetSelect" data-placeholder="{{ __('xin_employees_active') }}" name="stripe_active">
                                        <option value="yes" {{ $setting->stripe_active == 'yes' ? 'selected' : '' }}>{{ __('xin_yes') }}</option>
                                        <option value="no" {{ $setting->stripe_active == 'no' ? 'selected' : '' }}>{{ __('xin_no') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="card-title col-md-12 mt-10">
                                <div class="navi-text">
                                    <div class="font-weight-bold">
                                        {{ __('xin_acc_online_payment_receive_account') }}
                                    </div>
                                </div>
                                <hr>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{ __('xin_acc_accounts') }} <span class="text-danger">*</span></label>
                                    <select class="form-control selectSearch resetSelect" data-placeholder="{{ __('xin_employees_active') }}" name="bank_cash_id">
                                        @foreach($bank_cash as $val)
                                            <option value="{{ $val->bankcash_id }}" {{ $setting->online_payment_account == $val->bankcash_id ? 'selected' : ''  }}>{{ $val->account_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>
                        <hr>
                        <div class="col-md-12 text-right">
                            <button type="submit" id="saveAccountSystem" class="btn btn-primary mr-2">{{ __('xin_save') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection
@section('scripts')
    <script type="text/javascript" src="{{ mix('js/employee/profile.js') }}"></script>
    <script type="text/javascript" src="{{ mix('js/employee/defaultEp.js') }}"></script>
    <script>

        $(document).ready(function() {
            $('#updateAccountPaymentGateway').submit(function (event) {
                event.preventDefault();
                var post_url = $(this).attr("action");
                var request_method = $(this).attr("method");
                var form_data = new FormData($(this)[0])

                $.ajax({
                    type: request_method,
                    url: post_url,
                    data: form_data,
                    dataType: "json",
                    cache: false,
                    mimeType: 'multipart/form-data',
                    processData: false,
                    contentType: false,
                }).done(function (response) {
                    if (response.success) {
                        toastr.success(response.data);
                    } else if (response.errors) {
                        toastr.error(response.data);
                    }
                })
            })
        });
    </script>
@endsection
