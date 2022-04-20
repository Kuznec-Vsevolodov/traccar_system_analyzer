<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Users;
use App\Models\TcPositions;
use App\Models\Reviews;
use App\Models\TripMaster;

class RatingController extends Controller
{

    // public function addRaiting(Request $request){
    //     $instructor_id = Users::where('lastname', $request->input('last_name'))->where('firstname', $request->input('first_name'))->pluck('id');

    //     $booking = TripMaster::where('id', $request->input('booking_id'))->first();

    //     $current_position = TcPositions::where('deviceid', $booking->device_id)->orderBy('devicetime', 'DESC')->first();

    //     $rating = Reviews::create([
    //         'instructor_id' => $instructor_id[0],
    //         'booking_id' => $booking->id,
    //         'rating' => $request->input('rating'),
    //         'review' => $request->input('review'),
    //         'longitude' => $request->input('longitude'),
    //         'latitude' => $request->input('latitude'),
    //         'review_type' => $request->input()
    //     ]);
    //     return $rating;
    // }

    public function getAllReviewsBybooking($id){
        return Reviews::where('booking_id', $id)->get();
    }

}