@if(isset($type))
    <form class="form" id="formAssetCategory" method="POST" action="{{ route('admin.asset.create_asset_category') }}" enctype="multipart/form-data">
        @csrf
        @if(isset($assetCategory))
            <input type="hidden" name="id" value="{{ $assetCategory->assets_category_id }}"/>
        @endif
        <div class="card-body">
            <div class="col-xxl-12 col-lg-12 p-0 m-0">
                <div class="form-group">
                    <label>{{ __('xin_name') }} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="{{ __('xin_name') }}" name="category_name" value="{{ $assetCategory ? $assetCategory->category_name : '' }}"/>
                    <span class="form-text text-danger" id="errorCateName"></span>
                </div>

            </div>
        </div>

        <div class="card-footer">
        <div class="col-12 text-center">
            <button type="submit" id="saveImmigration" class="btn btn-primary mr-2">{{ __('xin_save') }}</button>
            <button type="reset" class="reset_form btn btn-secondary" data-dismiss="modal" aria-label="Close">{{ __('xin_close') }}</button>
        </div>
    </div>
    </form>
@else
    <h3 class="text-center">{{ __("Yêu cầu không tồn tại") }}</h3>
@endif

<script>
    $(document).ready(function() {
        //form add
        $('#formAssetCategory').submit(function (event) {
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
                    if(response.errorsForm.category_name){
                        $('#errorCateName').html(response.errorsForm.category_name[0]);
                        $('#errorCateName').show()
                    }
                }

                if (response.success) {
                    toastr.success(response.data);
                    $('#popupAssetCategory').modal('hide');
                    $('#formAssetCategory').trigger( "reset" );
                    reloadTable();
                } else if (response.errors) {
                    toastr.error(response.data);
                }
            });
            return false;
        });

    });
</script>
