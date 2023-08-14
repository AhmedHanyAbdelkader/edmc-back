<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FollowUp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;



class FollowUpController extends Controller{

    public function sendFollowUp(Request $request)
    {
        $validatedData = Validator::make($request->all(),[
        'subject' => 'required|string',
        'body' => 'required|string',
        'sender_id' => 'required',
        'receiver_id' => 'required',
        'status_id' =>  'required',
        'importance_id'=> 'required|int',
        'pdf' => 'array', // Change the validation rule to expect an array
        'pdf.*' => 'file|mimes:pdf', // Add validation rule for each PDF file in the array
        'registration_number' => 'required',
        'doc_id' => 'required|int',
        ]);
        if ($validatedData->fails()) {
           return response()->json([
               'sector' => null,
               $validatedData->errors(),
             'status_code' => 400,
              ]);
        }



        $pdfFiles = [];
        if ($request->hasFile('pdf')) {
            foreach ($request->file('pdf') as $file) {
                $pdfFiles[] = $file;
            }
        }

        $follow_up = FollowUp::create([
            'subject' => $request->subject,
            'body' => $request->body,
            'sender_id' => $request->sender_id,
            'receiver_id' => $request->receiver_id,
            'status_id' => $request->status_id,
            'importance_id' => $request->importance_id,
            'pdf' => $pdfFiles,
            'registration_number' => $request->registration_number,
            'doc_id' => $request->doc_id,
        ]);

        $follow_up_Id = $follow_up->id;

        $new_follow_up = FollowUp::with('importance','status')
        -> find($follow_up_Id);

        return response()->json([
            'document' => $new_follow_up,
            'status_code' => 200,
            'message' => 'your new document is successfully sent'
        ]);

    }



    public function getPdf(Request $request)
    {
        $pdfPath = $request->input('pdfPath');

        // Return the PDF file as a response
        $pdf = Storage::get($pdfPath);
        return response($pdf, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "inline; $pdfPath");
    }


    public function getDocFollowUps(Request $request){

        try{
            $id = $request->input('id');
            $follow_ups = FollowUp::with('importance','status')
            ->where('doc_id', $id)->get();
        return response()->json($follow_ups);
        }catch (\Exception $e) {
            return response()->json([
                "status_code" => 500,
                "error" => 'An error occurred'
            ],500
        );
        }

        return response()->json($follow_ups);

    }




}
