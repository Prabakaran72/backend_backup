<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Models\CompetitorDetailsBranches;
use Illuminate\Http\Request;
use App\Models\Token;

class CompetitorDetailsBranchesController extends Controller
{
    public function index()
    {
        $branch = CompetitorDetailsBranches::where('id', '!=', '')
            ->select('id', 'compNo', 'country', 'state', 'district','city')
            ->orderBy('id')
            ->get();

        if ($branch)
            return response()->json([
                'status' => 200,
                'branch' => $branch
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

      public function store(Request $request)
    {
        
        // echo "token ID:" . $curr_token=$request->tokenId;
        $user = Token::where("tokenid", $request->tokenId)->first();
        //We doesn't have user id in $request, so we get by using tokenId, then add Userid to $request Insert into table directly without assigning variables       
        $request->request->add(['cr_userid' => $user['userid']]);
        //Here is no need of token id when insert $request into table, so remove it form $request
        $request->request->remove('tokenId');

        $existence = CompetitorDetailsBranches::where("compNo", $request->compNo)
            ->where("compId", $request->compId)
            ->where("country", $request->country)
            ->where("state", $request->state)
            ->where("district", $request->district)
            ->where("city", $request->city)->exists();
            
        
        if ($existence) {
            return response()->json([
                'status' => 404,
                'message' => 'Already Exists!'
            ]);
        }

        $validator = Validator::make($request->all(), ['compNo' => 'required|string','country'=>'required|integer', 'state'=>'required|integer','district'=>'required|integer','city'=>'required|integer','cr_userid'=>'required|integer'
    ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 404,
                'message' =>"Not able to Add this Brnach now..!",
            ]);
        }

        $branchRes = CompetitorDetailsBranches::firstOrCreate($request->all());
        if ($branchRes) {
            return response()->json([
                'status' => 200,
                'message' => 'Created Succssfully!',
            ]);
        }
    }

    public function show($id)
    {
        $branch = CompetitorDetailsBranches::find($id);
        if ($branch)
            return response()->json([
                'status' => 200,
                'branch' => $branch
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

     
    public function edit(CompetitorDetailsBranches $competitorDetailsBranches)
    {
        //
    }

    
    public function update(Request $request, CompetitorDetailsBranches $competitorDetailsBranches)
    {
        //
    }

    
    public function destroy($id)
    {
        try{
            $branch = CompetitorDetailsBranches::destroy($id);
            if($branch)    
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
   
}
