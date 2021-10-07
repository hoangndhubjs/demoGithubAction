<div class="btn btn-icon w-auto btn-clean d-flex align-items-center btn-lg px-2" @if($useOffcanvas) id="kt_quick_user_toggle" @endif>
    <!--span class="text-muted font-weight-bold font-size-base d-none d-md-inline mr-1">Hi,</span-->
    <span class="text-dark-50 font-weight-bolder font-size-base d-none d-md-inline mr-3">{{ \Illuminate\Support\Facades\Auth::user()->getFullname() }}</span>
    <span class="symbol symbol-35 symbol-light-success">
        <img src="{{ \Illuminate\Support\Facades\Auth::user()->profile_picture }}" class="profile_avatar"/>
{{--        <span class="symbol-label font-size-h5 font-weight-bold">{{\Illuminate\Support\Facades\Auth::user()->getFirstCharacter()}}</span>--}}
    </span>
</div>
