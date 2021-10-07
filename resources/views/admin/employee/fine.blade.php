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
                            <h3 class="card-label">{{ $page_title }}</h3>
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
            <!--begin::Card-->
            <div class="card card-custom gutter-b h-100">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">{{__($page_title)}}
                        </h3>
                    </div>
                </div>
                <div class="card-body card-body-compensations">

                    <div class="datatable-head-custom">
                        <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>{{ __('xin_action') }}</th>
                                    <th>{{ __('xin_employee_all_month_mulct') }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td>Đi muộn</td>
                                    <td>300.000 VNĐ</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script src="{{ mix('js/list.js') }}" type="text/javascript"></script>
@endsection
