<?php

namespace App;

class FileReaderGenerator
{
	private $file;

	public function __construct(string $fileName)
	{
		$this->file = fopen($fileName, 'r');
	}

	public function readFile(): iterable
	{
		while (!feof($this->file)) {
			yield fgetcsv($this->file);
		}
	}

	public function __destruct()
	{
		fclose($this->file);
	}
}
