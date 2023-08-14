<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Importance;




class ImportanceController extends Controller{

     /**
     * Create a new importance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createNewImportance(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'importance_status' => 'required|string|max:255',
        ]);

        // Create the new importance record in the database
        $importance = Importance::create([
            'importance_status' => $request->input('importance_status'),
        ]);
        // Optionally, you can return a response or redirect the user
        return response()->json([
            'message' => 'Importance created successfully',
            'status_code' => 200,
            'importance' => $importance
        ],
         201);
    }

    /**
     * Get all importances.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllImportances()
    {
        // Retrieve all importance records from the database
        $importances = Importance::all();
        // Return the list of importances as a response
        return response()->json([
            'importances' => $importances
        ],
         200);
    }


}
