<?php

use Illuminate\Pagination\Paginator;

function csPaginate($items, $total, $perPage = 15) {
    $currentPage = Paginator::resolveCurrentPage();
    $options = [
        'path' => Paginator::resolveCurrentPath(),
        'pageName' => 'page',
    ];

    return app()->makeWith(\Illuminate\Pagination\LengthAwarePaginator::class,compact(
       'items','total','perPage','currentPage','options'
    ));
}