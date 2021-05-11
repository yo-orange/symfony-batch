<?php

namespace App\Service\Tasklet;

class NoOpProcessor implements ProcessorInterface
{
    public function process($input)
    {
        return $input;
    }
}
