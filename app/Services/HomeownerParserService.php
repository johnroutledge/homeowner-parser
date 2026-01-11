<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\PersonNameDTO;

class HomeownerParserService
{
    public function __construct(
        private CsvReaderService $csvReader,
        private NameSplitterService $nameSplitter,
        private IndividualNameParserService $individualParser
    ) {}

    public function parseFile(string $filePath): array
    {
        $rows = $this->csvReader->read($filePath);
        
        $people = [];
        
        foreach ($rows as $row) {
            $individualNames = $this->nameSplitter->split($row);
            
            foreach ($individualNames as $name) {
                $people[] = $this->individualParser->parse($name);
            }
        }
        
        return $people;
    }
}