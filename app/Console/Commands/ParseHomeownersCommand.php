<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\HomeownerParserService;
use Illuminate\Console\Command;
use Throwable;

final class ParseHomeownersCommand extends Command
{
    protected $signature = 'parse:homeowners {file : Path to the CSV file}';

    protected $description = 'Parse homeowner names from CSV and output as JSON';

    public function handle(HomeownerParserService $parser): int
    {
        $filePath = (string) $this->argument('file');

        if (!is_file($filePath)) {
            $this->error("File not found: {$filePath}");
            return self::FAILURE;
        }

        try {
            $this->info('Parsing homeowner names...');

            $people = $parser->parseFile($filePath);

            $data = array_map(
                static fn ($person) => $person->toArray(),
                $people
            );

            $this->line(json_encode($data, JSON_PRETTY_PRINT));

            $this->info("\nSuccessfully parsed " . count($people) . " people.");

            return self::SUCCESS;

        } catch (Throwable $e) {
            $this->error('Error parsing file: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
