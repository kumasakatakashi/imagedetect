<?php

namespace Tests\Http\Controllers;

use Tests\TestCase;
use Mockery as m;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageControllerTest extends TestCase
{
    protected function tearDown(): void {
        m::close();
    }
    
    public function testIndex()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('image.index');   
    }

    public function testUpload()
    {
        // ダミーファイルの準備
        $disk = env('FILESYSTEM_DRIVER');
        Storage::fake($disk);
        $file = UploadedFile::fake()->image('fake.jpg');
        
        $response = $this->post('upload', [
            'file' => $file,
        ]);
        
        $response->assertStatus(200);
        $response->assertViewIs('image.index');
        $response->assertViewHas('filepath');
        
        Storage::disk($disk)->assertExists('uploads/'.$file->hashName());
    }

    public function testDetect()
    {
        // テスト用にValidator(ファイル存在チェック)をモック化
        $mock_request = m::mock('overload:\App\Http\Requests\DetectImageRequest');
        $mock_request->shouldReceive('input')
                ->once();
        
        $mock_googleapi = m::mock('overload:\App\Support\Api\GoogleVisionApi');
        $mock_googleapi->shouldReceive('document_text_detection')
                ->once()
                ->with(\Mockery::any())
                ->andReturn('test');
        
        $response = $this->post('detect', [
            'filepath' => '/path/to/test.jpg',
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('image.index');
        $response->assertViewHas('result_text');
    }
}
