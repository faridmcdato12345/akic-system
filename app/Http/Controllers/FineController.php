<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use App\Models\House;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FineController extends Controller
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
            return redirect()->route('fine.index');
        }
        return view('admin.fines.index',compact('courses'));
    }
    public function getFine(){
        $query = Fine::select('id','name','amount');    
        return datatables($query)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '<button class="modify btn btn-info btn-sm action-bttn" value="'.$row->name.'" id="'.$row->id.'" title="Modify"><i class="fas fa-edit modify-button"></i></button>';
                $btn .= '<button class="delete btn btn-danger btn-sm delete-button" value="'.$row->name.'"  id="'.$row->id.'" title="Delete" ><i class="fas fa-trash"></i></button>';
                
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function selectFine(){
        $query = Fine::select('id','name','amount');
        return datatables($query)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '<button class="select-fine-btn btn btn-info btn-sm action-bttn" value="" id="'.$row->id.'" title="Modify">Select</button>';
                return $btn;
            })
            ->rawColumns(['action'])
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
            'name'=>'required|string|unique:fines,name,NULL,id,deleted_at,NULL',
            'amount' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect('/fine')
                        ->with('toast_error', $validator->messages()->all()[0])
                        ->withInput();
        }
        $data = Fine::create($request->all());
        return redirect('/fine')->with('toast_success','Fine was added');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Fine  $fine
     * @return \Illuminate\Http\Response
     */
    public function show(Fine $fine)
    {
        return response()->json($fine,200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Fine  $fine
     * @return \Illuminate\Http\Response
     */
    public function edit(Fine $fine)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fine  $fine
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fine $fine)
    {
        $fine->update($request->all());
        return response()->json($fine,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fine  $fine
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fine $fine)
    {
        $fine->delete();
        return response()->json($fine,200);
    }
}
