<?php
namespace App\Repositories;

use App\Exceptions\FileNotFoundException;
use App\Models\Department;
use App\Models\FileManager;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use UniSharp\LaravelFilemanager\Events\ImageIsDeleting;
use UniSharp\LaravelFilemanager\Events\ImageWasDeleted;
use UniSharp\LaravelFilemanager\Lfm;
use UniSharp\LaravelFilemanager\LfmPath;

class FileRepository extends Repository
{
    private $lfm;
    private $lfmPath;

    protected static $success_response = 'OK';

    public function __construct()
    {
        parent::__construct();
        $this->lfm = new Lfm(app('config'), app('request'));
        $this->lfmPath = new LfmPath($this->lfm);
    }

    public function getModel(): string
    {
        return FileManager::class;
    }

    public function getFolders() {
        $company_id = app('hrm')->getCompanyId();
        $getNameFile = Department::where('company_id', $company_id)->get()->pluck('department_name','slug');
        return $getNameFile;
    }

    public function getRootFolder() {
        $settingId = app('hrm')->getCompanyId();
        return $settingId;
    }

    public function setFolder($folder) {
        $root_folder = $this->getRootFolder();
        $this->lfmPath->dir('/'.$root_folder.'/'.$folder);
    }

    public function getPublicFolder() {
        foreach($this->getFolders() as $folderSlug => $folderName) {
            return $folderSlug;
        }
    }

    public function upload($files, $folder)
    {
        $uploaded_files = $files;
        $error_bag = [];
        $new_filename = null;
        $this->setFolder($folder);

        foreach (is_array($uploaded_files) ? $uploaded_files : [$uploaded_files] as $file) {
            try {
                $new_filename = $this->lfmPath->upload($file);
            } catch (\Exception $e) {
                Log::error($e->getMessage(), [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]);
                array_push($error_bag, $e->getMessage());
            }
        }

        if (is_array($uploaded_files)) {
            $response = count($error_bag) > 0 ? $error_bag : self::$success_response;
        } else { // upload via ckeditor5 expects json responses
            if (is_null($new_filename)) {
                $response = ['error' =>
                    [
                        'message' =>  $error_bag[0]
                    ]
                ];
            } else {
                $url = $this->lfmPath->setName($new_filename)->url();

                $response = [
                    'url' => $url
                ];
            }
        }

        return response()->json($response);
    }

    public function getFiles($folder, $search = "")
    {
        $this->setFolder($folder);
        $items = collect($this->lfmPath->files());

        if ($search) {
            $items = $items->filter(function ($item) use($search) {
                if (Str::contains($item->name, $search)) {
                    return true;
                }
                return false;
            });
        }
        $items->map(function ($item) use($search) {
            return $item->fill()->attributes;
        });
        return $items;
    }

    public function download($file, $folder) {
        $this->setFolder($folder);
        return response()->download($this->lfmPath->setName($file)->path('absolute'));
    }

    public function remove($file, $folder) {
        $this->setFolder($folder);
        $file_to_delete = $this->lfmPath->pretty($file);
        $file_path = $file_to_delete->path();

        event(new ImageIsDeleting($file_path));

        if (is_null($file)) {
            throw new FileNotFoundException();
        }

        if (! $this->lfmPath->setName($file)->exists()) {
            throw new FileNotFoundException();
        }

        if ($file_to_delete->isImage()) {
            $this->lfmPath->setName($file)->thumb()->delete();
        }

        $this->lfmPath->setName($file)->thumb(false)->delete();

        event(new ImageWasDeleted($file_path));

        return true;
    }

    private function getCurrentPageFromRequest()
    {
        $currentPage = (int) request()->get('page', 1);
        $currentPage = $currentPage < 1 ? 1 : $currentPage;

        return $currentPage;
    }

    private function getPaginationPerPage()
    {
        return config("lfm.paginator.perPage", 30);
    }
}
