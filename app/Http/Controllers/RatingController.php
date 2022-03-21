<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Drivers;
use App\Models\DevicesPositions;
use App\Models\Ratings;
use App\Models\Lessons;

class RatingController extends Controller
{

    public function addRaiting(Request $request){
        $instructor_id = Drivers::where('last_name', $request->input('last_name'))->where('first_name', $request->input('first_name'))->pluck('id');

        $lesson = Lessons::where('lesson_driver', $instructor_id)->where('grade', 0)->first();

        $current_position = DevicesPositions::where('deviceid', $lesson->device_id)->orderBy('devicetime', 'DESC')->first();

        Ratings::create([
            'author_id' => $instructor_id[0],
            'lesson_id' => $lesson->id,
            'rating_score' => $request->input('rating_score'),
            'category_name' => $request->input('category_name'),
        ]);
        return "Created";
    }

    public function getAllRatingsByLesson($id){
        return Ratings::where('lesson_id', $id)->get();
    }

}