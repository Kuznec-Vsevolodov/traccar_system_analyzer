<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Users;
use App\Models\TcPositions;
use App\Models\TripMaster;

class CommentsController extends Controller
{

    // public function addComment(Request $request){

    //     $instructor_id = Drivers::where('last_name', $request->input('last_name'))->where('first_name', $request->input('first_name'))->pluck('id');

    //     $lesson = Lessons::where('database_trip_id', $request->input('lesson_id'))->first();

    //     $current_position = TcPositions::where('deviceid', $lesson->device_id)->orderBy('devicetime', 'DESC')->first();

    //     $comment = Comments::create([
    //         'author_id' => $instructor_id[0],
    //         'lesson_id' => $lesson->id,
    //         'longitude' => $current_position->longitude,
    //         'latitude' => $current_position->latitude,
    //         'text' => $request->input('text')
    //     ]);
    //     return $comment;
    // }

    // public function getAllCommentsByLesson($id){
    //     return Comments::where('lesson_id', $id)->get();
    // }

}