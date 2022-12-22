<?php

namespace App\Http\Controllers;

use App\Models\StateMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\DB;

class StateMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $state = StateMaster::orderBy('created_at', 'desc')->get();
        //->paginate(4)->appends(['sort'=>'ulb_name']);
        $state = DB::table('state_masters')
        ->join('country_masters','country_masters.id','=','state_masters.country_id')
        ->where('country_masters.country_status','=','Active')
        ->select('country_masters.*','state_masters.*') 
        ->orderBy('country_masters.country_name', 'asc')
        ->orderBy('state_masters.state_name', 'asc')       
        ->get();
    
        if ($state)
            return response()->json([
                'status' => 200,
                'state' => $state
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return "Create Function";
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $ulbRes = StateMaster::where('state_name', '=', $request->state_name)
        ->where('country_id', '=', $request->country_id)
        ->exists();
        if ($ulbRes) {
            return response()->json([
                'status' => 400,
                'errors' => 'State Name Already Exists'
            ]);
        }
        
        $validator = Validator::make($request->all(), ['state_name' => 'required|string', 'state_status' => 'required', 'country_id' => 'required', 'state_code' => 'required']);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }

        $stateObj = StateMaster::firstOrCreate($request->all());
        if ($stateObj) {
            return response()->json([
                'status' => 200,
                'message' => 'State Has created Succssfully!'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StateMaster  $stateMaster
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $state = StateMaster::find($id);
      
        if ($state)
            return response()->json([
                'status' => 200,
                'state' => $state
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StateMaster  $stateMaster
     * @return \Illuminate\Http\Response
     */
    public function edit(StateMaster $stateMaster)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StateMaster  $stateMaster
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $ulbRes = StateMaster::where('state_name', '=', $request->state_name)
        ->where('id', '!=', $id)
        ->where('country_id', '=', $request->country_id)
        ->exists();
        if ($ulbRes) {
            return response()->json([
                'status' => 400,
                'errors' => 'State Name Already Exists'
            ]);
        }
        
        $validator = Validator::make($request->all(), 
        ['state_name' => 'required|string',
        'country_id' => 'required' ,
        'state_status' => 'required',
        'state_code' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }

        
        
        $state = StateMaster::findOrFail($id)->update($request->all());
        if ($state)
            return response()->json([
                'status' => 200,
                'message' => "Updated Successfully!"
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StateMaster  $stateMaster
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        try{
            $state = StateMaster::destroy($id);
            if($state)    
            {
                return response()->json([
                'status' => 200,
                'message' => "Deleted Successfully!",
            ]);}
            else
            {return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect!?',
                "errormessage" => "",
            ]);}
        }catch(\Illuminate\Database\QueryException $ex){
            $error = $ex->getMessage(); 
            
            return response()->json([
                'status' => 404,
                'message' => 'Unable to delete! This data is used in another file/form/table.',
                "errormessage" => $error,
            ]);
        }
    }

    public function getStateList($countryId){
        
        $states = StateMaster::where("country_id",$countryId)->where("state_status", "=", "Active")->get();
        //dd($states);
        $stateList = array();
        foreach($states as $state){
            $stateList[] = ["value" => $state['id'], "label" =>  $state['state_name']] ;
        }
        return  response()->json([
            'stateList' =>  $stateList
        ]);
    }

    public function getStateListOptions( $countryId, $category, $savedstate){
        if($category === "state"){$cat = "State";}
        if($category === "unionterritory"){$cat = "Union Territory";}

        DB::enableQueryLog(); 
        $states = StateMaster::where("country_id",$countryId)
        ->where("category",$cat)
        ->whereIn("country_id",function($query){
            $query->select('id')
            ->from('country_masters')
            ->where('country_status',"Active");
        }) 
        ->where("state_status", "=", "Active")
        ->orWhere("id", function($query) use ($countryId, $savedstate){
            $query->select('id')
            ->from('state_masters')
            ->where('id',$savedstate)
            ->where('country_id',$countryId);
        })
        ->get();
        //dd($states);

        $sqlquery = DB::getQueryLog();
        
        $query = str_replace(array('?'), array('\'%s\''),  $sqlquery[0]['query']);
        $query = vsprintf($query, $sqlquery[0]['bindings']);

        $stateList = array();
        foreach($states as $state){
            $stateList[] = ["value" => $state['id'], "label" =>  $state['state_name'], 'state_code' => $state['state_code'] ] ;
        }

        return  response()->json([
            'stateList' =>  $stateList,
            'sqlquery' => $query,
        ]);
    }

    
}
