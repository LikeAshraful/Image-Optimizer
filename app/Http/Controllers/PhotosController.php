<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PhotosController extends Controller
{
    
    public function store() {
        $this->validate(request(), [
            'photo' => 'required|image:jpeg '
        ]);

        request()->photo->storeAs('images', 'optimized.jpg');

        return response('OK', 201);
    }
}
