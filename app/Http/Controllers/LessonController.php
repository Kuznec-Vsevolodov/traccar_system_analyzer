<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Lessons;
use App\Models\DevicesPositions;

class LessonController extends Controller
{

    public function createLesson(Request $request){
        Lessons::create([
            'device_id' => $request->input('device_id'),
            'lesson_start' => $request->input('date_start'),
            'lesson_end' => $request->input('date_end'),
            'lesson_student' => $request->input('student_id'),
            'lesson_driver' => $request->input('driver_id'),
        ]);
        return "Created";
    }

    public function getLessonData($id){
        return Lesson::where('id', $id)->first();
    }
}