<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use App\Models\House;
use App\Models\Course;
use App\Models\Student;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
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
        $courses = Course::all();
        $house = House::all();
        if($house->isEmpty()){
            alert()->html('<b>No House is created</b>',"
            Create <b>HOUSE</b> first,
            click <a href='{{route('home')}}'>links</a>
            to create <b>HOUSE</b>
            ",'info');
            return redirect()->route('house.index');
        }
        return view('admin.students.all.index',compact('courses'));
    }
    /** 
     * @var House $house
    */
    public function withoutHouse(){
        return view('admin.students.withouthouse.index'); 
    }
    public function getStudentWithoutHouse(){
        $query = Student::doesntHave('houses')->select('id','firstname','middlename','lastname','course_id');
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
            ->addColumn('action', function($row){
                $btn = '<button class="house btn btn-warning btn-sm action-bttn" value=""  id="'.$row->id.'" title="Add house" ><i class="fas fa-house-user"></i></button>';
                return $btn;
            })
            ->rawColumns(['action','course','department'])
            ->make(true);
    }
    public function getStudent(){
        $query = Student::select('id','firstname','middlename','lastname','course_id');    
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
            ->addColumn('action', function($row){
                $btn = '';
                $btn .= '<button class="house btn btn-warning btn-sm action-bttn" value=""  id="'.$row->id.'" title="Add house" ><i class="fas fa-house-user"></i></button>';
                if(Auth::user()->role === "Adviser" || Auth::user()->role === "Admin"){
                    $btn .= '<button class="modify btn btn-info btn-sm action-bttn" value="'.$row->name.'" id="'.$row->id.'" title="Modify"><i class="fas fa-edit modify-button"></i></button>';
                    $btn .= '<button class="fine btn btn-success btn-sm action-bttn" value=""  id="'.$row->id.'" title="Add fine" ><i class="fas fa-money-bill-wave"></i></button>';
                    $btn .= '<button class="delete btn btn-danger btn-sm delete-button" value="'.$row->name.'"  id="'.$row->id.'" title="Delete" ><i class="fas fa-trash"></i></button>';
                }
                return $btn;
            })
            ->rawColumns(['action','course','department'])
            ->make(true);
    }
    public function selectStudent(){
        $query = Student::select('id','firstname','middlename','lastname','course_id');    
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
            ->addColumn('action', function($row){
                $btn = '<button class="select-btn btn btn-info btn-sm action-bttn" value="'.$row->name.'" id="'.$row->id.'" title="Modify">Select</button>';
                return $btn;
            })
            ->rawColumns(['action','course','department'])
            ->make(true);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname'=>'required|string',
            'middlename'=>'nullable|string',
            'lastname' => 'nullable|string',
            'course_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return redirect('/student')
                        ->with('toast_error', $validator->messages()->all()[0])
                        ->withInput();
        }
        $data = Student::create($request->all());
        return redirect('/student')->with('toast_success','Student was added');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        return response()->json($student,200);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        $data = $student->update($request->all());
        return response()->json($data,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        $student->delete();
        return response()->json($student,200);
    }

    public function attachHouse(Student $student,Request $request){
        $student->houses()->attach($request->house_id);
        $house = House::where('id',$request->house_id)->get();
        return response()->json($house,201);
    }
    public function attachFine(Student $student,Request $request){
        $student->fines()->attach($request->fine_id);
        $fine = Fine::where('id',$request->fine_id)->get();
        return response()->json($fine,201);
    }
}
