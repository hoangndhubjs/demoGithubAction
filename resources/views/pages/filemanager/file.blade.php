<div class="card-file rounded mb-3" data-title="{{ $file->name }}">
    <div class="file-toolbar">
        <div class="d-flex justify-content-end">
            <div class="dropdown dropdown-inline">
                <a href="#" class="btn btn-clean btn-hover-bg-white btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="ki ki-bold-more-hor"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right" style="">
                    <ul class="navi navi-hover">
                        <li class="navi-item">
                            <a href="{{ route('files.download', ['folder' => $folder, 'file' => $file->name]) }}" class="navi-link">
                                <span class="navi-icon">
                                    <x-icon type="svg" category="Files" icon="Cloud-download" class="svg-icon-primary"/>
                                </span>
                                <span class="navi-text">{{ __("download") }}</span>
                            </a>
                        </li>
                        <li class="navi-item">
                            <a href="#" class="navi-link file-remove" data-folder='{{ $folder }}' data-name="{{ $file->name }}" data-url="{{ route('files.ajax.delete') }}">
                                <span class="navi-icon">
                                    <x-icon type="svg" category="General" icon="Trash" class="svg-icon-danger"/>
                                </span>
                                <span class="navi-text">{{ __('remove') }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="d-flex align-items-center justify-content-center">
            @if($file->isImage())
                <div class="image-container" style="background-image: url('{{ $file->thumbUrl() }}')">
                </div>
            @else
                <div class="mime-icon ico-{{ $file->icon }}"><div class="ico"></div></div>
            @endif
        </div>
        <div class="file-link text-center text-ellipsis mb-5 pl-5 pr-5">
            {{ $file->name }}
        </div>
    </div>
</div>
