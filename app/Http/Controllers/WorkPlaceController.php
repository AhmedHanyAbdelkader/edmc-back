<?php

namespace App\Http\Controllers;

use App\Models\Sector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class WorkPlaceController extends Controller{

    public function getAllSectors(){
    $sectors = Sector::all(); // Assuming you have a "Sector" model representing the sectors table

    return response()->json([
         'data' => $sectors,
        //"status_code" => 200,
       // 'message' => 'Sectors retrieved successfully',
    ],
    200
);

    if(!sectors){
        return response()->json([
            "status_code" => 422,
            "error" => 'No Sectors found'
        ],
        422);
    }
}


    public function getSectorByNameOrId(Request $request)
{
    $sector = null;
    try{
        // Check if sector name or sector ID is provided in the request
    if ($request->has('sector_name')) {
        $sector = Sector::where('sector_name', $request->input('sector_name'))->first();
    } elseif ($request->has('sector_id')) {
        $sector = Sector::find($request->input('sector_id'));
    }

    if ($sector) {
        // Sector found
        return response()->json(['sector' => $sector]);
    } else {
        // Sector not found
        return response()->json(['error' => 'Sector not found'], 404);
    }
    }catch (\Exception $e) {
        return response()->json([
            "status_code" => 500,
            "error" => "An error occurred $e"
        ],
        500);

        }
}



    public function createSector(Request $request)
    {

        $validatedData = Validator::make($request->all(),[
            'sector_id' => 'required|unique:sector',
            'sector_name' => 'required',
        ]);

        if ($validatedData->fails()) {
            //return response()->json($validatedData->errors()->toJson(), 400);
           return response()->json([
               'sector' => null,
               $validatedData->errors(),
             'status_code' => 400,
              ]
            );
        }

        $sector = Sector::create([
            'sector_id' => $request->sector_id,
            'sector_name' => $request->sector_name,
        ]);

        return response()->json([
            'sector' => $sector,
            'status_code' => 200,
            'message' => 'your new sector is successfully created'
        ]);
    }

    // Other methods as per your requirements
}
