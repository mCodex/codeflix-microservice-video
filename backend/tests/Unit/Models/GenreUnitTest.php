<?php

namespace Tests\Unit\Models;

use App\Models\Genre;
use PHPUnit\Framework\TestCase;

use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class GenreTest extends TestCase
{

    private $genre;

    protected function setUp(): void
    {
        parent::setUp();
        $this->genre = new Genre();
    }

    public function testFillableAttribute()
    {

        $fillable = ['name', 'is_active'];

        $this->assertEquals(
            $fillable,
            $this->genre->getFillable()
        );
    }

    public function testIfUseTraitsAttribute()
    {
        $traits = [
            SoftDeletes::class, Uuid::class
        ];

        $genreTraits = array_keys(class_uses(Genre::class));

        $this->assertEquals($traits, $genreTraits);
    }

    public function testCastsAttribute()
    {

        $casts = ['id' => 'string', 'is_active' => 'boolean'];

        $this->assertEquals(
            $casts,
            $this->genre->getCasts()
        );
    }

    public function testIncrementingAttribute()
    {
        $this->assertFalse(
            $this->genre->incrementing
        );
    }

    public function testDatesAttribute()
    {
        $dates = ['deleted_at', 'created_at', 'updated_at'];


        $genre_dates = $this->genre->getDates();

        foreach ($dates as $date) {
            $this->assertContains($date, $genre_dates);
        }

        $this->assertCount(count($dates), $genre_dates);
    }
}
