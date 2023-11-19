<?php

namespace App\Application\Tools;

class StringWrapper
{
    public function fileGetContents(string $filename): string
    {
        return file_get_contents($filename);
    }
}
