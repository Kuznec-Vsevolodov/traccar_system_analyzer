<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TripMaster;
use App\Models\TcDevices;
use Carbon\Carbon;

class LessonController extends Controller
{

    // public function createLesson(Request $request){
    //     $device_id = TcDevices::where('name', $request->input('device_name'))->first();

    //     $lesson = TripMaster::create([
    //         'device_id' => $device_id->id,
    //         'lesson_start' => $request->input('lesson_start'),
    //         'lesson_end' => $request->input('lesson_end'),
    //         'lesson_student' => $request->input('lesson_student'),
    //         'lesson_driver' => $request->input('lesson_driver'),
    //         'database_trip_id' => $request->input('database_trip_id')
    //     ]);
    //     return $lesson;
    // }

    public function getLessonData($id){
        return TripMaster::where('id', $id)->first();
    }

    public function getLessonByTime(Request $request){ 
        $current_time = Carbon::now();

        return TripMaster::where('instructor_id', $request->input('instructor_id'))->where('start_time', '<', $current_time)->where('end_time', '>', $current_time)->first();
    
    }
}