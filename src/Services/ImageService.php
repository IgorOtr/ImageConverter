<?php

namespace App\Services;

class ImageService
{
    public function convertImage(string $imgPath, string $outputFormat, int $quality = 90): string
    {
        $imageInfo = getimagesize($imgPath);
        $mimeType = $imageInfo['mime'];

        switch ($mimeType) {

            case 'image/jpeg':
                $image = imagecreatefromjpeg($imgPath);
                break;

            case 'image/jpg':
                $image = imagecreatefromjpeg($imgPath);
                break;

            case 'image/png':
                $image = imagecreatefrompng($imgPath);
                break;

            default:
                throw new \Exception('Formato de Imagem não suportado: ' . $mimeType);
        }

        ob_start();

        switch (strtolower($outputFormat)) {

            case 'jpeg':
                imagejpeg($image, null);
                break;

            case 'jpg':
                imagejpeg($image, null);
                break;

            case 'png':
                imagepng($image, null);
                break;

            case 'webp':
                imagewebp($image, null);
                break;

            default:
                imagedestroy($image);
                throw new \Exception('Ainda não é possível converter para: ' . $outputFormat);
        }
        $imageData = ob_get_clean();
        imagedestroy($image);

        return $imageData;
    }

    public function saveImage($outputDir, $outputFormat, $imageData)
    {
        $hash = md5(time());
        $outputFile = $outputDir . '/ImageConverter-' . $hash . '.' . $outputFormat;
        file_put_contents($outputFile, $imageData);

        return $outputFile;
    }
}
