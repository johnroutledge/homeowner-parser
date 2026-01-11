<?php

declare(strict_types=1);

namespace App\DTOs;

class PersonNameDto
{
    public function __construct(
        public readonly string $title,
        public readonly ?string $first_name,
        public readonly ?string $initial,
        public readonly string $last_name,
    ) {}

    /**
     * @return array{
     *     title: string,
     *     first_name: ?string,
     *     last_name: string,
     *     initial: ?string
     * }
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'initial' => $this->initial
        ];
    }
}