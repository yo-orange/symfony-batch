<?php

namespace App\Service\ExportProduct;

use App\Entity\Product;
use App\Service\Tasklet\SpreadsheetWriter;

class ExportProductWriter extends SpreadsheetWriter
{
    public function __construct(string $projectDir)
    {
        parent::__construct();

        $this->projectDir = $projectDir;
        $this
            ->setWriterType("Csv")
            ->setFilePath("{$this->projectDir}/var/log/product.csv")
            ->setEnclosure("\"");
    }

    /**
     * mapping.
     *
     * @param Product $model
     * @return array
     */
    public function mapping($model): array
    {
        return [
            1 => $model->getId(),
            2 => $model->getName(),
        ];
    }
}
