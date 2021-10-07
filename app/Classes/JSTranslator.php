<?php
namespace App\Classes;

use Illuminate\Support\Facades\File;

class JSTranslator
{
    protected $items = [];
    
    public function __construct($locale) {
        $this->loadPhpTranslations($locale);
        $this->loadJsonTranslations($locale);
    }
    
    public function loadPhpTranslations($locale) {
        $path = resource_path("lang/$locale");
        $items = collect(File::allFiles($path))->flatMap(function ($file) use ($locale) {
            $key = ($translation = $file->getBasename('.php'));

            return [$key => trans($translation, [], $locale)];
        })->toArray();
        $this->items = array_merge($this->items, $items);
        
    }
    public function loadJsonTranslations($locale) {
        $path = resource_path("lang/$locale.json");
        $items = [];
        if (is_string($path) && is_readable($path)) {
            $items = json_decode(file_get_contents($path), true);
        }
        $this->items = array_merge($this->items, $items);
    }
    
    public function getItems() {
        return $this->items;
    }
}