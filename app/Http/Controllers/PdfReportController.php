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

use PDF;

class PdfReportController extends Controller
{
    public function download($id)
    {
        $lesson = Lessons::where('id', $id)->first();

        $brakes = Brakes::where('lesson_id', $lesson->id)->get();

        $accelerations = Accelerations::where('lesson_id', $lesson->id)->get();

        $turns = WideTurns::where('lesson_id', $lesson->id)->get();

        $instructor = Drivers::where('id', $lesson->lesson_driver)->first();
        $student = Students::where('id', $lesson->lesson_student)->first();


        $pdf = PDF::loadView('pdf.report', compact('lesson', 'student', 'instructor', 'turns', 'brakes', 'accelerations'));

        return $pdf->download('report.pdf');
    }

    public function index(){
        return view('pdf.report');
    }
}
