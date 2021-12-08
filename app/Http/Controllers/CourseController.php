<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
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
        $departments = Department::all();
        return view('admin.courses.index',compact('departments'));
    }
    public function getCourse(){
        $query = Course::select('id','name','departments_id');    
        return datatables($query)
            ->addIndexColumn()
            ->addColumn('departments', function($row){
                $d = Department::where('id',$row->departments_id)->first();
                return $d->name;
            })
            ->addColumn('action', function($row){
                $btn = '<button class="modify btn btn-info btn-sm action-bttn" value="'.$row->name.'" id="'.$row->id.'" title="Modify"><i class="fas fa-edit modify-button"></i></button>';
                $btn .= '<button class="delete btn btn-danger btn-sm delete-button" value="'.$row->name.'"  id="'.$row->id.'" title="Delete" ><i class="fas fa-trash"></i></button>';
                return $btn;
            })
            ->rawColumns(['action','departments'])
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
            'name'=>'required|unique:courses,name,NULL,id,deleted_at,NULL',
            'departments_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return redirect('/course')
                        ->with('toast_error', $validator->messages()->all()[0])
                        ->withInput();
        }
        $data = Course::create($request->all());
        return redirect('/course')->with('toast_success','Course was created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        return response()->json($course,200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        $course->departments_id = $request->departments_id;
        $course->name = $request->name;
        $course->save();
        return response()->json($course,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        $course->delete();
        return response()->json($course,200);
    }
}
