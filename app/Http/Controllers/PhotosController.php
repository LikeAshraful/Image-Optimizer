<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ImageOptimizer;

class PhotosController extends Controller
{
    private $original_photos_path; 
    private $optimized_photos_path;
    
    public function __construct()
    {
        $this->original_photos_path = public_path('/images');
        $this->optimized_photos_path = public_path('/images/optimized/');
    }
    
    public function store(Request $request) {

        $photos = $request->file('file');
 
        if (!is_array($photos)) {
            $photos = [$photos];
        } 
        if (!is_dir($this->original_photos_path)) {
            mkdir($this->original_photos_path, 0777);
        }
        if (!is_dir($this->optimized_photos_path)) {
            mkdir($this->optimized_photos_path, 0777);
        }

        for ($i = 0; $i < count($photos); $i++) {

            $photo = $photos[$i];
            $fileName = $photo->getClientOriginalName();          
            $optimizedImgName = str_random(5) . '.' . $photo->getClientOriginalExtension();
            
            $photo->move($this->original_photos_path, $fileName);

            ImageOptimizer::optimize($this->original_photos_path.'/'.$fileName, $this->optimized_photos_path .'/'. $optimizedImgName);
 
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
