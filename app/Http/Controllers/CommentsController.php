<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Drivers;
use App\Models\DevicesPositions;
use App\Models\Comments;
use App\Models\Lessons;

class CommentsController extends Controller
{

    public function addComment(Request $request){
        $instructor_id = Drivers::where('last_name', $request->input('last_name'))->where('first_name', $request->input('first_name'))->pluck('id');

        $lesson = Lessons::where('driver_id', $instructor_id)->where('grade', 0)->get();

        $current_position = DevicesPositions::where('device_id', $lesson->device_id)->orderBy('created_at', 'DESC')->get();

        Comments::create([
            'author_id' => $instructor_id,
            'lesson_id' => $lesson->id,
            'longtitude' => $current_position->longtitude,
            'latitude' => $current_position->latitude,
            'text' => $request->input('text_name')
        ]);
        return "Created";
    }

    public function getAllCommentsByLesson($id){
        return Comments::where('lesson_id', $id)->get();
    }

}