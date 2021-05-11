<?php

namespace App\Service\Tasklet;

interface ReaderInterface
{
    public function open();

    public function read();
}
