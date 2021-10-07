@extends('layout.default')

@section('styles')
@endsection

@section('content')

    <div class="d-flex flex-row">

        @include('admin.setting.nav_config')

        <div class="flex-row-fluid ml-lg-8">
            <div class="card card-custom card-stretch">
                <form class="form" id="updateAccountPayroll" method="POST" action="{{ route('config.setting.update-account-payroll') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        {{ $page_title }}
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
@endsection
