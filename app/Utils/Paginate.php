<?php


namespace App\Utils;


use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class Paginate extends LengthAwarePaginator
{
    public function __construct($items, $total, $perPage)
    {
        $currentPage = Paginator::resolveCurrentPage();
        $options = [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ];
        parent::__construct($items,$total,$perPage,$currentPage,$options);
    }
}