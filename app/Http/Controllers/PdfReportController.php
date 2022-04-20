<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TripMaster;
use App\Models\Users;
use App\Models\Brakes;
use App\Models\Accelerations;
use App\Models\WideTurns;
use App\Models\TcPositions;
use App\Models\FunFacts;
use App\Models\Reviews;
use App\Http\Controllers\TraccarController;

use PDF;

class PdfReportController extends Controller
{
    public function download($id)
    {
        $lesson = TripMaster::where('id', $id)->first(); 

        $instructor = Users::where('id', $lesson->instructor_id)->first();
        $student = Users::where('id', $lesson->student_id)->first();

        $positions = TcPositions::where('deviceid', $lesson->device_id)->whereBetween('devicetime', [$lesson->start_time, $lesson->end_time])->get(['longitude', 'latitude']);
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
        $final_str = substr_replace ($final_str, "", -1);

        $distance = TraccarController::getFullDistance($id);

        $fun_fact = FunFacts::orderByRaw("RAND()")->pluck('text');

        $reviews = Reviews::where('booking_id' == $lesson->booking_id)->get();

        $pdf = PDF::loadView('pdf.report', compact('lesson', 'student', 'instructor', 'final_str', 'distance', 'fun_fact', 'reviews'));

        return $pdf->download('report.pdf');
        // return view('pdf.report')->with(['lesson' => $lesson, 'final_str'=>$final_str, 'student'=>$student, 'instructor'=>$instructor, 'distance'=>$distance]);
    }

    public function index(){
        return view('pdf.report');
    }
}
