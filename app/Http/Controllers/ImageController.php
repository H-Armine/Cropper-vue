<?php

namespace App\Http\Controllers;


use App\Http\Requests\ValidateImageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Inertia\Inertia;
use App\Models\Image;


class ImageController extends Controller
{
    public function index()
    {
        $image = Image::all();
        return Inertia::render('ImageCropper', [
            'images' => $image
        ]);
    }

    public function saveImg(ValidateImageRequest $request)
    {
        $img = $request->file('image');

        $name = uniqid() . '.png';
        $path = '/upload/' . $name;
        $request->file('image')->move(public_path('/upload/'), $name);
        $image = Image::create([
            'name' => $path
        ]);
        return response()->json(['success' => 'Crop Image Uploaded Successfully',
            'file' => $image
        ]);
    }
}
