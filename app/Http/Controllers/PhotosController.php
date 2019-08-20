<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PhotosController extends Controller
{
    private $photos_path; 
    public function __construct()
    {
        $this->photos_path = public_path('/images');
    }
    
    public function store(Request $request) {

        $photos = $request->file('file');
 
        if (!is_array($photos)) {
            $photos = [$photos];
        } 
        if (!is_dir($this->photos_path)) {
            mkdir($this->photos_path, 0777);
        }

        for ($i = 0; $i < count($photos); $i++) {

            $photo = $photos[$i];
            $fileName = $photo->getClientOriginalName();          
            $save_name = $fileName . str_random(2) . '.' . $photo->getClientOriginalExtension(); 
            $photo->move($this->photos_path, $save_name);
 
        }

        // return Response::json([
        //     'message' => 'Image saved Successfully'
        // ], 200);

        // $this->validate(request(), [
        //     'photo' => 'required|image:jpeg '
        // ]);
        // request()->photo->storeAs('images', 'optimized.jpg');

        return response('OK', 201);
    }
}
