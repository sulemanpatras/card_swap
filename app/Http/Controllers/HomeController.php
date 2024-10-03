<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use Illuminate\Support\Facades\Artisan;

class HomeController extends BaseController
{
    public function index()
    {
        Artisan::call('optimize:clear');
        return view('welcome');
    }
}
