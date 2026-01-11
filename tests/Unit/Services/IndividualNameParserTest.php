<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\IndividualNameParserService;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class IndividualNameParserTest extends TestCase
{
    private IndividualNameParserService $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parser = new IndividualNameParserService();
    }

    #[Test]
    public function it_parses_full_name_with_title(): void
    {
        $result = $this->parser->parse('Mr John Smith');

        $this->assertEquals('Mr', $result->title);
        $this->assertEquals('John', $result->first_name);
        $this->assertEquals('Smith', $result->last_name);
        $this->assertNull($result->initial);
    }

    #[Test]
    public function it_parses_name_with_initial(): void
    {
        $result = $this->parser->parse('Mr M Mackie');

        $this->assertEquals('Mr', $result->title);
        $this->assertNull($result->first_name);
        $this->assertEquals('M', $result->initial);
        $this->assertEquals('Mackie', $result->last_name);
    }

    #[Test]
    public function it_parses_name_with_initial_and_period(): void
    {
        $result = $this->parser->parse('Mr F. Fredrickson');

        $this->assertEquals('Mr', $result->title);
        $this->assertNull($result->first_name);
        $this->assertEquals('F', $result->initial);
        $this->assertEquals('Fredrickson', $result->last_name);
    }

    #[Test]
    public function it_normalizes_mister_to_mr(): void
    {
        $result = $this->parser->parse('Mister John Doe');

        $this->assertEquals('Mr', $result->title);
        $this->assertEquals('John', $result->first_name);
        $this->assertEquals('Doe', $result->last_name);
    }

    #[Test]
    public function it_parses_mrs_title(): void
    {
        $result = $this->parser->parse('Mrs Jane Smith');

        $this->assertEquals('Mrs', $result->title);
        $this->assertEquals('Jane', $result->first_name);
        $this->assertEquals('Smith', $result->last_name);
    }

    #[Test]
    public function it_parses_dr_title(): void
    {
        $result = $this->parser->parse('Dr P Gunn');

        $this->assertEquals('Dr', $result->title);
        $this->assertNull($result->first_name);
        $this->assertEquals('P', $result->initial);
        $this->assertEquals('Gunn', $result->last_name);
    }

    #[Test]
    public function it_parses_prof_title(): void
    {
        $result = $this->parser->parse('Prof Alex Brogan');

        $this->assertEquals('Prof', $result->title);
        $this->assertEquals('Alex', $result->first_name);
        $this->assertEquals('Brogan', $result->last_name);
    }

    #[Test]
    public function it_parses_ms_title(): void
    {
        $result = $this->parser->parse('Ms Claire Robbo');

        $this->assertEquals('Ms', $result->title);
        $this->assertEquals('Claire', $result->first_name);
        $this->assertEquals('Robbo', $result->last_name);
    }

    #[Test]
    public function it_parses_hyphenated_last_names(): void
    {
        $result = $this->parser->parse('Mrs Faye Hughes-Eastwood');

        $this->assertEquals('Mrs', $result->title);
        $this->assertEquals('Faye', $result->first_name);
        $this->assertEquals('Hughes-Eastwood', $result->last_name);
    }

    #[Test]
    public function it_parses_title_and_last_name_only(): void
    {
        $result = $this->parser->parse('Mr Smith');

        $this->assertEquals('Mr', $result->title);
        $this->assertNull($result->first_name);
        $this->assertNull($result->initial);
        $this->assertEquals('Smith', $result->last_name);
    }

    #[Test]
    public function it_throws_for_empty_input(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->parser->parse('');
    }

    #[Test]
    public function it_throws_for_unknown_title(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->parser->parse('Sir John Smith');
    }
}
