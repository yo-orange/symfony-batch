<?php

namespace App\Service\Tasklet;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\RowIterator;

class SpreadsheetReader implements ReaderInterface
{
    private ?Spreadsheet $spreadsheet;

    private ?RowIterator $rowIterator;

    private ?string $filePath;

    public function __construct()
    {
        $this->rowIndex = 1;
    }

    public function open()
    {
        $this->spreadsheet = IOFactory::load($this->filePath);
        $this->rowIterator = $this->spreadsheet->getActiveSheet()->getRowIterator();
    }

    public function read()
    {
        if (!($this->rowIterator->valid())) {
            return null;
        }

        $row = $this->rowIterator->current();
        $this->rowIterator->next();
        return $row;
    }

    /**
     * file path.
     *
     * @param string $filePath file path
     *
     * @return $this
     */
    public function setFilePath(string $filePath)
    {
        $this->filePath = $filePath;
        return $this;
    }
}
