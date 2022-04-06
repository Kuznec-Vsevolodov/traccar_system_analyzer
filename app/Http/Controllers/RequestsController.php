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
        echo $lesson->lesson_start;
        echo $lesson->lesson_end;
        $positions = TcPositions::where('deviceid', $lesson->device_id)->whereBetween('devicetime', [$lesson->lesson_start, $lesson->lesson_end])->pluck('longitude', 'latitude');
        return $positions;
        // return view('requests');
    }
}