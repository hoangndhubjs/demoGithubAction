@extends('layout.default')

@section('content')
    <div class="card card-custom">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <ul class="nav flex-column nav-pills folders">
                        @foreach($folders as $folder_slug => $folder_name)
                            <li class="nav-item folder">
                                <a class="nav-link folder-item {{ $folder_slug === $folder ? 'active' : '' }} cursor-pointer" data-toggle="tab" data-folder="{{ $folder_slug }}">
                                    <span class="nav-icon">
                                        <i class="flaticon2-folder"></i>
                                    </span>
                                    <span class="nav-text">{{ $folder_name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-md-9 file-wrapper">
                    <div class="files-filter text-right mb-7">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-sm input-group-solid" style="max-width: 175px">
                                    <input type="text" class="form-control" id="file_search" placeholder="{{ __('search') }}...">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <span class="svg-icon">
                                                <x-icon type="svg" category="General" icon="Search"/>
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <a href="#" class="btn btn-light-primary px-6 font-weight-bold" data-toggle="modal" data-target="#uploadForm">
                                    <x-icon type="svg" category="Files" icon="Uploaded-file"/> {{ __("upload") }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card card-custom card-stretch shadow-none bg-gray-200 files-area">
                        <div class="card-body d-flex flex-wrap align-items-start justify-content-start">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="uploadForm" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __("upload_files") }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="attachments">
                        <div class="dropzone dropzone-default dropzone-primary" id="upload-dropzone">
                            <div class="dropzone-msg dz-message needsclick">
                                <input type="hidden" name="working_dir" id="working_dir" value="/{{ $folder }}">
                                <h3 class="dropzone-msg-title">{{ __('drop_or_click_to_upload') }}</h3>
                                <span class="dropzone-msg-desc">{{ __('only_accepted_files') }}</span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('vendor/laravel-filemanager/css/mime-icons.min.css') }}">
    <style>
        .file-wrapper {
            display: flex;
            align-items: stretch !important;
            flex-direction: column;
        }
        .card-file {
            width: 140px;
            transition: ease 200ms;
        }
        .card-file .file-toolbar {
            opacity: 0;
        }
        .card-file .file-toolbar .svg-icon {
            margin-right: 0px;
        }
        .card-file:hover {
            background: #fff;
        }
        .card-file:hover .file-toolbar {
            opacity: 1;
        }
        .text-ellipsis {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .image-container {
            margin: 5px 0px;
            width: 95px;
            height: 110px;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
        }
    </style>
@endsection

@section('scripts')
    <script>
        window._valid_mime = {!! collect(config('constants.valid_mime'))->toJson() !!};
        window.list_file_url = '{{ route("files.ajax.list") }}';
        window.upload_file_url = '{{ route("files.ajax.upload") }}';
    </script>
    <script src="{{ mix('js/files/filemanager.js') }}"></script>
@endsection
