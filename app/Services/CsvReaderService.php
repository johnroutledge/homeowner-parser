<?php

declare(strict_types=1);

namespace App\Services;

use RuntimeException;

final class CsvReaderService
{
    public function read(string $filePath): array
    {
        if (!is_file($filePath)) {
            throw new RuntimeException("CSV file not found: {$filePath}");
        }

        $handle = fopen($filePath, 'r');
        if ($handle === false) {
            throw new RuntimeException("Unable to open CSV file: {$filePath}");
        }

        try {
            fgetcsv($handle);

            $rows = [];

            while (($data = fgetcsv($handle)) !== false) {
                $value = trim((string)($data[0] ?? ''));

                if ($value !== '') {
                    $rows[] = $value;
                }
            }

            return $rows;
        } finally {
            fclose($handle);
        }
    }
}
