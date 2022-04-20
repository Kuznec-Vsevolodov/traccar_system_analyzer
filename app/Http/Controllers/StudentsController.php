<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Students;

class StudentsController extends Controller
{

    // public function addStudent(Request $request){
    //     if(Students::where('first_name', $request->input('first_name'))->where('last_name', $request->input('last_name'))->count() > 0){
    //         return 'Already exists';
    //     }else{
    //         $student = Students::create([
    //             'last_name' => $request->input('last_name'),
    //             'first_name' => $request->input('first_name'),
    //         ]);
    //         return $student;
    //     }
    // }

    // public function getStudentsList(){
    //     return Students::all();
    // }

}