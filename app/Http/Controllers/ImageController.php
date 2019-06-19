<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\Api\GoogleVisionApi;

class ImageController extends Controller
{
    public function index()
    {
        return view('image.index');
    }
    
    /**
     * 画像アップロード
     * 
     * @param Request $request
     * @return type
     */
    public function upload(Request $request)
    {
        $this->validate($request, [
            'file' => [
                'required',
                'file',
                'image',
                'mimes:jpeg,png,gif',
            ]
        ]);
        
        if (!$request->file('file')->isValid()) {
            return back()->withInput()->withErrors();
        }

        $path = $request->file->store('public');
        return view('image.index')->with('filename', basename($path));
    }
    
    /**
     * 文字認識
     * 
     * @param Request $request
     * @return type
     */
    public function detect(Request $request)
    {
        $image_path = $request->input('imagepath');

        // OCR実行 by GoogleVisionAPI
        $api = new GoogleVisionApi();
        $text = $api->document_text_detection($image_path);

        return view('image.index')->with([
            'filename' => basename($image_path),
            'result_text' => $text
        ]);
    }
}
