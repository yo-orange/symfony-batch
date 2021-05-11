<?php

namespace App\Service\ImportProduct;

use App\Service\Tasklet\WriterInterface;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;
use Psr\Log\LoggerInterface;

class ImportProductWriter implements WriterInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     *
     * @param Row $model
     * @return void
     */
    public function write($model)
    {

        $values = [];

        $cells = $model->getCellIterator();
        while ($cells->valid()) {
            $cell = $cells->current();
            $values[] = $cell->getValue();
            $cells->next();
        }

        $this->logger->info(implode(",", $values));
    }

    public function close()
    {
    }
}
