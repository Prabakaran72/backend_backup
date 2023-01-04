<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\Models\CommunicationFiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Token;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class CommunicationFilesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            if($request ->hasFile('file')){
                $file = $request->file('file');
                $filename = $file->getClientOriginalName();
                $fileName = date('His') . $filename;
                $file->storeAs('uploads/image/bidmanagement/workorder/communicationFiles/', $fileName, 'public');
                
                return response() -> json([
                    'status' => 200,
                    'message' => 'Uploaded Succcessfully'
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
     * @param  \App\Models\CommunicationFiles  $communicationFiles
     * @return \Illuminate\Http\Response
     */
    public function show(CommunicationFiles $communicationFiles)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CommunicationFiles  $communicationFiles
     * @return \Illuminate\Http\Response
     */
    public function edit(CommunicationFiles $communicationFiles)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CommunicationFiles  $communicationFiles
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CommunicationFiles $communicationFiles)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CommunicationFiles  $communicationFiles
     * @return \Illuminate\Http\Response
     */
    public function destroy(CommunicationFiles $communicationFiles)
    {
        //
    }
}
