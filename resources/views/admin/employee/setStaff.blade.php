@extends('layout.default')
@section('styles')
    <style>
        .is_select_end_trail{display: none;}
    </style>
@endsection
@section('content')
    <div class="row">
    <div class="col-xl-12 mb-5">
        <div class="card card-custom card-stretch">
            <div class="">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">{{ $page_title }}
                            <span class="d-block text-muted pt-2 font-size-sm">Danh sách hồ sơ nhân viên của công ty</span></h3>
                    </div>
                    <div class="card-toolbar">
                        <!--begin::Button-->
                        <!--end::Button-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <div class="d-flex flex-row">

        @include('admin.employee.nav.nav')

        <div class="flex-row-fluid ml-lg-8">
            <div class="card card-custom card-stretch">
                <div class="card-header py-3">
                    <div class="card-title align-items-start flex-column">
                        <h3 class="card-label font-weight-bolder text-dark">
                            {{ __('xin_employee_set_salary')  }}
                        </h3>
                        <span class="text-muted font-weight-bold font-size-sm mt-1"></span>
                    </div>
                </div>
                <form class="form" id="updateSalary">
                    <input type="hidden" name="employee_id" value="{{ request()->route('id') }}">
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label">
                                {{ __('xin_salary_type').''.__('xin_tooltip_salary_gross') }}
                                <span class="text-danger"> *</span>
                            </label>
                            <div class="col-lg-9 col-xl-6 col-form-label type_salary">
                                <div class="radio-inline row col-md-10 pb-2">
                                    <label class="radio col px-md-5">
                                        <input type="radio" value="1" name="ways_type" {{ $getUser->wages_type == 1 ? 'checked' : ''}}/>
                                        <span></span>
                                        {{ __('Lương chính thức') }}
                                    </label>
                                    <label class="radio col px-md-5">
                                        <input type="radio" value="3" name="ways_type" {{ $getUser->wages_type == 3 ? 'checked' : ''}}/>
                                        <span></span>
                                        {{ __('Lương partime') }}
                                    </label>
                                </div>
                                <div class="radio-inline row col-md-10">
                                    <label class="radio col px-md-5">
                                        <input type="radio" value="2" name="ways_type" {{ $getUser->wages_type == 2 ? 'checked' : ''}}/>
                                        <span></span>
                                        {{ __('Lương thử việc') }}
                                    </label>
                                    <label class="radio col px-md-5">
                                        <input type="radio" value="4" name="ways_type" {{ $getUser->wages_type == 4 ? 'checked' : ''}}/>
                                        <span></span>
                                        {{ __('Lương học việc') }}
                                    </label>

                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label">{{ __('xin_payroll_basic_salary') }}<span class="text-danger"> *</span></label>
                            <div class="col-lg-9 col-xl-6">

                                <div class="input-group">
                                    <input type="text" class="form-control numberRq" placeholder="{{ __('xin_salary_basic') }}" name="basic_salary"
                                           value="{{ $getUser->basic_salary }}"/>
                                </div>
                            </div>
                        </div>
                        <div class="is_select_end_trail {{ $getUser->wages_type == 2 ? 'd-block' : 'd-none'  }}">
                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">
                                    {{ __('xin_end_trail_work') }}
                                    <span class="text-danger"> *</span>
                                </label>
                                <div class="col-lg-9 col-xl-6">
                                    <input autocomplete="off" class="form-control" id="end_trail_work" type="text" placeholder="{{ __('xin_end_trail_work') }}"
                                           name="end_trail_work" value="{{ ($getUser->end_trail_work)?date('d-m-Y', strtotime($getUser->end_trail_work)):'' }}"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">{{ __('xin_payroll_trail_work') }}<span class="text-danger"> *</span></label>
                                <div class="col-lg-9 col-xl-6">
                                    <input class="form-control numberRq" type="text" placeholder="{{ __('xin_payroll_trail_work') }}"
                                           name="salary_trail_work" value="{{ $getUser->wages_type == 2 ? $getUser->salary_trail_work : $getUser->basic_salary }}"/>
                                </div>

                            </div>
                        </div>

                       <div class="form-group">
                           <div class="card-toolbar col-md-6 mx-auto text-right">
                               <button class="btn btn-primary mr-2">{{ __('xin_save') }}</button>
                               <a href="/" class="btn btn-secondary">{{ __('xin_cancel') }}</a>
                           </div>
                       </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript" src="{{ mix('js/employee/profile.js') }}"></script>
@endsection
