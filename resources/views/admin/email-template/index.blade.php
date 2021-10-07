@extends('layout.default')

@section('styles')
    <link rel="stylesheet" href="{{ mix('plugins/custom/datatables/datatables.bundle.css') }}">
@endsection

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">

            <div class="card-title">
                <h3 class="card-label">{{ $page_title }}</h3>
            </div>
        </div>

        <div class="card-body">
            <div class="datatable datatable-bordered datatable-head-custom" id="email_template_list"></div>
        </div>

    </div>

    <div class="modal fade px-0" id="popupEmailTemplate" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="popupEmailTemplate" aria-hidden="true" data-form-url="{{ route('config.create_form_template')}}">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('xin_edit_email_template') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body1">
                    <div class="card card-custom" style="min-height: 50px"></div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script src="{{ mix('js/list.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        window._tables = {
            'email_template_list': {
                'url': '{{ route('config.list-email-template') }}',
                'columns': [
                    {
                        field: 'action',
                        width: 80,
                        title: '{{ __('xin_action') }}',
                        sortable: false,
                        autoHide: false,
                        textAlign: 'center',
                        template: function (row) {
                            let html = "<a href=\"javascript:;\" class=\"btn btn-sm btn-clean btn-icon mr-2\" title=\"Edit details\" data-toggle=\"modal\" data-target=\"#popupEmailTemplate\" data-id=\'"+row.template_id+"\'>" +
                                "<span class=\"svg-icon svg-icon-md\">" +
                                "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24px\" height=\"24px\" viewBox=\"0 0 24 24\" version=\"1.1\">" +
                                "<g stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\">" +
                                "<rect x=\"0\" y=\"0\" width=\"24\" height=\"24\"/>" +
                                "<path d=\"M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z\" fill=\"#000000\" fill-rule=\"nonzero\"\\ transform=\"translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) \"/>" +
                                "<rect fill=\"#000000\" opacity=\"0.3\" x=\"5\" y=\"20\" width=\"15\" height=\"2\" rx=\"1\"/>" +
                                "</g>" +
                                "</svg>" +
                                "</span>" +
                                "</a>";
                            return html;
                        }
                    },
                    {
                        field: 'name',
                        title: '{{ __('xin_name_of_template') }}',
                        sortable: true,
                        autoHide: false,
                        template: function (row) {
                            return row.name
                        }
                    },
                    {
                        field: 'subject',
                        title: '{{ __('xin_theme_title') }}',
                        sortable: false,
                        autoHide: false,
                        template: function (row) {
                            return row.subject
                        }
                    },
                    {
                        field: 'status',
                        title: '{{ __('dashboard_xin_status') }}',
                        sortable: false,
                        autoHide: false,
                        textAlign: 'center',
                        template: function (row) {
                            let status = {
                                0: {
                                    'class': ' label-light-danger',
                                    'title': __('xin_employees_inactive')
                                },
                                1: {
                                    'class': ' label-light-success',
                                    'title': __('xin_employees_active')
                                },
                            };
                            let html = "";
                            return '<span class="label label-lg font-weight-bold ' + status[row.status].class + ' label-inline">' + status[row.status].title + '</span>';
                        }
                    }
                ],
            }
        };

        $('#popupEmailTemplate').on('shown.bs.modal', function (e) {
            window._loading("#popupEmailTemplate .card-custom");
            let button = $(e.relatedTarget);
            let id = button.data('id');
            let url = $('#popupEmailTemplate').data('form-url');
            $.ajax({
                url: url,
                data: {
                    id: id
                }
            })
                .done(function (response) {
                    window._loading("#popupEmailTemplate .card-custom", false);
                    $("#popupEmailTemplate .card-custom").html(response);
                })
                .fail(function (jqXHR, status){
                    window._loading("#popupEmailTemplate .card-custom", false);
                    toastr.error(__("order_form_fetch_error"));
                    window._display_alert("#popupEmailTemplate .card-custom", __("order_form_fetch_error"));
                });
        });

        $('#popupEmailTemplate').on('hidden.bs.modal', function (e) {
            $("#popupEmailTemplate .card-custom").html("");
        });

        function reloadTable() {
            window._tables.email_template_list.datatable.reload();
        }
    </script>
@endsection
