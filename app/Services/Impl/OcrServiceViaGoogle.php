<?php

namespace App\Services\Impl;

use App\Services\OcrService;
use App\Support\Api\GoogleVisionApi;

class OcrServiceViaGoogle implements OcrService
{
    /**
     * @var App\Support\Api\GoogleVisionApi
     */
    private $api;

    public function __construct() {
        $this->api = new GoogleVisionApi();
    }
    
    /**
     * 
     * @see App\Services\OcrServiceContract
     * @param string $image_path
     * @return string result_text
     */
    public function detect(string $image_path) {
        $text = $this->api->document_text_detection($image_path);
        return $text;
    }
}
