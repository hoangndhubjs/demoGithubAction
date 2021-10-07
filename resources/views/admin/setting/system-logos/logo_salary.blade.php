<div class="col-md-12 row p-0 m-0">
    <div class="col-md-6">
        <form class="form updateLogoSystem" method="POST" action="{{ route('config.setting.update-account-system-logos') }}"  enctype="multipart/form-data">
            <div class="image-config">
                <div class="image-edit">
                    <input type='file' id="imageUpload4" name="payroll_logo" accept=".png, .jpg, .jpeg, .gif"/>
                    <label for="imageUpload4">
                        <x-icon class="iconEdit svg-icon-primary" type="svg" category="Design" icon="Edit"/>
                    </label>
                </div>
                <div class="image-preview">
                    <div id="imagePreview4" style="background-image: url('{{ $setting->payroll_logo ? asset('/storage/uploads/logo/payroll/'.$setting->payroll_logo) : asset('uploads/logo/logo_1596014132.png') }}');">
                    </div>
                </div>
            </div>

            <small>- {{ __('xin_logo_files_only_favicon') }}</small><br />
            <small>- {{ __('xin_best_logo_size_favicon') }}</small><br />
            <small></small><br />
            <hr>
            <div class="col-md-12 text-right">
                <button type="submit" class="btn btn-primary mr-2">{{ __('xin_save') }}</button>
            </div>
        </form>
    </div>

</div>


@section('js')

@endsection

