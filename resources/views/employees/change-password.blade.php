@extends('layout.default')

@section('content')

    <div class="d-flex flex-row">
        @include('employees.nav_employee')

        <div class="flex-row-fluid ml-lg-8">
            <div class="card card-custom card-stretch">
                <div class="card-header py-3">
                    <div class="card-title align-items-start flex-column">
                        <h3 class="card-label font-weight-bolder text-dark">
                            {{ __('header_change_password')  }}
                        </h3>
                    </div>
                    <div class="card-toolbar">
                        <button id="savePass" class="btn btn-primary mr-2">{{ __('xin_save') }}</button>
                        <a href="/" class="btn btn-secondary">{{ __('xin_cancel') }}</a>
                    </div>
                </div>
                <form class="form" id="formChangePass">
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label">{{ __('xin_old_password') }}</label>
                            <div class="col-lg-9 col-xl-6">
                                <div class="input-group">
                                    <input type="password" class="form-control"
                                           placeholder="{{ __('xin_old_password') }}"
                                           name="current_password"/>
                                </div>
                                <span class="form-text text-danger" id="errorCurrentPassword"></span>
                            </div>

                        </div>

                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label">{{ __('xin_e_details_enpassword') }}</label>
                            <div class="col-lg-9 col-xl-6">
                                <div class="input-group">
                                    <input type="password" class="form-control"
                                           placeholder="{{ __('xin_e_details_enpassword') }}"
                                           name="new_password"/>
                                </div>
                                <span class="form-text text-danger" id="errorNewPassword"></span>
                            </div>

                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label">{{ __('xin_employee_cpassword') }}</label>
                            <div class="col-lg-9 col-xl-6">
                                <div class="input-group">
                                    <input type="password" class="form-control"
                                           placeholder="{{ __('xin_employee_cpassword') }}"
                                           name="new_confirm_password"/>
                                </div>
                                <span class="form-text text-danger" id="errorNewConfirmPassword"></span>
                            </div>

                        </div>

                    </div>
                </form>
            </div>


        </div>
    </div>

@endsection
@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {

            $('button[id="savePass"]').click(function (e) {
                let current_password = $('input[name ="current_password"]').val();
                let new_password = $('input[name ="new_password"]').val();
                let new_confirm_password = $('input[name ="new_confirm_password"]').val();
                let url = '{{ route('updatePassword') }}';

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: url,
                    data: {
                        'current_password': current_password,
                        'new_password': new_password,
                        'new_confirm_password': new_confirm_password,
                    },
                    cache: false,

                    success: function (response) {

                        if (response.errorsForm) {
                            if (response.errorsForm.current_password) {
                                $('#errorCurrentPassword').html(response.errorsForm.current_password[0]);
                                $('#errorCurrentPassword').show()
                            }
                            if (response.errorsForm.new_password) {
                                $('#errorNewPassword').html(response.errorsForm.new_password[0]);
                                $('#errorNewPassword').show()
                            }
                            if (response.errorsForm.new_confirm_password) {
                                $('#errorNewConfirmPassword').html(response.errorsForm.new_confirm_password[0]);
                                $('#errorNewConfirmPassword').show()
                            }
                        }

                        if (response.success) {
                            toastr.success(response.data);
                            $('#formChangePass').trigger("reset");
                            setTimeout(window.location.href = '{{ route('logout') }}', 5000)
                        } else if (response.errors) {
                            toastr.error(response.data);
                        }

                        $(':input').change(function () {
                            $(this).closest('.form-group').find('.form-text.text-danger').hide();
                        });
                    }
                })
            })

        });
    </script>
@endsection
