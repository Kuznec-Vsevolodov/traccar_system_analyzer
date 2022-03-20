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

        $lesson = Lessons::where('lesson_driver', $instructor_id)->where('grade', 0)->first();

        $current_position = DevicesPositions::where('deviceid', $lesson->device_id)->orderBy('devicetime', 'DESC')->first();

        Comments::create([
            'author_id' => $instructor_id[0],
            'lesson_id' => $lesson->id,
            'longitude' => $current_position->longitude,
            'latitude' => $current_position->latitude,
            'text' => $request->input('text')
        ]);
        return "Created";
    }

    public function getAllCommentsByLesson($id){
        return Comments::where('lesson_id', $id)->get();
    }

}