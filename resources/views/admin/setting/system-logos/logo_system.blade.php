<div class="col-md-12 row p-0 m-0">
    <div class="col-md-6">

        <form class="form updateLogoSystem" id="updateLogoWeb" method="POST" action="{{ route('config.setting.update-account-system-logos') }}" enctype="multipart/form-data">
            <div class="image-config">
                <div class="image-edit">
                    <input type='file' id="imageUpload" name="p_file" accept=".png, .jpg, .jpeg, .gif"/>
                    <label for="imageUpload">
                        <x-icon class="iconEdit svg-icon-primary" type="svg" category="Design" icon="Edit"/>
                    </label>
                </div>
                <div class="image-preview">
                    <div id="imagePreview" style="background-image: url('{{ $company_info->logo ? '/storage/uploads/logo/logo/'.$company_info->logo : asset('uploads/logo/logo_1596014132.png') }}');">
                    </div>
                </div>
            </div>

            <small>- {{ __('xin_logo_files_only') }}</small><br />
            <small>- {{ __('xin_best_main_logo_size') }}</small><br />
            <small>- {{ __('xin_logo_whit_background_light_text') }}</small>
            <hr>
            <div class="col-md-12 text-right">
                <button type="submit" id="saveAccountSystem" class="btn btn-primary mr-2">{{ __('xin_save') }}</button>
            </div>
        </form>

    </div>

    <div class="col-md-6">
        <form class="form updateLogoSystem" method="POST" action="{{ route('config.setting.update-account-system-logos') }}"  enctype="multipart/form-data">
            <div class="image-config">
                <div class="image-edit">
                    <input type='file' id="imageUpload1" name="favicon"  accept=".png, .jpg, .jpeg, .gif"/>
                    <input type="hidden" value="favicon" name="module">
                    <label for="imageUpload1">
                        <x-icon class="iconEdit svg-icon-primary" type="svg" category="Design" icon="Edit"/>
                    </label>
                </div>
                <div class="image-preview">
                    <div id="imagePreview1" style="background-image: url('{{ $company_info->favicon ? '/storage/uploads/logo/favicon/'.$company_info->favicon : asset('uploads/logo/logo_1596014132.png') }}');">
                    </div>
                </div>
            </div>

            <small>- {{ __('xin_logo_files_only_favicon') }}</small><br />
            <small>- {{ __('xin_best_logo_size_favicon') }}</small><br />
            <small></small><br />
            <hr>
            <div class="col-md-12 text-right">
                <button type="submit" id="saveAccountSystem" class="btn btn-primary mr-2">{{ __('xin_save') }}</button>
            </div>
        </form>
    </div>
</div>


@section('js')
    <script type="text/javascript">

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $("#imagePreview").css(
                        "background-image",
                        "url(" + e.target.result + ")"
                    );
                    $("#imagePreview").hide();
                    $("#imagePreview").fadeIn(650);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#imageUpload").change(function () {
            readURL(this);
        });

        function readURL1(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $("#imagePreview1").css(
                        "background-image",
                        "url(" + e.target.result + ")"
                    );
                    $("#imagePreview1").hide();
                    $("#imagePreview1").fadeIn(650);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#imageUpload1").change(function () {
            readURL1(this);
        });

        function readURL2(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $("#imagePreview2").css(
                        "background-image",
                        "url(" + e.target.result + ")"
                    );
                    $("#imagePreview2").hide();
                    $("#imagePreview2").fadeIn(650);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#imageUpload2").change(function () {
            readURL2(this);
        });

        function readURL3(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $("#imagePreview3").css(
                        "background-image",
                        "url(" + e.target.result + ")"
                    );
                    $("#imagePreview3").hide();
                    $("#imagePreview3").fadeIn(650);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#imageUpload3").change(function () {
            readURL3(this);
        });

        function readURL4(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $("#imagePreview4").css(
                        "background-image",
                        "url(" + e.target.result + ")"
                    );
                    $("#imagePreview4").hide();
                    $("#imagePreview4").fadeIn(650);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#imageUpload4").change(function () {
            readURL4(this);
        });
    </script>
@endsection

