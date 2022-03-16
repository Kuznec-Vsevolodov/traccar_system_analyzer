<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Students;

class RequestsController extends Controller
{

    public function index(){
        return view('requests');
    }
}