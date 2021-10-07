<?php
namespace App\Traits;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

trait DatatableResponseable
{
    public function makeDatatableResponse(LengthAwarePaginator $paginator, $sort = null, $sortDir = 'asc') {
        $response = [
            'data' => $paginator->items(),
            'meta' => [
                'page' => $paginator->currentPage(),
                'pages' => $paginator->lastPage(),
                'perpage' => $paginator->perPage(),
                'total' => $paginator->total(),
                'field' => $sort,
                'sort' => $sortDir
            ]
        ];
        return response()->json($response);
    }
}
