<?php

namespace Tests\Unit\Models;

use App\Models\CastMember;
use PHPUnit\Framework\TestCase;

use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class CastMemberTest extends TestCase
{

    private $castMember;

    protected function setUp(): void
    {
        parent::setUp();
        $this->castMember = new CastMember();
    }

    public function testFillableAttribute()
    {

        $fillable = ['name', 'type'];

        $this->assertEquals(
            $fillable,
            $this->castMember->getFillable()
        );
    }

    public function testCastsAttribute()
    {

        $casts = ['id' => 'string', 'type' => 'integer'];

        $this->assertEquals(
            $casts,
            $this->castMember->getCasts()
        );
    }

    public function testIfUseTraitsAttribute()
    {
        $traits = [
            SoftDeletes::class, Uuid::class
        ];

        $castMemberTraits = array_keys(class_uses(CastMember::class));

        $this->assertEquals($traits, $castMemberTraits);
    }

    public function testIncrementingAttribute()
    {
        $this->assertFalse(
            $this->castMember->incrementing
        );
    }

    public function testDatesAttribute()
    {
        $dates = ['deleted_at', 'created_at', 'updated_at'];


        $castMember_dates = $this->castMember->getDates();

        foreach ($dates as $date) {
            $this->assertContains($date, $castMember_dates);
        }

        $this->assertCount(count($dates), $castMember_dates);
    }
}
