<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use PHPUnit\Framework\TestCase;

use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryTest extends TestCase
{

    private $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->category = new Category();
    }

    public function testFillableAttribute()
    {

        $fillable = ['name', 'description', 'is_active'];

        $this->assertEquals(
            $fillable,
            $this->category->getFillable()
        );
    }

    public function testIfUseTraitsAttribute()
    {
        $traits = [
            SoftDeletes::class, Uuid::class
        ];

        $categoryTraits = array_keys(class_uses(Category::class));

        $this->assertEquals($traits, $categoryTraits);
    }

    public function testCastsAttribute()
    {

        $casts = ['id' => 'string'];

        $this->assertEquals(
            $casts,
            $this->category->getCasts()
        );
    }

    public function testIncrementingAttribute()
    {
        $this->assertFalse(
            $this->category->incrementing
        );
    }

    public function testDatesAttribute()
    {
        $dates = ['deleted_at', 'created_at', 'updated_at'];


        $category_dates = $this->category->getDates();

        foreach ($dates as $date) {
            $this->assertContains($date, $category_dates);
        }

        $this->assertCount(count($dates), $category_dates);
    }
}
