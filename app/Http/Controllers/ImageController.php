<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UploadImageRequest;
use App\Http\Requests\DetectImageRequest;
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
    public function upload(UploadImageRequest $request)
    {
        // subfolder:none disk:depends on .env(FILESYSTEM_DRIVER)
        $filename = Storage::putFile('', $request->file);
        return view('image.index')->with('filepath', Storage::url($filename));
    }
    
    /**
     * 文字認識
     * 
     * @param Request $request
     * @return type
     */
    public function detect(DetectImageRequest $request)
    {
        $filepath = $request->input('filepath');

        // OCR実行 by GoogleVisionAPI
        $api = new GoogleVisionApi();
        $text = $api->document_text_detection($filepath);

        return view('image.index')->with([
            'filepath' => $filepath,
            'result_text' => $text
        ]);
    }
}
