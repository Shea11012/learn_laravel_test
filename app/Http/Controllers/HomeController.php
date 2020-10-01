<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
	public function home(): string
    {
		return DB::getDatabaseName();
	}
}
