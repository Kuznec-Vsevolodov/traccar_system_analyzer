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
            'lesson_start' => $request->input('lesson_start'),
            'lesson_end' => $request->input('lesson_end'),
            'lesson_student' => $request->input('lesson_student'),
            'lesson_driver' => $request->input('lesson_driver'),
        ]);
        return "Created";
    }

    public function getLessonData($id){
        return Lesson::where('id', $id)->first();
    }
}