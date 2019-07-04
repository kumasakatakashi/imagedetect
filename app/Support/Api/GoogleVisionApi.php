<?php

namespace App\Support\Api;

use GuzzleHttp\Client;

class GoogleVisionApi
{
    private $client;
    private $api_key;

    public function __construct() {
        $this->client = new Client([
            'base_uri' => 'https://vision.googleapis.com/v1/images:annotate',
            'timeout' => 30,
        ]);
        $this->api_key = config('google.apikey');
    }
    
    public function document_text_detection($image_path)
    {
        $response = $this->client->request('POST', null, [
            'query' => [
                'key' => $this->api_key,  
            ],
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => [
                "requests" => [
                    [
                        "image" => [
                            "content" => base64_encode(file_get_contents($image_path)),
                        ],
                        "features" => [
                            [
                                "type" => "DOCUMENT_TEXT_DETECTION" ,
                            ],
                        ],
                    ],
                ],
            ],
        ]);
        $array_json = json_decode($response->getBody(), true);
        $result = $array_json["responses"]["0"];
        $text = isset($result["textAnnotations"]) ? $result["textAnnotations"]["0"]["description"] : "";
        return $text;
    }
}
