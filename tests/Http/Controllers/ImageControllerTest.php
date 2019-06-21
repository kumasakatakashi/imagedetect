<?php

namespace Tests\Http\Controllers;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ImageControllerTest extends TestCase
{
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
        
        Storage::disk($disk)->assertExists($file->hashName());
    }
}
