<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Lessons;
use App\Models\Students;
use App\Models\Drivers;
use App\Models\Brakes;
use App\Models\Accelerations;
use App\Models\WideTurns;
use App\Models\TcPositions;
use App\Models\FunFacts;
use App\Http\Controllers\TraccarController;

use PDF;

class PdfReportController extends Controller
{
    public function download($id)
    {
        $lesson = Lessons::where('database_trip_id', $id)->first(); 

        $instructor = Drivers::where('id', $lesson->lesson_driver)->first();
        $student = Students::where('id', $lesson->lesson_student)->first();

        $positions = TcPositions::where('deviceid', $lesson->device_id)->whereBetween('devicetime', [$lesson->lesson_start, $lesson->lesson_end])->get(['longitude', 'latitude']);;
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

        $pdf = PDF::loadView('pdf.report', compact('lesson', 'student', 'instructor', 'final_str', 'distance', 'fun_fact'));

        return $pdf->download('report.pdf');
        // return view('pdf.report')->with(['lesson' => $lesson, 'final_str'=>$final_str, 'student'=>$student, 'instructor'=>$instructor, 'distance'=>$distance]);
    }

    public function index(){
        return view('pdf.report');
    }
}
