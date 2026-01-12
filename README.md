# Homeowner CSV Parser

Laravel-based CLI application that parses homeowner names from a CSV file and outputs structured JSON.

This project was implemented as a technical test and intentionally supports only the name formats present in the provided example CSV.

---

## Requirements

- PHP 8.2 or higher
- Composer

---

## Setup

Install dependencies:

```bash
composer install
```

Create the environment file and application key:

```bash
cp .env.example .env
php artisan key:generate
```

---

## Running the Application

Run the parser from the command line using Artisan:

```bash
php artisan parse:homeowners path/to/file.csv
```

### CSV file location

- The CSV file can be placed anywhere on your filesystem
- You may pass either a relative or absolute path

Examples:

```bash
php artisan parse:homeowners storage/app/homeowners.csv
php artisan parse:homeowners ./example.csv
php artisan parse:homeowners /full/path/to/example.csv
```

The command reads the CSV, parses homeowner names, and outputs formatted JSON to STDOUT.

---

## Testing

A small, focused unit test suite is included for the core parsing logic.

Run tests with:

```bash
php artisan test
```

---

## Project Structure

Key application components relevant to this exercise:

```
app/
├── Console/Commands/ParseHomeownersCommand.php
├── DTOs/PersonNameDto.php
├── Enums/Title.php
├── Services/
│ ├── CsvReaderService.php
│ ├── HomeownerParserService.php
│ ├── IndividualNameParserService.php
│ └── NameSplitterService.php
tests/
└── Unit/Services/IndividualNameParserTest.php
```

---

## Use of AI

AI tooling was used in a limited, supportive capacity during development.

Specifically, it was used to sanity-check that the final JSON output matched the required format described in the test brief and to validate edge cases already present in the example CSV (such as shared surnames across multiple titles and shared first names for couples with different titles).

It was also used as a productivity aid when scaffolding a small, focused unit test suite and for reviewing documentation clarity.

---

## Implementation Notes

- Parsing logic is implemented using small, single-responsibility services
- CsvReaderService handles CSV I/O only
- NameSplitterService handles household-level name splitting
- IndividualNameParserService parses a single name into a DTO
- Titles are normalised using a PHP enum to avoid duplication
- The Artisan command acts as the presentation layer and is responsible for JSON output
- The implementation deliberately supports only the formats present in the example CSV in order to respect the two-hour scope of the exercise.
