<?php

namespace App;

abstract class Message 
{
    public function error($message)
    {
        fwrite(STDOUT, PHP_EOL.$message.PHP_EOL);
        exit(1);
    }
}