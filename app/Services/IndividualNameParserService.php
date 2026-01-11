<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\PersonNameDTO;
use App\Enums\Title;
use InvalidArgumentException;

class IndividualNameParserService
{
    public function parse(string $nameString): PersonNameDTO
    {
        $parts = $this->splitNameIntoParts($nameString);
        
        $title = $this->extractTitle($parts);
        $remainingParts = $this->removeTitle($parts);
        
        if (count($remainingParts) === 0) {
            throw new InvalidArgumentException("Invalid name format: {$nameString}");
        }

        $lastName = array_pop($remainingParts);
        
        $firstName = null;
        $initial = null;
        
        if (count($remainingParts) > 0) {
            $firstPart = $remainingParts[0];
            
            if ($this->isInitial($firstPart)) {
                $initial = $this->normalizeInitial($firstPart);
            } else {
                $firstName = $firstPart;
            }
        }

        return new PersonNameDTO(
            title: $title,
            first_name: $firstName,
            initial: $initial,
            last_name: $lastName
        );
    }

    private function splitNameIntoParts(string $nameString): array
    {
        return array_filter(
            explode(' ', trim($nameString)),
            fn($part) => !empty($part)
        );
    }

    private function extractTitle(array $parts): string
    {
        if (empty($parts)) {
            throw new InvalidArgumentException('Cannot extract title from empty name');
        }

        $title = Title::fromString($parts[0]);

        if ($title === null) {
            throw new InvalidArgumentException("Unrecognised title: {$parts[0]}");
        }

        return $title->value;
    }

    private function removeTitle(array $parts): array
    {
        array_shift($parts);
        return array_values($parts);
    }

    private function isInitial(string $part): bool
    {
        $cleaned = str_replace('.', '', $part);
        
        return strlen($cleaned) === 1 && ctype_alpha($cleaned);
    }

    private function normalizeInitial(string $initial): string
    {
        $cleaned = str_replace('.', '', $initial);
        return strtoupper($cleaned);
    }
}