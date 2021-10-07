<?php
namespace App\Http\Controllers\Files;

use App\Http\Controllers\Controller;
use App\Repositories\FileRepository;
use Illuminate\Http\Request;
use Exception;

class FileController extends Controller
{
    private $file;

    public function __construct(FileRepository $file)
    {
        $this->file = $file;
    }

    protected function _getActiveFolder($request) {
        $folder = $request->get('folder');
        $folders = $this->file->getFolders()->keys();
        if ($folders->contains($folder)) {
            return $folder;
        }

        return $this->file->getPublicFolder();
    }

    public function index(Request $request) {
        $folders = $this->file->getFolders();
        $folder = $this->_getActiveFolder($request);

        return view('pages.filemanager.filemanager', compact('folders', 'folder'));
    }

    public function files(Request $request) {
        $folder = $request->get('folder');
        $search = $request->get('search');
        $files = $this->file->getFiles($folder, $search);
        return view('pages.filemanager.files', compact('files','folder'))->render();
    }

    public function upload(Request $request) {
        $files = $request->file('upload');
        $folder = $request->get('working_dir');
        return $this->file->upload($files, $folder);
    }

    public function download(Request $request) {
        $file = $request->get('file');
        $folder = $request->get('folder');
        return $this->file->download($file, $folder);
    }

    public function delete(Request $request) {
        $file = $request->get('file');
        $folder = $request->get('folder');
        try {
            $this->file->remove($file, $folder);
            return $this->responseSuccess([]);
        } catch (Exception $e) {
            return $this->responseError($e->getMessage());
        }
    }
}
