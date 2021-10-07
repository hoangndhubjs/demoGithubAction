{{-- Header --}}
@if (config('layout.extras.quick-actions.dropdown.style') == 'light')
    <div class="d-flex flex-column flex-center py-10 bg-dark-o-5 rounded-top bg-light">
        <h4 class="text-dark font-weight-bold">
            Quick Actions
        </h4>
    </div>
@else
    <div class="d-flex flex-column flex-center py-10 bgi-size-cover bgi-no-repeat rounded-top" style="background-image: url('{{ asset('media/misc/bg-1.jpg') }}')">
        <h4 class="text-white font-weight-bold">
            Quick Actions
        </h4>
    </div>
@endif

{{-- Nav --}}
<div class="row row-paddingless">
    {{-- Item --}}
    <div class="col-6">
        <a target="_blank" href="{{ env('SOCIAL_URL','#') }}" class="d-block py-10 px-5 text-center bg-hover-light border-right">
            {{ Metronic::getSVG("media/svg/icons/Code/Compiling.svg", "svg-icon-3x svg-icon-primary") }}
            <span class="d-block text-dark-75 font-weight-bold font-size-h6 mt-2 mb-1">Social</span>
            <span class="d-block text-dark-50 font-size-lg">Mạng xã hội nội bộ</span>
        </a>
    </div>

    {{-- Item --}}
    <div class="col-6">
        <a href="{{ env('CRM_URL','#') }}" target="_blank" class="d-block py-10 px-5 text-center bg-hover-light">
            {{ Metronic::getSVG("media/svg/icons/Communication/Group.svg", "svg-icon-3x svg-icon-primary") }}
            <span class="d-block text-dark-75 font-weight-bold font-size-h6 mt-2 mb-1">CRM</span>
            <span class="d-block text-dark-50 font-size-lg">Quản lý khách hàng</span>
        </a>
    </div>
</div>
