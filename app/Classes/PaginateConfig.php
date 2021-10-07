<?php
namespace App\Classes;

use App\Http\Requests\DatatableRequest;
use Illuminate\Http\Request;

class PaginateConfig
{
    protected $page;
    protected $perPage;
    protected $query;
    protected $sortBy;
    protected $sortDir;
    protected $columns = ['*'];
    
    public function __construct($perPage = 10, $page = 1, $query = null, $sortBy = null, $sortDir = 'asc') {
        $this->perPage = $perPage;
        $this->page = $page;
        $this->query = $query;
        $this->sortBy = $sortBy;
        $this->sortDir = $sortDir;
    }
    
    public static function fromDatatable(Request $request) {
        $pagination = $request->get('pagination', []);
        $query = $request->get('query');
        $sort = $request->get('sort');
        $perPage = $pagination['perpage'] ?? 10;
        $page = $pagination['page'] ?? 1;
        $query = $query;
        $sortBy = $sort['field'] ?? 'created_at';
        $sortDir = $sort['sort'] ?? 'desc';
        return new self($perPage, $page, $query, $sortBy, $sortDir);
    }

    public function getPage() {
        return $this->page;
    }

    public function getPerPage() {
        return $this->perPage;
    }

    public function getFullTextQuery() {
        return $this->query;
    }

    public function getSortColumn() {
        return $this->sortBy;
    }

    public function getSortDir() {
        return $this->sortDir;
    }

    public function getColumns() {
        return $this->columns;
    }

    public function setSortBy($column) {
        $this->sortBy = $column;
        return $this;
    }
}
