@extends('layout.blank')

@section('content')
    <div class="login login-4 wizard d-flex flex-column flex-lg-row flex-column-fluid">
		<!--begin::Content-->
		<div class="login-container order-2 order-lg-1 d-flex flex-center flex-row-fluid px-7 pt-lg-0 pb-lg-0 pt-4 pb-6 bg-white">
			<!--begin::Wrapper-->
			<div class="login-content d-flex flex-column pt-lg-0 pt-12">
				<!--begin::Logo-->
				<!--a href="#" class="login-logo pb-xl-20 pb-15">
					<img src="assets/media/logos/logo-4.png" class="max-h-70px" alt="" />
				</a-->
				<!--end::Logo-->
				<!--begin::Signin-->
				<div class="login-form">
					<!--begin::Form-->
					<form class="form" id="kt_login_singin_form" action="{{ route('login') }}" method="POST">
                        @csrf
						<!--begin::Title-->
						<div class="pb-5 pb-lg-15">
							<h3 class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg">{{ __('Đăng nhập') }}</h3>
							<!--div class="text-muted font-weight-bold font-size-h4">New Here?
							<a href="custom/pages/login/login-4/signup.html" class="text-primary font-weight-bolder">Create Account</a></div-->
						</div>
                        @include('components.alert_box')
						<!--begin::Title-->
						<!--begin::Form group-->
						<div class="form-group">
							<label class="font-size-h6 font-weight-bolder text-dark">{{ __('Email') }}</label>
							<input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg border-0" type="text" name="username" autocomplete="off" />
						</div>
						<!--end::Form group-->
						<!--begin::Form group-->
						<div class="form-group">
							<div class="d-flex justify-content-between mt-n5">
								<label class="font-size-h6 font-weight-bolder text-dark pt-5">{{ __('Mật khẩu') }}</label>
								<!--a href="custom/pages/login/login-4/forgot.html" class="text-primary font-size-h6 font-weight-bolder text-hover-primary pt-5">Forgot Password ?</a-->
							</div>
							<input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg border-0" type="password" name="password" autocomplete="off" />
						</div>
						<!--end::Form group-->
						<!--begin::Action-->
						<div class="pb-lg-0 pb-5">
							<button type="submit" id="kt_login_singin_form_submit_button" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-3">{{ __('Đăng nhập') }}</button>
						</div>
						<!--end::Action-->
					</form>
					<!--end::Form-->
				</div>
				<!--end::Signin-->
			</div>
			<!--end::Wrapper-->
		</div>
	</div>
@endsection

@section('styles')
    <link href="{{ mix('css/pages/login/login-4.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('scripts')
    <script src="{{ mix('js/pages/custom/login/login-4.js') }}" type="text/javascript"></script>
    <script src="{{ mix('js/app.js') }}" type="text/javascript"></script>
@endsection
