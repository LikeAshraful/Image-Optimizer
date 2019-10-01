<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Image;
use ImageOptimizer;
use Session;
use ZipArchive;

class PhotosController extends Controller
{
    private $original_photos_path; 
    private $optimized_photos_path; 
    
    public function __construct()
    {
        $this->original_photos_path = public_path('/images/original');
        $this->optimized_photos_path = public_path('/images/optimized');      
    }

    public function index(Request $request){
        $temp_id = uniqid();            
        $request->session()->put('temp_id',$temp_id);

        return view('welcome');
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

            $temp_id = $request->session()->get('temp_id');

            $image = new Image;
            $image->temp_id = $temp_id;       

            $photo = $photos[$i];
            $fileName = $photo->getClientOriginalName();          
            $optimizedImgName = str_random(5) . '.' . $photo->getClientOriginalExtension();                     
            
            $photo->move($this->original_photos_path, $fileName);            

            ImageOptimizer::optimize($this->original_photos_path.'/'.$fileName, $this->optimized_photos_path .'/'. $optimizedImgName);

            $image->original_img_path = '/images/original/'.$fileName;
            $image->original_img_name = $fileName;
            $image->compressed_img_path = '/images/optimized/'.$optimizedImgName;
            $image->compressed_img_name = $optimizedImgName;
            $image->save();
        }        

        return response('OK', 201);
    }

    public function download_images(Request $request){
        $temp_id = $request->session()->get('temp_id'); 
        $images = Image::where('temp_id', $temp_id)->get();  
              
        // $img_path = '.' . $images->compressed_img_path;        
        $zipFileName = 'images.zip';
        $zip = new ZipArchive;
        $zip->open($zipFileName, ZipArchive::CREATE);
        foreach ($images as $i => $img) {           
            $zip->addFile('.' .$img->compressed_img_path);
           
        }
        $zip->close();
        
        return response()->download($zipFileName);
    }
}
