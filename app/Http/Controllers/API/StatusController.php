<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getAll(){
        return  auth()->user()->statuses;
    }
}
