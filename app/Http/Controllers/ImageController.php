<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        $filename = Storage::putFile('uploads', $request->file, 'public');
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

        // OCR実行
        $text = $this->ocrService->detect($filepath);
        
        $message = new \Illuminate\Support\MessageBag();
        if (empty($text)) {
            $message->add('filepath', '画像内に認識可能な文字がありません');
        }
        
        return view('image.index')->with([
            'filepath' => $filepath,
            'result_text' => $text
        ])->withErrors($message);
    }
}
