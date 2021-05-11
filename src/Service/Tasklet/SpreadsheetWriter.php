<?php

namespace App\Service\Tasklet;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

abstract class SpreadsheetWriter implements WriterInterface
{
    private Spreadsheet $spreadsheet;

    private int $rowIndex;

    private ?string $writerType = null;

    private ?string $delimiter = null;

    private ?string $enclosure = null;

    private ?string $lineEnding = null;

    private ?bool $useBOM = null;

    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet();
        $this->rowIndex = 1;
    }

    public function write($model)
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        $mapping = $this->mapping($model);
        foreach ($mapping as $index => $value) {
            $sheet->setCellValueByColumnAndRow($index, $this->rowIndex, $value);
        }
        $this->rowIndex++;
    }

    public function close()
    {
        $writer = IOFactory::createWriter($this->spreadsheet, $this->writerType);
        if ($writer instanceof Csv) {
            if ($this->delimiter !== null) $writer->setDelimiter($this->delimiter);
            if ($this->enclosure !== null) $writer->setEnclosure($this->enclosure);
            if ($this->lineEnding !== null) $writer->setLineEnding($this->lineEnding);
            if ($this->useBOM !== null) $writer->setUseBOM($this->useBOM);
        }
        $writer->save($this->filePath);
    }

    public function setFilePath(string $filePath)
    {
        $this->filePath = $filePath;
        return $this;
    }

    public function setWriterType(string $writerType)
    {
        $this->writerType = $writerType;
        return $this;
    }

    /**
     * Set delimiter.
     *
     * @param string $pValue Delimiter, defaults to ','
     *
     * @return $this
     */
    public function setDelimiter(string $pValue)
    {
        $this->delimiter = $pValue;
        return $this;
    }

    /**
     * Set enclosure.
     *
     * @param string $pValue Enclosure, defaults to "
     *
     * @return $this
     */
    public function setEnclosure(string $pValue = '"')
    {
        $this->enclosure = $pValue;
        return $this;
    }

    /**
     * Set line ending.
     *
     * @param string $pValue Line ending, defaults to OS line ending (PHP_EOL)
     *
     * @return $this
     */
    public function setLineEnding(string $pValue)
    {
        $this->lineEnding = $pValue;
        return $this;
    }

    /**
     * Set whether BOM should be used.
     *
     * @param bool $pValue Use UTF-8 byte-order mark? Defaults to false
     *
     * @return $this
     */
    public function setUseBOM(bool $pValue)
    {
        $this->useBOM = $pValue;
        return $this;
    }

    abstract function mapping($model): array;
}
