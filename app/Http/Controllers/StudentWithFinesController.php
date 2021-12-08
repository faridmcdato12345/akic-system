<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentWithFinesController extends Controller
{
    public function index(){
        return view('admin.students.withfines.index');
    }
    public function getData(){
        $query = Student::has('fines')->select('id','firstname','middlename','lastname','course_id');
        return datatables($query)
            ->addIndexColumn()
            ->editColumn('firstname',function($row){
                return $row->firstname . ' ' . $row->middlename . ' ' . $row->lastname;
            })
            ->addColumn('course',function($row){
                $course = Course::select('name')->where('id',$row->course_id)->first();
                return $course->name;
            })
            ->addColumn('department',function($row){
                $deptId = Course::select('id','departments_id')->where('id',$row->course_id)->first();
                $department = Department::select('id','name')->where('id',$deptId->departments_id)->first();
                return $department->name;
            })
            ->addColumn('fines', function($row){
                $x = '';
                $s = DB::table('fine_student')->where('student_id',$row->id)->get();
                foreach($s as $d){
                    $z = DB::table('fines')->select('id','name')->where('id',$d->fine_id)->first();
                    $x .= '<div id="'.$row->id.'" class="fine-container">';
                    $x .= '<label class="badge badge-success">'.$z->name.'</label>';
                    $x .= '<div class="btn-danger close-button" id="'.$z->id.'"><span>x</span></div>';
                    $x .= '</div>';
                }
                return $x;
            })
            ->rawColumns(['course','department','fines'])
            ->make(true);
    }
}
