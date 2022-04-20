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

        $lesson = Lessons::where('database_trip_id', $lesson_id)->first();
        $positions = TcPositions::where('deviceid', $lesson->device_id)->whereBetween('devicetime', [$lesson->lesson_start, $lesson->lesson_end])->get(['longitude', 'latitude']);;
        $total_quantity = (count($positions)-count($positions)%100)/100;
        $final_array = [];
        $final_array[] = $positions[0];
        for($i = 1; $i <= $total_quantity; $i++){
            $final_array[] = $positions[100*$i];
        }
        $final_array[] = $positions[count($positions)-1];
        $final_str = '';
        foreach($final_array as $position){
            $final_str = $final_str.$position['latitude'].','.$position['longitude'].'|';
        }
        return view('requests')->with('positions_string', substr_replace ($final_str, "", -1));
    }
}    