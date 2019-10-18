<?php

namespace App;

class File
{
    public function openFile($fileName)
    {
        return fopen($fileName, "w");

        // if (is_null($file)) {
        //     Message::error("The file".$fileName.' not found.');
        // }
    }

    public function closeFile($fileName)
    {
        return fclose($fileName);
    }
}