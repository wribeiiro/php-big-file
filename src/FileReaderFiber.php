<?php

namespace App;

use Fiber;

class FileReaderFiber
{
	private $file;

	public function __construct(string $fileName)
	{
		$this->file = fopen($fileName, 'r');
	}

	public function readFile(): iterable
	{
		while (!feof($this->file)) {
            Fiber::suspend(fgetcsv($this->file));
        }
	}

	public function __destruct()
	{
		fclose($this->file);
	}
}
