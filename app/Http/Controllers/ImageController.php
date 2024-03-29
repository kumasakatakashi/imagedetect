<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Http\Requests\UploadImageRequest;
use App\Http\Requests\DetectImageRequest;
use App\Services\OcrService;

class ImageController extends Controller
{
    /**
     * @var App\Services\OcrService
     */
    protected $ocrService;
    
    public function __construct(OcrService $ocrService)
    {
        $this->ocrService = $ocrService;
    }
    
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
        // subfolder:uploads / disk:depends on .env(FILESYSTEM_DRIVER) / accessable:public
        //$path = Storage::putFile('uploads', $request->file, 'public');
        // resize
        $uploadfile = $request->file;
        $encode_type = substr(strstr($uploadfile->getMimeType(), '/'), 1);
        $image = Image::make($uploadfile)->resize(600, 400)->encode($encode_type);

        // put
        $hash = md5($image->__toString());
        $path = 'uploads/'.$hash.'.'.$uploadfile->getClientOriginalExtension();
        Storage::put($path, $image->__toString(), 'public');
        
        return view('image.index')->with('filepath', Storage::url($path));
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

        // OCR実行
        $text = $this->ocrService->detect($filepath);
        
        if (empty($text)) {
            session()->flash('warning', '画像内に認識可能な文字がありません');
        }
        
        return view('image.index')->with([
            'filepath' => $filepath,
            'result_text' => $text
        ]);
    }
}
