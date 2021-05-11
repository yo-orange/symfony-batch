<?php

namespace App\Service\ExportProduct;

use App\Repository\ProductRepository;
use App\Service\Tasklet\ReaderInterface;

class ExportProductReader implements ReaderInterface
{

    private $records;

    public function __construct(
        ProductRepository $repository
    ) {
        $this->records = $repository->findAllIterator();
    }

    public function open() {
    }

    public function read()
    {
        if (($record = $this->records->next()) !== false) {
            return $record[0];
        }
        return null;
    }
}
