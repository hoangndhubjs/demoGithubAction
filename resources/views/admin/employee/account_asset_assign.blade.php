 @extends('layout.default')

@section('content')

    <div class="d-flex flex-row">
        @include('admin.employee.nav_employee')

        <div class="flex-row-fluid ml-lg-8">
            <div class="card card-custom card-stretch">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label">{{ __('xin_asset_assign')  }}
                            <i class="mr-2"></i>
                        </h3>
                    </div>
                </div>

                <div class="card-body p-2">
                    <div class="datatable datatable-bordered datatable-head-custom table-responsive position-relative" id="immigration"></div>
                </div>
            </div>


        </div>
    </div>

@endsection
@section('scripts')
    <script type="text/javascript">
        window._tables = {
            'immigration': {
                'url': '{{ route('employee_managements.asset_list', request()->route('id')) }}',
                'columns': [
                    {
                        field: 'stt',
                        title: '{{ __('STT') }}',
                        sortable: false,
                        autoHide: false,
                        width: 40,
                        textAlign: "center",
                        template: function (row, index) {
                            return ++index;
                        }
                    },
                    {
                        field: 'name',
                        title: '{{ __('xin_asset_name') }}',
                        template: function (row) {
                            return row.name;
                        }
                    },
                    {
                        field: 'category_asset',
                        width:80,
                        sortable: false,
                        autoHide: false,
                        title: '{{ __('xin_acc_category') }}',
                        template: function (row) {
                            return row.category_asset.category_name;
                        }
                    },
                    {
                        field: 'company_asset_code',
                        title: '{{ __('xin_company_asset_code') }}',
                        textAlign: "center",
                        sortable: false,
                        template: function (row) {
                            return row.company_asset_code;
                        }
                    },
                    {
                        field: 'is_working',
                        sortable: false,
                        autoHide: false,
                        title: '{{ __('dashboard_xin_status') }}',
                        template: function (row) {
                            let active = "<span class='label label-lg font-weight-bold label-light-primary label-inline'>{{ __('xin_is_working') }}</span>";
                            let inactive = "<span class='label label-lg font-weight-bold label-light-danger label-inline'>{{ __('xin_employees_inactive') }}</span>";

                            return row.is_working == 1 ? active : inactive;
                        }
                    },
                ],
            }
        };
    </script>
    <script type="text/javascript" src="{{ mix('js/employee/profile.js') }}"></script>
    <script src="{{ mix('js/list.js') }}" type="text/javascript"></script>
@endsection
