<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Helper;

use InvalidArgumentException;
use Iterator;
use SplFileObject;

class CsvReaderHelper
{

    private SplFileObject $file;

    public function __construct(string $filePath, string $delimiter = ';', string $enclosure = '"', string $escape = '\\')
    {
        if (!file_exists($filePath)) {
            throw new InvalidArgumentException("Arquivo \"{$filePath}\" não encontrado.");
        }

        $this->file = new SplFileObject($filePath);
        $this->file->setFlags(SplFileObject::READ_CSV | SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE);
        $this->file->setCsvControl($delimiter, $enclosure, $escape);
    }

    /** @return Iterator */
    public function getIterator()
    {
        foreach ($this->file as $row) {
            yield $row;
        }
    }

    public function eof(): bool
    {
        return $this->file->eof();
    }
}
