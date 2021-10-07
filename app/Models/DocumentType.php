<?php
namespace App\Models;

class DocumentType extends CacheModel
{
    protected $table = 'document_type';
    protected $primaryKey = 'document_type_id';
    protected $guarded = [];
}
