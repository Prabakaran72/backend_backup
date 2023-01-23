<?php

namespace App\Http\Controllers;

use App\Models\BidCreation_Creation;
use App\Models\BidCreation_Creation_Docs;
use App\Models\TenderCreation;
use App\Models\BidmanagementPreBidQueries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Token;
use App\Models\StateMaster;
use App\Models\CustomerCreationProfile;
use Illuminate\Support\Facades\File;


class BidCreationCreationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // $bidCreation = DB::table('bid_creation__creations')->select('*')->orderBy('id', 'DESC')->get();

       
        $tenderCreation = DB::table('tender_creations')
        ->leftjoin('bid_creation__creations','tender_creations.id','bid_creation__creations.tendercreation')
        ->join('customer_creation_profiles','tender_creations.customername','customer_creation_profiles.id')
        ->select('tender_creations.id AS tenderid', 'bid_creation__creations.id AS bidid', 'tender_creations.nitdate', 'tender_creations.customername', 'bid_creation__creations.quality', 'bid_creation__creations.unit', 'bid_creation__creations.submissiondate', 'customer_creation_profiles.customer_name')
        ->orderBy('tender_creations.nitdate', 'DESC')
        ->get();
          

        return response()->json([
            'tenderCreationList' =>   $tenderCreation,
            'bidcreationList' => []
        ]);


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
        //
         //get the user id 
         $user = Token::where('tokenid', $request->tokenid)->first();   
         $userid = $user['userid'];

         if($userid){
            $bidCreation = new BidCreation_Creation;
            $bidCreation -> bidno = $request->bidcreationData['bidno'];
            $bidCreation -> customername = $request->bidcreationData['customername'];
            $bidCreation -> bidcall = $request->bidcreationData['bidcall'];
            $bidCreation -> tenderid = $request->bidcreationData['tenderid'];
            $bidCreation -> tenderinvtauth = $request->bidcreationData['tenderinvtauth'];
            $bidCreation -> tenderref = $request->bidcreationData['tenderref'];
            $bidCreation -> state = $request->bidcreationData['state']['value'];
            $bidCreation -> ulb = $request->bidcreationData['ulb']['value'];
            $bidCreation -> TenderDescription = $request->bidcreationData['TenderDescription'];
            $bidCreation -> NITdate = $request->bidcreationData['NITdate'];
            $bidCreation -> submissiondate = $request->bidcreationData['submissiondate'];
            $bidCreation -> quality = $request->bidcreationData['quality'];
            $bidCreation -> unit = $request->bidcreationData['unit'];
            $bidCreation -> tenderevalutionsysytem = $request->bidcreationData['tenderevalutionsysytem'];
            $bidCreation -> projectperioddate1 = $request->bidcreationData['projectperioddate1'];
            $bidCreation -> projectperioddate2 = $request->bidcreationData['projectperioddate2'];
            $bidCreation -> estprojectvalue = $request->bidcreationData['estprojectvalue'];
            $bidCreation -> tenderfeevalue = $request->bidcreationData['tenderfeevalue'];
            $bidCreation -> priceperunit = $request->bidcreationData['priceperunit'];
            $bidCreation -> emdmode = $request->bidcreationData['emdmode'];
            $bidCreation -> emdamt = $request->bidcreationData['emdamt'];
            $bidCreation -> dumpsiter = $request->bidcreationData['dumpsiter'];
            $bidCreation -> prebiddate = $request->bidcreationData['prebiddate'];
            $bidCreation -> EMD = $request->bidcreationData['EMD']; 
            $bidCreation -> location = $request->bidcreationData['location'];
            $bidCreation -> tendercreation= $request->tenderid;
            $bidCreation -> createdby_userid = $userid ;
            $bidCreation -> updatedby_userid = 0 ;
            $bidCreation -> save();
        }

        if ($bidCreation) {
            return response()->json([
                'status' => 200,
                'message' => 'Bid Has created Succssfully!',
                'id' => $bidCreation['id'],
            ]);
        }else{
            return response()->json([
                'status' => 400,
                'message' => 'Unable to save!'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BidCreation_Creation  $bidCreation_Creation
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $bidCreation_Creation = BidCreation_Creation::find($id);
        if ($bidCreation_Creation){

            $state = StateMaster::find($bidCreation_Creation['state']);
            $stateValue = ["value" => $state['id'], "label" =>  $state['state_name']];

            $ulb = CustomerCreationProfile::find($bidCreation_Creation['ulb']);
            $ulbValue = ["value" => $ulb['id'], "label" =>  $ulb['customer_name']];

            $bidCreation_Creation['state'] = $stateValue;
            $bidCreation_Creation['ulb'] = $ulbValue;

            return response()->json([
                'status' => 200,
                'bidcreationdata' => $bidCreation_Creation
            ]);
        }

        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are Invalid'
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BidCreation_Creation  $bidCreation_Creation
     * @return \Illuminate\Http\Response
     */
    public function edit(BidCreation_Creation $bidCreation_Creation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BidCreation_Creation  $bidCreation_Creation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        //
        $user = Token::where('tokenid', $request->tokenid)->first();
        $userid = $user['userid'];

        if($userid){
            $updatedata = $request->bidcreationData;
            $updatedata['updatedby_userid']= $userid;
            $updatedata['state']= $request->bidcreationData['state']['value'];
            $updatedata['ulb']= $request->bidcreationData['ulb']['value'];

            $bidcreationData = BidCreation_Creation::findOrFail($id)->update($updatedata);
        }

        if ($bidcreationData)
            return response()->json([
                'status' => 200,
                'message' => "Updated Successfully!"
            ]);
        else {
            return response()->json([
                'status' => 400,
                'message' => 'Unable to save!'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BidCreation_Creation  $bidCreation_Creation
     * @return \Illuminate\Http\Response
     */
    public function destroy($tender_creaton_id)
    {
        //
        try{

            // $bidcreation = BidCreation_Creation::where("tendercreation",$tender_creaton_id)->get();

            // if(count($bidcreation) > 0){

            //     $docs = BidCreation_Creation_Docs::where("bidCreationMainId",$bidcreation[0]['id'])->get();
                
            //     if($docs){
            //         foreach($docs as $doc){
            //             $document = BidCreation_Creation_Docs::find($doc['id']);
    
            //             $filename = $document['file_new_name'];
            //             $file_path = public_path()."/uploads/BidManagement/biddocs/".$filename;
            //             // $file_path =  storage_path('app/public/BidDocs/'.$filename);
            
            //             if(File::exists($file_path)) {
            //                 File::delete($file_path);
            //             }
            //         }
            //     }
    
            //     $prebiddocs = BidmanagementPreBidQueries::where("bidCreationMainId",$id)->get();
    
            //     if($prebiddocs){
            //         foreach($prebiddocs as $doc){
            //             $document = BidmanagementPreBidQueries::find($doc['id']);
    
            //             $filename = $document['file_new_name'];
            //             $file_path = public_path()."/uploads/BidManagement/prebidqueries/".$filename;
            //             // $file_path =  storage_path('app/public/BidDocs/'.$filename);
            
            //             if(File::exists($file_path)) {
            //                 File::delete($file_path);
            //             }
            //         }
            //     }
            // }




            $deleteBid = TenderCreation::destroy($tender_creaton_id);

            if($deleteBid)
            {return response()->json([
                'status' => 200,
                'message' => "Deleted Successfully!"
            ]);}
            else
            {return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.',
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

    public function getBidList(Request $request){

           //
          if($request->fromdate && $request->todate){
            // $tenderCreation = DB::table('tender_creations')
            // ->join('bid_creation__creations','tender_creations.id','bid_creation__creations.id')
            // ->whereBetween('tender_creations.nitdate', [$request->fromdate, $request->todate])
            // ->select('*')
            // ->orderBy('NITdate', 'DESC')
            // ->get();

            $tenderCreation = DB::table('tender_creations')
            ->leftjoin('bid_creation__creations','tender_creations.id','bid_creation__creations.tendercreation')
            ->join('customer_creation_profiles','tender_creations.customername','customer_creation_profiles.id')
            ->whereBetween('tender_creations.nitdate', [$request->fromdate, $request->todate])
            ->select('tender_creations.id AS tenderid', 'bid_creation__creations.id AS bidid', 'tender_creations.nitdate', 'tender_creations.customername', 'bid_creation__creations.quality', 'bid_creation__creations.unit', 'bid_creation__creations.submissiondate', 'customer_creation_profiles.customer_name')
            ->orderBy('tender_creations.nitdate', 'DESC')
            ->get();
          }

          

          return response()->json([
              'tenderCreationList' => $tenderCreation 
          ]);

    }


    public function getlegacylist(Request $request){

        $todaydate = date('Y-m-d'); 
      
        $tender_participation = $request->tenderparticipation;
        $formdate = $request->fromdate;
        $todate = $request->todate;
        $status	= $request->status;
		$state	=  $request->state;		
		$typeofproject =  $request->typeofproject;	
        $typeofcustomer =  $request->typeofcustomer;
     

        DB::enableQueryLog(); 

        $legacystatememnt = DB::table('tender_creations')
        ->leftjoin('bid_creation__creations', 'tender_creations.id','bid_creation__creations.tendercreation')
        ->join('customer_creation_profiles','tender_creations.customername','customer_creation_profiles.id')
        ->leftjoin('bid_creation_tender_participations', 'bid_creation__creations.id', 'bid_creation_tender_participations.bidCreationMainId')
        ->leftjoin('customer_creation_s_w_m_project_statuses', 'customer_creation_profiles.id', 'customer_creation_s_w_m_project_statuses.mainid' )
        ->where('bid_creation__creations.submissiondate' , '<', $todaydate)
        // ->where('tender_creations.nitdate' , '<', $todaydate)
        ->when($formdate, function($query) use ($formdate) {
            return $query->where('tender_creations.nitdate','>=',$formdate);
        })
        ->when($todate, function($query) use ($todate) {
            return $query->where('tender_creations.nitdate','<=',$todate);
        })
        ->when($state, function($query) use ($state) {
            return $query->where('bid_creation__creations.state','=',$state);
        })
        ->when($typeofproject, function($query) use ($typeofproject) {
            return $query->where('customer_creation_s_w_m_project_statuses.projecttype', $typeofproject);    
            // return $query->whereIn('customer_creation_profiles.id', function($query) use ($typeofproject){
            //     $query->select('id')->from('customer_creation_s_w_m_project_statuses')
            //     ->where('projecttype', $typeofproject);
            // });
        })
        ->when($typeofcustomer, function($query) use ($typeofcustomer){
            if($typeofcustomer === "Public"){
                return $query->where('tender_creations.organisation','Public Sector Unit');
            }
            if($typeofcustomer === "Private"){
                return $query->where('tender_creations.organisation','Private/Public Sector Company');
            }
        })

        ->when($tender_participation, function($query) use ($tender_participation){
            if($tender_participation === "Yes"){
                return $query->where('bid_creation_tender_participations.tenderparticipation','participating');
            }
            if($tender_participation === "No"){
                return $query->where('bid_creation_tender_participations.tenderparticipation','notparticipating');
            }
        })
        ->orderBy('tender_creations.nitdate', 'DESC')
        ->get();

        $sqlquery = DB::getQueryLog();
        
        $SQL = str_replace(array('?'), array('\'%s\''),  $sqlquery[0]['query']);
        $SQL = vsprintf($SQL, $sqlquery[0]['bindings']);

        return response()->json([
            'legacylist' => $legacystatememnt,
            'sql'=>$SQL,
            'type_of_company' => $request->typeofcustomer,
            'tender_participation' => $request->tenderparticipation,
            'formdate' => $request->fromdate,
            'todate' => $request->todate
        ]);
    }


     // Created by Brindha on 21.01.2023 for dashboard count
    public function live_tender()
    {
        $live_tender_count = BidCreation_Creation::whereDate('submissiondate', '>=', now())->count();
        // $live_tender = BidCreation_Creation::where('created_at', 'desc')->get();
     
        if ($live_tender_count)
            return response()->json([
                'status' => 200,
                'live_tender_count' => $live_tender_count
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }
public function fresh_tender()
    {
        $fresh_tender_count = BidCreation_Creation::whereDate('created_at', '=', now())->count();
        // $live_tender = BidCreation_Creation::where('created_at', 'desc')->get();
      
        if ($fresh_tender_count)
            return response()->json([
                'status' => 200,
                'fresh_tender_count' => $fresh_tender_count
            ]);
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

    
    public function getLastBidno($code){

        $lastbidno = BidCreation_Creation::select('bidno')
        ->where('bidno','Like',"%$code%") 
        ->get()
        ->last();
            
        if ($lastbidno)
            return response()->json([
                'status' => 200,
                'lastbidno' => $lastbidno
            ]);
        else {
            return response()->json([
                'status' => 404,
                'lastbidno' => $lastbidno,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }

}
