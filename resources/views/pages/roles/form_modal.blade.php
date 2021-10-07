@if(isset($type))
    <form method="POST" action="{{ route('role.ajax.store') }}" id="form_role" enctype="multipart/form-data">
        @csrf
        {{--<input type="hidden" name="company_id" value="{{Auth::user()->company_id}}">
        <input type="hidden" name="complaint_from" value="{{Auth::user()->user_id}}">--}}
        @if(isset($role))
        <input type="hidden" name="id" id="role_id" value="{{$role->id}}"/>
        @endif
        <div class="form-group row">
            <div class="col-lg-4">
                <div class="form-group row">
                    <div class="col-lg-12">
                        <label class="d-block required">{{ __("xin_company_name") }} <span class="text-danger"> *</span></label>
                        <select class="form-control {{--selectpicker--}}" name="company_id" id="company_id" style="width: 100%">
                            @foreach($companies as $key => $company)
                                <option value="{{ $key }}" @if(isset($role->company_id) && $role->company_id == $key) {{'selected'}} @endif >{{ $company }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-lg-12">
                        <label class="d-block required">{{ __("xin_role_name") }}</label>
                        <input class="form-control" placeholder="{{__('xin_role_name')}}"
                               name="role_name" id="role_name" type="text" value="@if(isset($role->name)){{$role->name}}@endif">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-lg-12">
                        <label class="d-block required">{{ __("xin_role_access") }} <span class="text-danger"> *</span></label>
                        <select class="form-control {{--selectpicker--}}" name="role_access" id="role_access" style="width: 100%">
                            <option value="2" @if(isset($role->role_access) &&  $role->role_access == 2) {{'selected'}} @endif>{{ __('Truy cập menu tùy chỉnh') }}</option>
                            <option value="1" @if(isset($role->role_access) &&  $role->role_access == 1) {{'selected'}} @endif>{{ __('Truy cập tất cả menu') }}</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p><strong>{{__('xin_role_note_title')}}</strong></p>
                        <p>{{__('xin_role_note1')}}</p>
                        <p>{{__('xin_role_note2')}}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div id="kt_tree_6" class="tree-demo"></div>
            </div>
        </div>

        <div class="form-group text-right mb-0">
            <button type="submit" class="btn btn-primary"><x-icon type="svg" category="Design" icon="Flatten"/>  {{ $role ? __("Cập nhật") : __("Thêm mới") }}</button>
        </div>
    </form>
    <!-- JS Form -->
    <script>
        if(typeof formComplaint === "undefined") {
            let formComplaint = null;
        }
        if(typeof submitButton === "undefined") {
            let submitButton = null;
        }
        if(typeof formValidator === "undefined") {
            let formValidator = null;
        }

        formComplaint = document.getElementById('form_role');
        submitButton = formComplaint.querySelector('[type="submit"]');

        formValidator = FormValidation.formValidation(
            formComplaint,
            {
                fields: {
                    'company_id': {
                        validators: {
                            notEmpty: {
                                message: __("xin_error_complaint_title"),
                            }
                        }
                    },
                    'group_id': {
                        validators: {
                            notEmpty: {
                                message: __("xin_error_complaint_date"),
                            },
                        }
                    },
                    'name_permission': {
                        validators: {
                            notEmpty: {
                                message: __("xin_error_complaint_date"),
                            },
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
            submitButton.setAttribute('disabled', 'disabled');
            /*let formData = new FormData($('#form_permission')[0]);*/
            let permissions = $('#kt_tree_6').jstree(true).get_selected();
            let company_id = $('#company_id').val();
            let role_name =  $('#role_name').val();
            let role_access =  $('#role_access').val();
            let id =  $('#role_id').val();
            $.ajax({
                url: '{{ route('role.ajax.store')}}',
                data: {
                    permissions:permissions,
                    company_id:company_id,
                    role_name:role_name,
                    role_access:role_access,
                    id:id,
                },
                method: 'POST',
                dataType: "json",
                cache:false,
            }).done(function (response) {
                if (response.success) {
                    toastr.success(__("create_overtime_success"));
                    $('#role_modal').modal('hide');
                    window._tables.role_list.datatable.reload();
                    submitButton.setAttribute('disabled', 'disabled');
                } else {
                    submitButton.removeAttribute('disabled');
                    window._display_alert_error('#form_role', __("field_wrong_format"));
                }
                $('#form_role').find('[type=submit]').attr('disabled', 'disabled');
            }).fail(function (jqXHR, status) {
                let statusCode = jqXHR.status;
                if (statusCode !== 422) {
                    let errorText = {
                        "parsererror": __("Dữ liệu nhận được không đúng định dạng!"),
                        "error": (jqXHR.responseJSON.errors)?__(jqXHR.responseJSON.errors):__('Lỗi! Vui lòng thử lại'),
                        "timeout": __("Không có phản hồi từ máy chủ!"),
                        "abort": __("Yêu cầu bị hủy!")
                    };
                    toastr.error(errorText[status]);
                } else {
                    let data = jqXHR.responseJSON?jqXHR.responseJSON:__("Dữ liệu không hợp lệ! Vui lòng sửa lại cho đúng");
                    let html = "";
                    if (typeof jqXHR.responseJSON.errors === "object") {
                        $.each(jqXHR.responseJSON.errors, function(fieldName, errors) {
                            html += errors[0];
                            return false;
                        });
                    }
                    toastr.error(html);
                }
                submitButton.removeAttribute('disabled');
            });
        });

        /*$('#complaint_request').on('hidden.bs.modal', function (e) {
            if (formValidator) {
                formValidator.resetForm(true);
                submitButton.removeAttribute('disabled');
            }
        });*/

    </script>
    <script>
        function getData(){
            let result = null;
            let role_name = $('#role_name').val();
            let company_id = $('#company_id').val();
            let id = $('#role_id').val();
            let role_access = $('#role_access').val();
            $.ajax({
                async: false,
                url: '{{ route('permission.ajax.list')}}',
                data: {role_name:role_name, company_id:company_id, id:id, role_access:role_access},
                method: 'POST',
                dataType: "json",
                cache:false,
            }).done(function (response) {
                result = response;
            });
            return result;
        }


        $("#kt_tree_6").jstree({
            "core": {
                "themes": {
                    "responsive": false
                },
                // so that create works
                "check_callback": true,
                "data": getData(),
                "dataType" : "json",
                /*"data": {
                    "url": function(node) {
                        return '{{ route('permission.ajax.list')}}';
                },
                "dataType" : "json",
                "data": function(node) {
                    return { "text" : node.text };
                }
            }*/
            },
            "types": {
                "default": {
                    "icon": "fa fa-folder"
                },
                "file": {
                    "icon": "fa fa-file  text-primary"
                }
            },
            "plugins": ["wholerow", "checkbox", "types", "search"]
        });


        /*$('#kt_tree_6').on("changed.jstree", function (e, data) {
            console.log(data.instance.get_selected(true)[0].text);
            console.log(data.instance.get_node(data.selected[0]).text);
        });*/
        $("#company_id").change(function(e) {
            resfreshJSTree();
        });

        $("#role_access").change(function(e) {
            resfreshJSTree();
        });
        
        function resfreshJSTree() {
            $('#kt_tree_6').jstree(true).settings.core.data = getData();
            $('#kt_tree_6').jstree(true).refresh();
        }
    </script>
@else
    <h3 class="text-center">{{ __("Yêu cầu không tồn tại") }}</h3>
@endif