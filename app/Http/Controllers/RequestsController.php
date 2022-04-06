<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Students;
use App\Models\TcPositions;
use App\Models\Lessons;

class RequestsController extends Controller
{

    public function index($lesson_id){

        $lesson = Lessons::where('id', $lesson_id)->first();
        $positions = TcPositions::where('deviceid', $lesson->device_id)->whereBetween('devicetime', [$lesson->lesson_start, $lesson->lesson_end])->pluck('longtitude', 'latitude');
        echo $positions;
        return view('requests');
    }
}