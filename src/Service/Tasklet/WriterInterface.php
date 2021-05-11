<?php

namespace App\Service\Tasklet;

interface WriterInterface
{
    public function write($model);

    public function close();

}
