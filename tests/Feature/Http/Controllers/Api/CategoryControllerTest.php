<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Lang;
use Tests\TestCase;
use Tests\Traits\TestValidations;

class CategoryControllerTest extends TestCase
{
    use DatabaseMigrations, TestValidations;

    private $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = factory(Category::class)->create();
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->get(route('categories.index'));

        $response->assertStatus(200)->assertJson([$this->category->toArray()]);
    }

    public function testShow()
    {
        $response = $this->get(route('categories.show', ['category' => $this->category->id]));

        $response->assertStatus(200)->assertJson($this->category->toArray());
    }

    public function testInvalidationData()
    {
        $data = [
            'name' => ''
        ];

        $this->assertInvalidationInStoreAction($data, 'required');
        $this->assertInvalidationInUpdateAction($data, 'required');

        //----------

        $data = [
            'name' => str_repeat('a', 500),
        ];

        $this->assertInvalidationInStoreAction($data, 'max.string', ['max' => 255]);
        $this->assertInvalidationInUpdateAction($data, 'max.string', ['max' => 255]);


        $data = [
            'is_active' => 'a'
        ];

        $this->assertInvalidationInStoreAction($data, 'boolean');
        $this->assertInvalidationInUpdateAction($data, 'boolean');
    }

    protected function assertInvalidationRequired(TestResponse $response)
    {
        $this->assertInvalidationFields($response, ['name'], 'required');
        $response
            ->assertJsonMissingValidationErrors(['is_active']);
    }

    protected function assertInvalidationMax(TestResponse $response)
    {
        $this->assertInvalidationFields($response, ['name'], 'max.string', ['max' => 255]);
    }

    protected function assertInvalidationBoolean(TestResponse $response)
    {
        $this->assertInvalidationFields($response, ['is_active'], 'boolean', ['is active']);
    }

    public function testStore()
    {
        $response = $this->json('POST', route('categories.store'), [
            'name' => 'test',
        ]);

        $id  = $response->json('id');

        $category = Category::find($id);

        $response->assertStatus(201)
            ->assertJson($category->toArray());

        $this->assertTrue($response->json('is_active'));
        $this->assertNull($response->json('description'));


        //----------

        $response = $this->json('POST', route('categories.store'), [
            'name' => 'test',
            'description' => 'description',
            'is_active' => false
        ]);

        $response->assertJsonFragment([
            'is_active' => false,
            'description' => 'description'
        ]);
    }


    public function testUpdate()
    {
        $category = factory(Category::class)->create(['description' => 'description', 'is_active' => false]);

        $response = $this->json(
            'PUT',
            route('categories.update', [
                'category' => $category->id
            ]),
            [
                'name' => 'test',
                'description' => 'test',
                'is_active' => true
            ]
        );

        $id  = $response->json('id');

        $category = Category::find($id);

        $response->assertStatus(200)
            ->assertJson($category->toArray())
            ->assertJsonFragment([
                'description' => 'test',
                'is_active' => true
            ]);


        $response = $this->json(
            'PUT',
            route('categories.update', [
                'category' => $category->id
            ]),
            [
                'name' => 'test',
                'description' => '',

            ]
        );

        $response->assertStatus(200)
            ->assertJsonFragment([
                'description' => null,
            ]);


        $category->description = 'test';
        $category->save();

        $response = $this->json(
            'PUT',
            route('categories.update', [
                'category' => $category->id
            ]),
            [
                'name' => 'test',
                'description' => null,

            ]
        );

        $response->assertStatus(200)
            ->assertJsonFragment([
                'description' => null,
            ]);
    }

    public function testDestroy()
    {
        $response = $this->json(
            'DELETE',
            route('categories.destroy', [
                'category' => $this->category->id
            ])
        );

        $response->assertStatus(204);

        $this->assertNull(Category::find($this->category->id));

        $this->assertNotNull(Category::withTrashed()->find($this->category->id));
    }

    protected function routeStore()
    {
        return route('categories.store');
    }


    protected function routeUpdate()
    {
        return route('categories.update', ['category' => $this->category->id]);
    }
}
