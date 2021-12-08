<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class RemoveHouseStudentController extends Controller
{
    public function removeHouse(Student $student,Request $request){
        $query = $student->houses()->detach($request->house_id);
        return response()->json($query,201);
    }
}
