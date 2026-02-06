#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';
use App\Services\ImageService;
use App\Controllers\ImageController;

echo "=== Bem vindo ao ImageConverter CLI ===\n";
echo "Informe o caminho da imagem: ";

$imgPath = trim(fgets(STDIN));
if (!file_exists($imgPath)) {
    echo "Arquivo não encontrado: $imgPath\n";
    exit(1);
}

$imgDir = dirname($imgPath);
$outputDir = $imgDir . '/ImageConverter';
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0777, true);
}

echo "Informe o formato de saída (jpeg, png, webp): ";
$outputFormat = trim(fgets(STDIN));

echo "Informe a qualidade da imagem (1-100). Caso não queira alterar, pressione Enter e o valor padrão será aplicado (padrão 90): ";
$qualityInput = trim(fgets(STDIN));
$quality = is_numeric($qualityInput) ? (int)$qualityInput : 90;

$imageService = new ImageService();
$imageController = new ImageController($imageService);

try {

    $imageData = $imageController->convert($imgPath, $outputFormat, $quality);
    $saveImage = $imageController->save($outputDir, $outputFormat, $imageData);

    echo "Imagem convertida e salva em: " . $saveImage . "\n";

} catch (Exception $e) {

    echo "Erro: " . $e->getMessage() . "\n";
}
