@extends('layout.default')

@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">Kho
                    <span class="d-block text-muted pt-2 font-size-sm"> Biểu đồ tài sản chung</span>
                </h3>
            </div>
            <div class="card-toolbar">
                <!--begin::Button-->
                <button class="btn btn-primary font-weight-bolder mr-3" data-toggle="modal" data-target="#add_store">
                    <x-icon type="svg" category="Code" icon="Plus"/> {{ __("xin_add_new") }}
                </button>
                <!--end::Button-->

                <!--begin::Button-->
                <button class="btn btn-light-primary font-weight-bolder mr-3" data-toggle="modal" data-target="#authority">
                    <x-icon type="svg" category="Communication" icon="Add-user"/> {{ __("Ủy quyền") }}
                </button>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div id="chart_3"></div>
        </div>
    </div>
    <!-- begin: Modal -->
    <div class="modal fade" id="add_store" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="add_store" aria-hidden="true" data-form-url="{{ route('admin.asset.form-store-asset')}}">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __("xin_add_new") }} {{ __("tài sản") }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body form-container" style="min-height:150px">
                </div>
            </div>
        </div>
    </div>
    <!-- end: Modal -->
    <!-- begin: Modal -->
    <div class="modal fade" id="authority" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="authority" aria-hidden="true" data-form-url="{{ route('admin.asset.form-store-authority')}}">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __("Ủy quyền") }} {{ __("tài sản") }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body form-container" style="min-height:150px">
                </div>
            </div>
        </div>
    </div>
    <!-- end: Modal -->
@endsection

@section('scripts')
    <script>
        const cate_name = @json($total_asset_cate['cate_name']);
        const asset_active = @json($total_asset_cate['asset_active']);
        const asset_inventory = @json($total_asset_cate['asset_inventory']);
        const asset_inactive = @json($total_asset_cate['asset_inactive']);

        const primary = '#1689FF';
        const warning = '#F2C80F';
        const dannger = '#EC0000';

        var chart = null;
        var update_data_chart = null;
        
        var _demo3 = function () {
            const apexChart = "#chart_3";
            var options = {
                series: [{
                    name: 'Hoạt động',
                    data: asset_active
                }, {
                    name: 'Tồn kho',
                    data: asset_inventory
                }, {
                    name: 'Hỏng',
                    data: asset_inactive
                }],
                chart: {
                    type: 'bar',
                    height: 350
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '15%',
                        dataLabels: {
                            position: 'top',
                        },
                    },
                },
                dataLabels: {
                    offsetY: -15,
                    style: {
                        fontSize: '10px',
                        colors: ["#80808F"]
                    }
                },
                legend: {
                    position: 'top',
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: cate_name
                },
                yaxis: {
                    title: {
                        text: 'chiếc'
                    }
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val + " chiếc"
                        }
                    }
                },
                colors: [primary, warning, dannger]
            };

            chart = new ApexCharts(document.querySelector(apexChart), options);
            chart.render();
        };
        $(document).ready(function () {
            _demo3();
            update_data_chart = function () {
                $.get("get-data-chart", function(response){
                    chart.updateOptions({
                        xaxis: {
                            categories: response.cate_name
                        }
                    });
                    chart.updateSeries([{
                        name: 'Hoạt động',
                        data: response.asset_active
                    }, {
                        name: 'Tồn kho',
                        data: response.asset_inventory
                    }, {
                        name: 'Hỏng',
                        data: response.asset_inactive
                    }]);
                });
            }
        });
    </script>
    <script>
        $('#add_store').on('show.bs.modal', function (e) {
            window._loading("#add_store .modal-body");
            let url = $('#add_store').data('form-url');
            $.ajax({
                url: url
            })
                .done(function (response) {
                    window._loading("#add_store .modal-body", false);
                    $("#add_store .modal-body").html(response);
                })
                .fail(function (jqXHR, status){
                    window._loading("#add_store .modal-body", false);
                    toastr.error(__("order_form_fetch_error"));
                    window._display_alert("#add_store .modal-body", __("order_form_fetch_error"));
                });
        });

        $('#authority').on('show.bs.modal', function (e) {
            window._loading("#authority .modal-body");
            let url = $('#authority').data('form-url');
            $.ajax({
                url: url
            })
                .done(function (response) {
                    window._loading("#authority .modal-body", false);
                    $("#authority .modal-body").html(response);
                })
                .fail(function (jqXHR, status){
                    window._loading("#authority .modal-body", false);
                    toastr.error(__("order_form_fetch_error"));
                    window._display_alert("#authority .modal-body", __("order_form_fetch_error"));
                });
        });
    </script>
@endsection



