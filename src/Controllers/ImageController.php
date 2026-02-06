<?php

namespace App\Controllers;

use App\Services\ImageService;

class ImageController
{

    private $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function convert($imgPath, $outputFormat, $quality = 90)
    {

        try {

            $convertedImageData = $this->imageService->convertImage($imgPath, $outputFormat, $quality);
            return $convertedImageData;

        } catch (\Exception $e) {

            return 'Erro na conversão da imagem: ' . $e->getMessage();
        }
    }

    public function save($outputDir, $outputFormat, $imageData)
    {
        return $this->imageService->saveImage($outputDir, $outputFormat, $imageData);
    }
}
