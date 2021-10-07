@foreach($files as $file)
    @include('pages.filemanager.file', ['file' => $file, 'folder'=> $folder])
@endforeach
