<?php

namespace App\Http\Controllers;
use App\Models\BidManagementWorkOrderWorkOrder;
use Illuminate\Http\Request;
use App\Models\Token;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
class BidManagementWorkOrderWorkOrderController extends Controller
{
    
    public function index()
    {
        //
    }

   
    public function create()
    {
        //
    }

   
    public function store(Request $request)
    {
        
        if($request->hasFile('wofile') && $request->hasFile('agfile') && $request->hasFile('shofile')){

            $data=(array) $request->all();   
            $validator = Validator::make($data, [
            'orderQuantity' => 'required|integer',
            'PricePerUnit' => 'required|integer',
            'LoaDate' => 'required|date',
            'OrderDate' => 'required|date',
            'SiteHandOverDate' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' =>"Not able to Add Strength/Weakness details now..!",
                'error' => $validator->messages(),
            ]);
        }
            //image one upload 
            $wofile = $request->file('wofile');
            $wofile_original = $wofile->getClientOriginalName();
            $wofile_fileName =intval(microtime(true) * 1000) . $wofile_original;
            $wofile->storeAs('BidManagement/WorkOrder/WorkOrder/workorderDocument/', $wofile_fileName, 'public');
            $wofile_mimeType =  $wofile->getMimeType();
            $wofile_filesize = ($wofile->getSize())/1000;
            $wofile_ext =  $wofile->extension();

        //    //image one upload 
        //     $wofile = $request->file('wofile');
        //     $fileExt_I = $wofile->getClientOriginalName();
        //     $FileExt_I = $wofile->getClientOriginalExtension();
        //     $fileName_I=$wofile->hashName();
        //     $filenameSplited_I=explode(".",$fileName_I);
        //     if($filenameSplited_I[1]!=$fileExt_I)
        //     {
        //     $FileName_I=$filenameSplited_I[0].".".$FileExt_I;
        //     }
        //     else{
        //         $FileName_I=$fileName_I;   
        //     }
        //     $wofile->storeAs('BidManagement/WorkOrder/WorkOrder/workorderDocument/', $FileName_I, 'public');

           //image two upload
          $agfile = $request->file('agfile');
          $agfile_original = $agfile->getClientOriginalName();
          $agfile_fileName =intval(microtime(true) * 1000) . $agfile_original;
          $agfile->storeAs('BidManagement/WorkOrder/WorkOrder/agreementDocument/', $agfile_fileName, 'public');
          $agfile_mimeType =  $agfile->getMimeType();
          $agfile_filesize = ($agfile->getSize())/1000;
          $agfile_ext =  $agfile->extension();

            // //image two upload
            // $agfile = $request->file('agfile');
            // $fileExt_II = $agfile->getClientOriginalName();
            // $FileExt_II = $agfile->getClientOriginalExtension();
            // $fileName_II=$agfile->hashName();
            // $filenameSplited_II=explode(".",$fileName_II);
            // if($filenameSplited_II[1]!=$fileExt_II)
            // {
            // $FileName_II=$filenameSplited_II[0].".".$FileExt_II;
            // }
            // else{
            //     $FileName_II=$fileName_II;   
            // }
            // $agfile->storeAs('BidManagement/WorkOrder/WorkOrder/agreementDocument/', $FileName_II, 'public');
          


            //image three upload
          $shofile = $request->file('shofile');
          $shofile_original = $shofile->getClientOriginalName();
          $shofile_fileName =intval(microtime(true) * 1000) . $shofile_original;
          $shofile->storeAs('BidManagement/WorkOrder/WorkOrder/siteHandOverDocumet/', $shofile_fileName, 'public');
          $shofile_mimeType =  $shofile->getMimeType();
          $shofile_filesize = ($shofile->getSize())/1000;
          $shofile_ext =  $shofile->extension();


            // //image three upload
            // $shofile = $request->file('shofile');
            // $fileExt_III = $shofile->getClientOriginalName();
            // $FileExt_III = $shofile->getClientOriginalExtension();
            // $fileName_III=$shofile->hashName();
            // $filenameSplited_III=explode(".",$fileName_III);
            // if($filenameSplited_III[1]!=$fileExt_III)
            // {
            // $FileName_III=$filenameSplited_III[0].".".$FileExt_III;
            // }
            // else{
            //     $FileName_III=$fileName_III;   
            // }
            // $shofile->storeAs('BidManagement/WorkOrder/WorkOrder/siteHandOverDocumet/', $FileName_III, 'public');
            
            
            $user = Token::where('tokenid', $request->tokenid)->first();   
            $userid =$user['userid'];
            $request->request->remove('tokenid');
            if($userid){

                $WorkOrder = new BidManagementWorkOrderWorkOrder;
                $WorkOrder -> bidid = $request->bidid;
                $WorkOrder -> orderquantity = $request->orderQuantity;
                $WorkOrder -> priceperUnit = $request->PricePerUnit;
                $WorkOrder -> loadate = $request->LoaDate;
                $WorkOrder -> orderdate = $request->OrderDate;
                $WorkOrder -> agreedate = $request->AgreeDate;
                $WorkOrder -> sitehandoverdate = $request->SiteHandOverDate;
                $WorkOrder -> wofile = $wofile_fileName;
                $WorkOrder -> agfile = $agfile_fileName;
                $WorkOrder -> shofile = $shofile_fileName;
                $WorkOrder -> createdby_userid = $userid ;
                // $WorkOrder -> updatedby_userid = 0 ;
                $WorkOrder -> save();

            //  //image one upload 
            //  $wofile->storeAs('BidManagement/WorkOrder/WorkOrder/workorderDocument/', $FileName_I, 'public');
            //  //image two upload
            //  $agfile->storeAs('BidManagement/WorkOrder/WorkOrder/agreementDocument/', $FileName_II, 'public');
            //  //image three upload
            //  $shofile->storeAs('BidManagement/WorkOrder/WorkOrder/siteHandOverDocumet/', $FileName_III, 'public');
            }
            return response()-> json([
                    'status' => 200,
                    'message' => 'Uploaded Succcessfully',
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
     * @param  \App\Models\BidManagementWorkOrderWorkOrder  $bidManagementWorkOrderWorkOrder
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $WorkOrder = BidManagementWorkOrderWorkOrder::where('bidid','=',$id)->get();
        if ($WorkOrder){
            return response()->json([
                'status' => 200,
                'WorkOrder' => $WorkOrder,
            ]);
        }   
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
     * @param  \App\Models\BidManagementWorkOrderWorkOrder  $bidManagementWorkOrderWorkOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(BidManagementWorkOrderWorkOrder $bidManagementWorkOrderWorkOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BidManagementWorkOrderWorkOrder  $bidManagementWorkOrderWorkOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    { 
        

        if($request->hasFile('wofile') && $request->hasFile('agfile') && $request->hasFile('shofile')){

            $data=(array) $request->all();   
            $validator = Validator::make($data, [
                'orderQuantity' => 'required|integer',
                'PricePerUnit' => 'required|integer',
                'LoaDate' => 'required|date',
                'OrderDate' => 'required|date',
                'SiteHandOverDate' => 'required|date',
        ]);

        
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' =>"Not able to Add Strength/Weakness details now..!",
                'error' => $validator->messages(),
            ]);
        }

            $doc = BidManagementWorkOrderWorkOrder::find($id);
            
            $wofile_filename = $doc['wofile'];
            $wofile_path = public_path()."/uploads/BidManagement/WorkOrder/WorkOrder/workorderDocument/".$wofile_filename;

            $agfile_filename = $doc['agfile'];
            $agfile_path = public_path()."/uploads/BidManagement/WorkOrder/WorkOrder/agreementDocument/".$agfile_filename;

            $shofile_filename = $doc['shofile'];
            $shofile_path = public_path()."/uploads/BidManagement/WorkOrder/WorkOrder/siteHandOverDocumet/".$shofile_filename;
            
            if(File::exists($wofile_path)){
                if(File::delete($wofile_path)){
                    if(File::exists($agfile_path)){
                        if(File::delete($agfile_path)){
                            if(File::exists($shofile_path)){
                                if(File::delete($shofile_path)){
                                    
                                    // wofile update
                                  $wofile = $request->file('wofile');
                                  $wofile_original = $wofile->getClientOriginalName();
                                  $wofile_fileName =intval(microtime(true) * 1000) . $wofile_original;
                                  $wofile->storeAs('BidManagement/WorkOrder/WorkOrder/workorderDocument/', $wofile_fileName, 'public');
                                  $wofile_mimeType =  $wofile->getMimeType();
                                  $wofile_filesize = ($wofile->getSize())/1000;
                                  $wofile_ext =  $wofile->extension();

                                    // agfile file update
                                  $agfile = $request->file('agfile');
                                  $agfile_original = $agfile->getClientOriginalName();
                                  $agfile_fileName =intval(microtime(true) * 1000) . $agfile_original;
                                  $agfile->storeAs('BidManagement/WorkOrder/WorkOrder/agreementDocument/', $agfile_fileName, 'public');
                                  $agfile_mimeType =  $agfile->getMimeType();
                                  $agfile_filesize = ($agfile->getSize())/1000;
                                  $agfile_ext =  $agfile->extension();

                                   //shofile  update
                                   $shofile = $request->file('shofile');
                                   $shofile_original = $shofile->getClientOriginalName();
                                   $shofile_fileName =intval(microtime(true) * 1000) . $shofile_original;
                                   $shofile->storeAs('BidManagement/WorkOrder/WorkOrder/siteHandOverDocumet/', $shofile_fileName, 'public');
                                   $shofile_mimeType =  $shofile->getMimeType();
                                   $shofile_filesize = ($shofile->getSize())/1000;
                                   $shofile_ext =  $shofile->extension();
                                   
                                   $user = Token::where('tokenid', $request->tokenid)->first();   
                                   $userid =$user['userid'];
                                   $request->request->remove('tokenid');
                    
                                   if($userid){
                                    $WorkOrder =  BidManagementWorkOrderWorkOrder::find($id);
                                    $WorkOrder -> bidid = $request->bidid;
                                    $WorkOrder -> orderquantity = $request->orderQuantity;
                                    $WorkOrder -> priceperUnit = $request->PricePerUnit;
                                    $WorkOrder -> loadate = $request->LoaDate;
                                    $WorkOrder -> orderdate = $request->OrderDate;
                                    $WorkOrder -> agreedate = $request->AgreeDate;
                                    $WorkOrder -> sitehandoverdate = $request->SiteHandOverDate;
                                    $WorkOrder -> wofile = $wofile_fileName;
                                    $WorkOrder -> agfile = $agfile_fileName;
                                    $WorkOrder -> shofile = $shofile_fileName;
                                    $WorkOrder -> updatedby_userid = $userid ;
                                    $WorkOrder -> save();
                                   }  
                                   if ($WorkOrder) {
                                    return response()->json([
                                        'status' => 200,
                                        'message' => 'Updated Succcessfully'
                                    ]);
                                }else{
                                    return response()->json([
                                        'status' => 400,
                                        'message' => 'Unable to update!'
                                    ]);
                                }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BidManagementWorkOrderWorkOrder  $bidManagementWorkOrderWorkOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(BidManagementWorkOrderWorkOrder $bidManagementWorkOrderWorkOrder)
    {
        //
    }

    public function wodownload($id){
        $doc = BidManagementWorkOrderWorkOrder::where('bidid','=',$id)->get();
        if($doc[0]['wofile'] !== ""){
            $wofile_name = $doc[0]['wofile'];
            $wofile = public_path()."/uploads/BidManagement/WorkOrder/WorkOrder/workorderDocument/".$wofile_name;
            return response()->download($wofile);
        }
    }

    public function agdownload($id){
        $doc = BidManagementWorkOrderWorkOrder::where('bidid','=',$id)->get();
        if($doc[0]['agfile'] !== ""){
            $agfile_name = $doc[0]['agfile'];
            $agfile = public_path()."/uploads/BidManagement/WorkOrder/WorkOrder/agreementDocument/".$agfile_name;
            return response()->download($agfile);
        }
    }

    public function shodownload($id){
        $doc = BidManagementWorkOrderWorkOrder::where('bidid','=',$id)->get();
        if($doc[0]['shofile'] !== ""){
             $shofile_name = $doc[0]['shofile'];
             $shofile = public_path()."/uploads/BidManagement/WorkOrder/WorkOrder/siteHandOverDocumet/".$shofile_name;
            return response()->download($shofile);
        }
    }

    public function getimagename($id){
        $doc = BidManagementWorkOrderWorkOrder::where('bidid','=',$id)->get();
        if ($doc){
            return response()->json([
                'status' => 200,
                'doc' => $doc,
            ]);
        }   
        else {
            return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
        }
    }
}
