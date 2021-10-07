<div class="modal-body form-container" style="min-height:150px">
<style>
    .avatar_user {
        width: 50px;
        height: 50px;
    }
</style>
<div class="body_detail">
    <div class="title_detail_warranty">
        <h2>Chi tiết tài sản: {{ $detail_asset && $detail_asset['asset'] ? $detail_asset['asset']['name'] : '' }}</h2>
    </div>
    <div class="asset_info mt-5">
        <div class="base_info">
            <div class="row">
                <div class="col-md-6">
                    <p class="font-weight-bold">{{ __('Ngày nhập:') }}</p>
                </div>
                <div class="col-md-6">
                    <p class="text-right">{{ $detail_asset && $detail_asset['asset'] ? date('m-d-Y', strtotime($detail_asset['asset']->date_add_asset)) : '' }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <p class="font-weight-bold">{{ __('Tuổi thọ:') }}</p>
                </div>
                <div class="col-md-6">
                    <p class="text-right">{{ $detail_asset['asset']->age_life_asset }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <p class="font-weight-bold">{{ __('Hạn bảo hành:') }}</p>
                </div>
                <div class="col-md-6">
                    <p class="text-right">{{ date('m-d-Y', strtotime($detail_asset['asset']->warranty_end_date)) }}</p>
                </div>
            </div>
        </div>
        <!-- lịch sử sử dụng -->
        <div class="history_use">
            <div class="title_history_warranty py-2">
                <h5>Lịch sử sử dụng</h5>
            </div>
            @foreach($detail_asset['asset_history'] as $key => $history_id)
                <div class="d-flex align-items-center mb-10">
                    <!--begin::Symbol-->
                    <div class="symbol symbol-40 symbol-light-success mr-5">
                          <span class="symbol-label">
                            <img src="{{ $history_id->employee->profile_picture }}" class="h-100 w-100 align-self-end" alt="" />
                          </span>
                    </div>
                    <!--end::Symbol-->
                    <!--begin::Text-->
                    <div class="d-flex flex-column flex-grow-1 font-weight-bold">
                        <a href="#" class="text-dark text-hover-primary mb-1 font-size-lg">{{ $history_id->employee->last_name.' '.$history_id->employee->first_name }}</a>
                        <span class="text-muted">ID người sử dụng: {{ $history_id->employee->employee_id }}</span>
                    </div>
                    <!--end::Text-->
                    <!--begin::Dropdown-->
                    <div class="dropdown dropdown-inline ml-2" data-toggle="tooltip" title="" data-placement="left" data-original-title="Quick actions">
                        <p class="m-0">Từ: <span class="font-weight-bold">{{ date('d-m-Y', strtotime($history_id->start_date)) }}</span></p>
                        <p class="m-0">Đến: <span class="font-weight-bold">{{ date('d-m-Y', strtotime($history_id->end_date)) }}</span></p>
                    </div>
                    <!--end::Dropdown-->
                </div>
            @endforeach
        </div>
        <!-- lịch sử bảo hành -->
        @if($detail_asset['warranty'])
            <div class="history_warranty">
            <div class="title_history_warranty py-2">
               <h5>Lịch sử bảo hành</h5>
            </div>
            @foreach($detail_asset['warranty'] as $warranty_id)
                <div class="row mb-2">
                    <div class="col-md-6 col-xs-6 warranty_note ">{{ $warranty_id->warranty_note }}</div>
                    <div class="col-md-6 text-right col-xs-6 ">
                        <p class="m-0">Từ: <span class="font-weight-bold">{{ date('m-d-Y', strtotime($warranty_id->warranty_start)) }}</span></p>
                        <p class="m-0">Đến: <span class="font-weight-bold">{{ date('m-d-Y', strtotime($warranty_id->warranty_end)) }}</span></p>
                    </div>
                </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
<div class="justify-content-center">
    <div class="text-right">
        <button type="reset" class="btn btn-light-primary" data-dismiss="modal" aria-label="Close">{{ __('cancel')  }}</button>
    </div>
</div>
</div>
