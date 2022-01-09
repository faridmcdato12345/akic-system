<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\StudentPayment;
use Illuminate\Support\Facades\DB;

class StudentPaymentController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        // Your constructor code here..
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.payments.index');
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
                    $x .= '<div id="role-container" class="'.$row->id.'" style="display:inline-block;margin-right:2px;">';
                    $x .= '<label class="badge badge-success">'.$z->name.'</label>';
                    $x .= '</div>';
                }
                return $x;
            })
            ->addColumn('action',function($row){
                $btn = '<button class="select-btn btn btn-info btn-sm action-bttn" value="'.$row->name.'" id="'.$row->id.'" title="Modify">Pay</button>';
                return $btn;
            })
            ->rawColumns(['course','department','fines','action'])
            ->make(true);
    }
    public function showFines(Student $student){
        $query = Student::with('fines')->where('id',$student->id)->get();
        
        foreach ($query as $q) {
            $tr = '';
            foreach($q->fines as $fines){
                $tr .= '<tr>';
                $tr .= '<td value="'.$fines->id.'" class="fine-name">'.$fines->name.'</td>';
                $tr .= '<td value="'.$fines->id.'" class="fine-amount">'.$fines->amount.'</td>';
                $tr .= '<td value="'.$fines->id.'" class="fine-action"><input type="checkbox" id="fine-'.$fines->id.'" name="fines" value="'.$fines->id.'"></td>';
                $tr .= '</tr>';
            }
        }
        return response()->json(['data' => $query, 'tr' => $tr, 'student_id' => $student->id],200);
    }
    public function payFines(Student $student,Request $request){
        $query = $student->fines()->detach($request->fines_id);
        return response()->json($query,201);
    }
}
