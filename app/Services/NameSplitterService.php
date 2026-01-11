<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\Title;

final class NameSplitterService
{
    public function split(string $nameString): array
    {
        $parts = preg_split('/\s+(?:and|&)\s+/i', trim($nameString)) ?: [];

        $result = [];
        $sharedRemainder = null;

        foreach ($parts as $part) {
            $part = trim($part);
            if ($part === '') {
                continue;
            }

            $words = preg_split('/\s+/', $part) ?: [];

            if (count($words) === 1 && $this->isTitle($words[0])) {
                $result[] = $part;
                continue;
            }

            $result[] = $part;

            if (count($words) >= 2 && $this->isTitle($words[0])) {
                $sharedRemainder = implode(' ', array_slice($words, 1));
            }

            for ($i = count($result) - 2; $i >= 0; $i--) {
                $prevWords = preg_split('/\s+/', $result[$i]) ?: [];

                if (count($prevWords) === 1 && $this->isTitle($prevWords[0]) && $sharedRemainder) {
                    $result[$i] = $result[$i] . ' ' . $sharedRemainder;
                } else {
                    break;
                }
            }
        }

        return $result;
    }

    private function isTitle(string $word): bool
    {
        return Title::fromString($word) !== null;
    }
}
