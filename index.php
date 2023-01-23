<?php

use App\FileReaderGenerator;
use App\FileReaderFiber;

require_once './vendor/autoload.php';

function runReaderGenerator(): void
{
	$generatorReader = new FileReaderGenerator('./bigger-file.csv');
	$generatorContents = $generatorReader->readfile();

	foreach ($generatorContents as $line) {
		if (!empty($line)) {
			printf('%s fez %d let it rip %s', $line[0], $line[1], PHP_EOL);	
		}
	}

	echo "Leitura usando Generator";
	echo "Memória utilizada: " . round(memory_get_peak_usage() / 1024, 0) . "KB" . PHP_EOL;
	echo "Tamanho do arquivo: " . round(filesize('./bigger-file.csv') / 1024 / 1024, 0) . "MB" . PHP_EOL;
}

function runReaderFiber(): void
{	
	$fiberReader = new FileReaderFiber('./bigger-file.csv');
	$fiberContents = new Fiber(function () use ($fiberReader) {
	    return $fiberReader->readfile();
	});

	$fiberContents->start();
	while ($fiberLine = $fiberContents->resume()) {
	    printf('%s fez %d let it rip %s', $fiberLine[0], $fiberLine[1], PHP_EOL);
	}

	echo "Leitura usando Fiber";
	echo "Memória utilizada: " . round(memory_get_peak_usage() / 1024, 0) . "KB" . PHP_EOL;
	echo "Tamanho do arquivo: " . round(filesize('./bigger-file.csv') / 1024 / 1024, 0) . "MB" . PHP_EOL;
}

echo "----------------------------" . PHP_EOL;
runReaderGenerator();
echo "----------------------------" . PHP_EOL;
runReaderFiber();
echo "----------------------------" . PHP_EOL;
