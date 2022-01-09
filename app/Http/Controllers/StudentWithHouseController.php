<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StudentWithHouseController extends Controller
{
    public function index(){
        return view('admin.students.withhouse.index');
    }
    public function getData(){
        $query = Student::has('houses')->select('id','firstname','middlename','lastname','course_id');
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
            ->addColumn('houses', function($row){
                $x = '';
                $s = DB::table('house_student')->where('student_id',$row->id)->get();
                foreach($s as $d){
                    $z = DB::table('houses')->select('id','name','deleted_at')->where('id',$d->house_id)->first();
                    if(is_null($z->deleted_at)){
                        $x .= '<div id="'.$row->id.'" class="house-container" style="display:inline;margin-right:2px;">';
                        $x .= '<label class="badge badge-success">'.$z->name.'</label>';
                        if(Auth::user()->role == 'Admin'){
                            $x .= '<div class="btn-danger close-button" id="'.$z->id.'"><span>x</span></div>';
                        }
                        $x .= '</div>';
                    }
                }
                return $x;
            })
            ->rawColumns(['course','department','houses'])
            ->make(true);
    }
}
