<?php

namespace App\Service\ImportProduct;

use App\Service\Tasklet\SpreadsheetReader;

class ImportProductReader extends SpreadsheetReader
{
    public function __construct(string $projectDir)
    {
        parent::__construct();

        $this->setFilePath("{$projectDir}/var/log/product.csv");
    }
}
