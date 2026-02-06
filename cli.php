#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';
use App\Services\ImageService;
use App\Controllers\ImageController;

echo "=== Bem vindo ao ImageConverter CLI ===\n";

echo "Informe o(s) caminho(s) da(s) imagem(ns), separados por vírgula: ";
$imgPathsInput = trim(fgets(STDIN));
$imgPaths = array_map('trim', explode(',', $imgPathsInput));

echo "Informe o formato de saída (jpeg, png, webp): ";
$outputFormat = trim(fgets(STDIN));

echo "Informe a qualidade da imagem (1-100). Caso não queira alterar, pressione Enter e o valor padrão será aplicado (padrão 90): ";
$qualityInput = trim(fgets(STDIN));
$quality = is_numeric($qualityInput) ? (int)$qualityInput : 90;

$imageService = new ImageService();
$imageController = new ImageController($imageService);


foreach ($imgPaths as $imgPath) {
    if (!file_exists($imgPath)) {
        echo "Arquivo não encontrado: $imgPath\n";
        continue;
    }

    $imgDir = dirname($imgPath);
    $outputDir = $imgDir . '/ImageConverter';
    if (!is_dir($outputDir)) {
        mkdir($outputDir, 0777, true);
    }

    try {
        $imageData = $imageController->convert($imgPath, $outputFormat, $quality);
        $originalName = pathinfo($imgPath, PATHINFO_FILENAME);
        $hash = md5($imgPath . time());
        $outputFile = $outputDir . '/' . $originalName . '-' . $hash . '.' . $outputFormat;
        file_put_contents($outputFile, $imageData);
        echo "Imagem convertida e salva em: " . $outputFile . "\n";
    } catch (Exception $e) {
        echo "Erro ao converter $imgPath: " . $e->getMessage() . "\n";
    }
}
