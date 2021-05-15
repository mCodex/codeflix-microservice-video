<?php

namespace Tests\Feature\Feature\Models;

use App\Models\Category;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testList()
    {
        factory(Category::class, 1)->create();

        $categories = Category::all();

        $this->assertCount(1, $categories);
    }

    public function testCreate()
    {
        $category = Category::create([
            'name' => 'test1'
        ]);

        $category->refresh();

        $this->assertEquals('test1', $category->name);
        $this->assertNull($category->description);
        $this->assertTrue($category->is_active);

        $category = Category::create([
            'name' => 'test1',
            'description' => null
        ]);

        $this->assertNull($category->description);

        $category = Category::create([
            'name' => 'test1',
            'description' => 'description'
        ]);

        $this->assertEquals('description', $category->description);


        $category = Category::create([
            'name' => 'test1',
            'description' => 'description',
            'is_active' => false
        ]);

        $this->assertFalse($category->is_active);
    }
}
