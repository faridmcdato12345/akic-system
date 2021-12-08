<?php

namespace App\Http\Controllers;

use App\Models\House;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HouseController extends Controller
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
        return view('admin.houses.index');
    }
    public function getHouse(){
        $query = House::select('id','name');
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
    public function selectHouse(){
        $query = House::select('id','name');
        return datatables($query)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '<button class="select-btn btn btn-info btn-sm action-bttn" value="" id="'.$row->id.'" title="Modify">Select</button>';
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
            'name'=>'required|unique:departments,name'
        ]);
        if ($validator->fails()) {
            return redirect('/house')
                        ->with('toast_error', $validator->messages()->all()[0])
                        ->withInput();
        }
        $data = House::create($request->all());
        return redirect('/house')->with('toast_success','House was created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\House  $house
     * @return \Illuminate\Http\Response
     */
    public function show(House $house)
    {
        return response()->json($house,200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\House  $house
     * @return \Illuminate\Http\Response
     */
    public function edit(House $house)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\House  $house
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, House $house)
    {
        $house->name = $request->name;
        $house->save();
        return response()->json($house,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\House  $house
     * @return \Illuminate\Http\Response
     */
    public function destroy(House $house)
    {
        $house->delete();
        return response()->json($house,200);
    }
}
