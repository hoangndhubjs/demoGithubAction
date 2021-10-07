@extends('layout.default')

@section('styles')
@endsection

@section('content')

    <div class="d-flex flex-row">

        @include('admin.setting.nav_config')

        <div class="flex-row-fluid ml-lg-8">
            <div class="card card-custom card-stretch">
                <form class="form" id="updateAccountGeneral" method="POST" action="{{ route('config.setting.update-account-general') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="col-md-12 p-0 m-0 row">

                            <div class="col-md-6">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ __('xin_company_name') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control"  placeholder="{{ __('xin_company_name') }}" name="company_name" value="{{ $companyInfo->company_name }}"/>
                                        <span class="form-text text-danger" id="errorCompanyName"></span>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ __('xin_clcontact_person') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control"  placeholder="{{ __('xin_clcontact_person') }}" name="contact_person" value="{{ $companyInfo->contact_person }}"/>
                                        <span class="form-text text-danger" id="errorContact"></span>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ __('dashboard_email') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control"  placeholder="{{ __('dashboard_email') }}" name="email" value="{{ $companyInfo->email }}"/>
                                        <span class="form-text text-danger" id="errorEmail"></span>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ __('xin_phone') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control numberRq" placeholder="{{ __('xin_phone') }}" name="phone" value="{{ $companyInfo->phone }}"/>
                                        <span class="form-text text-danger" id="errorPhone"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ __('xin_address') }}</label>
                                        <textarea class="form-control" rows="1" placeholder="{{ __('xin_address_1') }}" name="address_1">{{ $companyInfo->address_2 }}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <textarea class="form-control" rows="1" placeholder="{{ __('xin_address_2') }}" name="address_2">{{ $companyInfo->address_2 }}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="{{ __('xin_city') }}" name="city" value="{{ $companyInfo->city }}"/>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" class="form-control"  placeholder="{{ __('xin_state') }}" name="state" value="{{ $companyInfo->state }}"/>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" class="form-control"  placeholder="{{ __('xin_zipcode') }}" name="zipcode" value="{{ $companyInfo->zipcode }}"/>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <select class="form-control selectSearch resetSelect" data-placeholder="{{ __('xin_country') }}" name="country">
                                            @foreach($country as $val)
                                                <option value="{{ $val->country_id }}" {{ $companyInfo->country == $val->country_id ? 'selected' : '' }}>{{ $val->country_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
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
    <script type="text/javascript">
        $(document).ready(function() {
            $('#updateAccountGeneral').submit(function (event) {
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

                    if(response.errorsForm) {
                        if(response.errorsForm.company_name){
                            $('#errorCompanyName').html(response.errorsForm.company_name[0]);
                            $('#errorCompanyName').show()
                        }
                        if(response.errorsForm.contact_person){
                            $('#errorContact').html(response.errorsForm.contact_person[0]);
                            $('#errorContact').show()
                        }
                        if(response.errorsForm.email){
                            $('#errorEmail').html(response.errorsForm.email[0]);
                            $('#errorEmail').show()
                        }
                        if(response.errorsForm.phone){
                            $('#errorPhone').html(response.errorsForm.phone[0]);
                            $('#errorPhone').show()
                        }
                    }

                    if (response.success) {
                        toastr.success(response.data);
                        // $('#popupExperience').modal('hide');
                        // $('#formExperience').trigger( "reset" );
                        // window._tables.experience.datatable.reload();
                    } else if (response.errors) {
                        toastr.error(response.data);
                    }
                })
            })
        });
    </script>
@endsection
