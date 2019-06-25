<?php

namespace App\Services;

interface OcrService
{
    public function detect(string $image_path);
}
