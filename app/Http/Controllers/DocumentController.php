<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\followUp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;



class DocumentController extends Controller{

    public function sendDodument(Request $request)
    {
        $validatedData = Validator::make($request->all(),[
        'subject' => 'required|string',
        'body' => 'required|string',
        'sender_id' => 'required',
        'receiver_id' => 'required',
        //'follow_up_id' =>  'required|int',
        'status_id' =>  'required',
        'importance_id'=> 'required|int',
        'pdf' => 'array', // Change the validation rule to expect an array
        'pdf.*' => 'file|mimes:pdf', // Add validation rule for each PDF file in the array
        'registration_number' => 'required|integer',

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

        $document = Document::create([
            'subject' => $request->subject,
            'body' => $request->body,
            'sender_id' => $request->sender_id,
            'receiver_id' => $request->receiver_id,
            //'follow_up_id' => $request->follow_up_id,
            'status_id' => $request->status_id,
            'importance_id' => $request->importance_id,
            'pdf' => $pdfFiles,
            'registration_number' => $request->registration_number,
        ]);

        $documentId = $document->id;

        $documents = Document::with('importance','status')
        -> find($documentId);

        return response()->json([
            'document' => $documents,
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


    public function getAllDocuments(Request $request){

        $id = $request->input('id');

        $documents = Document::with(['importance','status'])
        ->where(function ($query) use ($id) {
            $query->where('sender_id', $id)
                ->orWhere('receiver_id', $id);
        })->withCount('follow_ups')->get();

        return response()->json($documents);

    }


    public function getAllSentDocuments(Request $request)
    {
        try{
            $id = $request->input('id');

        $documents = Document::with(['importance','status'])
        ->where('sender_id', $id)->withCount('follow_ups')->get();

        return response()->json($documents);

        }catch (\Exception $e) {
            return response()->json([
                "status_code" => 500,
                "error" => 'An error occurred'
            ],500
        );
        }
    }


    public function getAllReceivedDocuments(Request $request)
    {
        $id = $request->input('id');

        $documents = Document::with('importance','status')
        ->where('receiver_id', $id)->withCount('follow_ups')->get();

        return response()->json($documents);
    }

}
