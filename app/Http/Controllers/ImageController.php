<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        // APIキー
        $api_key = config('google.apikey');

        // 認識対象の画像パス
        $image_path = $request->input('imagepath');

        // リクエスト用のJSONを作成
        $json = json_encode([
            "requests" => [
                [
                    "image" => [
                        "content" => base64_encode(file_get_contents($image_path)),
                    ],
                    "features" => [
                        [
                            "type" => "DOCUMENT_TEXT_DETECTION" ,
                            "maxResults" => 10 ,
                        ],
                    ],
                ],
            ],
        ]);

        // リクエストを実行
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://vision.googleapis.com/v1/images:annotate?key=" . $api_key);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        if(isset($referer) && !empty($referer)) curl_setopt($curl, CURLOPT_REFERER, $referer);
        curl_setopt($curl, CURLOPT_TIMEOUT, 15);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
        $res1 = curl_exec($curl);
        $res2 = curl_getinfo($curl);
        curl_close($curl);

        // 取得したデータ
        $json = substr($res1, $res2["header_size"]);
        $array_json=json_decode($json, true);

        $text=$array_json["responses"]["0"]["textAnnotations"]["0"]["description"];

        return view('image.index')->with([
            'filename' => basename($image_path),
            'result_text' => $text
        ]);
    }
}
