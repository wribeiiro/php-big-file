<?php

use App\FileReaderGenerator;
use App\FileReaderFiber;

require_once './vendor/autoload.php';

function runReaderGenerator(): array
{
	$generatorReader = new FileReaderGenerator('./bigger-file.csv');
	$generatorContents = $generatorReader->readfile();

	foreach ($generatorContents as $line) {
		if (!empty($line)) {
			printf('%s fez %d let it rip %s', $line[0], $line[1], PHP_EOL);	
		}
	}

	return [
		'memory' => round(memory_get_peak_usage() / 1024, 0) . "KB",
		'fileLength' => round(filesize('./bigger-file.csv') / 1024 / 1024, 0) . "MB"
	];
}

function runReaderFiber(): array
{	
	$fiberReader = new FileReaderFiber('./bigger-file.csv');
	$fiberContents = new Fiber(function () use ($fiberReader) {
	    return $fiberReader->readfile();
	});

	$fiberContents->start();
	while ($fiberLine = $fiberContents->resume()) {
	    printf('%s fez %d let it rip %s', $fiberLine[0], $fiberLine[1], PHP_EOL);
	}

	return [
		'memory' => round(memory_get_peak_usage() / 1024, 0) . "KB",
		'fileLength' => round(filesize('./bigger-file.csv') / 1024 / 1024, 0) . "MB"
	];

	echo "Leitura usando Fiber" . PHP_EOL;
	echo "Memória utilizada: " . round(memory_get_peak_usage() / 1024, 0) . "KB" . PHP_EOL;
	echo "Tamanho do arquivo: " . round(filesize('./bigger-file.csv') / 1024 / 1024, 0) . "MB" . PHP_EOL;
}

$generatorResult = runReaderGenerator();
$fiberResult = runReaderFiber();

echo "----------------------------" . PHP_EOL;
echo "Leitura usando Generator" . PHP_EOL;
echo "Memória utilizada: " . $generatorResult['memory'] . PHP_EOL;
echo "Tamanho do arquivo: " . $fiberResult['fileLength'] . PHP_EOL;
echo "----------------------------" . PHP_EOL;
echo "Leitura usando Fiber" . PHP_EOL;
echo "Memória utilizada: " . $fiberResult['memory'] . PHP_EOL;
echo "Tamanho do arquivo: " . $fiberResult['fileLength'] . PHP_EOL;
echo "----------------------------" . PHP_EOL;
