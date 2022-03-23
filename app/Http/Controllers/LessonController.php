<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Lessons;
use App\Models\TcDevices;
use Carbon\Carbon;

class LessonController extends Controller
{

    public function createLesson(Request $request){
        $device_id = TcDevices::where('name', $request->input('device_name'))->first();

        $lesson = Lessons::create([
            'device_id' => $device_id->id,
            'lesson_start' => $request->input('lesson_start'),
            'lesson_end' => $request->input('lesson_end'),
            'lesson_student' => $request->input('lesson_student'),
            'lesson_driver' => $request->input('lesson_driver'),
        ]);
        return $lesson;
    }

    public function getLessonData($id){
        return Lessons::where('id', $id)->first();
    }

    public function getLessonByTime(Request $request){ 
        $current_time = Carbon::now();
        return Lessons::where('lesson_start' < $current_time)
                ->where('lesson_end' > $current_time)
                ->where('instructor_id', $request->input('instructor_id'))
                ->first();
    
    }
}