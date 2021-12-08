<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Officer;
use App\Models\Student;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OfficerController extends Controller
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
        $students = Student::all();
        if($students->isEmpty()){
            alert()->html('<b>No student was registered</b>',"
            Please register a <b>STUDENT</b> first,
            ",'info');
            return redirect()->route('student.index');
        }
        return view('admin.officers.index');
    }
    public function getOfficer(){
        $query = Officer::select('id','students_id');    
        return datatables($query)
            ->addIndexColumn()
            ->editColumn('students_id', function($row){
                $s = Student::where('id',$row->students_id)->first();
                return $s->firstname . ' ' . $s->middlename . ' ' . $s->lastname ;
            })
            ->addColumn('course',function($row){
                $student = Student::select('id','course_id')->where('id',$row->students_id)->first();
                $course = Course::select('id','name')->where('id',$student->course_id)->first();
                return $course->name;
            })
            ->addColumn('department',function($row){
                $student = Student::select('id','course_id')->where('id',$row->students_id)->first();
                $deptId = Course::select('id','departments_id')->where('id',$student->course_id)->first();
                $department = Department::select('id','name')->where('id',$deptId->departments_id)->first();
                return $department->name;
            })
            ->addColumn('action', function($row){
                $btn = '<button class="delete btn btn-danger btn-sm delete-button" value="'.$row->name.'"  id="'.$row->id.'" title="Delete" ><i class="fas fa-trash"></i></button>';
                return $btn;
            })
            ->rawColumns(['action','course','department'])
            ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'students_id'=>'required|unique:officers,students_id,NULL,students_id,deleted_at,NULL',
        ]);
        if ($validator->fails()) {
            return response()->json(['message'=>$validator->messages()->all()[0]],409);
        }
        $data = Officer::create($request->all());
        return response()->json($data,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Officer  $officer
     * @return \Illuminate\Http\Response
     */
    public function show(Officer $officer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Officer  $officer
     * @return \Illuminate\Http\Response
     */
    public function edit(Officer $officer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Officer  $officer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Officer $officer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Officer  $officer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Officer $officer)
    {
        $officer->delete();
        return response()->json($officer,200);
    }
}
