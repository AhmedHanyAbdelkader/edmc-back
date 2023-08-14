<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Status;




class StatusController extends Controller{


    public function getAllStatus()
    {
        $statusList = Status::all();
        return response()->json([
            'status' => $statusList
        ],
         200);
    }

}
