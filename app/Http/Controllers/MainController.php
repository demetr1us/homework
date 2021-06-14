<?php


namespace App\Http\Controllers;

use App\Models\FileGenerator;
use Illuminate\Http\Request;

class MainController extends Controller
{

    public function makeFile(Request $request){
        if ($request->json()->count()){
            $file = new FileGenerator($request->json());
            $file->generateFileFromJson();
            return response()->json('OK', 201);
        }else{
            return response()->json('Incorrect data', 404);
        }
    }

}
