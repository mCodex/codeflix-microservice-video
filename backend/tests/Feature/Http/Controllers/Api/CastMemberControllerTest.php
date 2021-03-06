<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\CastMember;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestResponse;

use Tests\TestCase;

use Tests\Traits\TestValidations;
use Tests\Traits\TestSaves;

class CastMemberControllerTest extends TestCase
{
    use DatabaseMigrations, TestValidations, TestSaves;

    private $castMember;

    protected function setUp(): void
    {
        parent::setUp();

        $this->castMember = factory(CastMember::class)->create([
            'type' => CastMember::TYPE_DIRECTOR
        ]);
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->get(route('cast_members.index'));

        $response->assertStatus(200)->assertJson([$this->castMember->toArray()]);
    }

    public function testShow()
    {
        $response = $this->get(route('cast_members.show', ['cast_member' => $this->castMember->id]));

        $response->assertStatus(200)->assertJson($this->castMember->toArray());
    }

    public function testInvalidationData()
    {
        $data = [
            'name' => '',
            'type' => ''
        ];

        $this->assertInvalidationInStoreAction($data, 'required');
        $this->assertInvalidationInUpdateAction($data, 'required');

        //----------

        $data = [
            'type' => 'NOT_EXISTS'
        ];

        $this->assertInvalidationInStoreAction($data, 'in');
        $this->assertInvalidationInUpdateAction($data, 'in');
    }

    protected function assertInvalidationRequired(TestResponse $response)
    {
        $this->assertInvalidationFields($response, ['name'], 'required');
    }

    protected function assertInvalidationMax(TestResponse $response)
    {
        $this->assertInvalidationFields($response, ['name'], 'max.string', ['max' => 255]);
    }

    public function testStore()
    {

        $data = [
            [
                'name' => 'test',
                'type' => CastMember::TYPE_DIRECTOR
            ],
            [
                'name' => 'test',
                'type' => CastMember::TYPE_ACTOR
            ]
        ];

        foreach ($data as $key => $value) {
            $response = $this->assertStore($value, $value + ['deleted_at' => null]);
            $response->assertJsonStructure([
                'created_at', 'updated_at'
            ]);
        }
    }


    public function testUpdate()
    {
        $data = [
            'name' => 'actor name updated',
            'type' => CastMember::TYPE_ACTOR
        ];

        $response = $this->assertUpdate($data, $data + ['deleted_at' => null]);

        $response->assertJsonStructure([
            'created_at', 'deleted_at'
        ]);
    }

    public function testDestroy()
    {
        $response = $this->json(
            'DELETE',
            route('cast_members.destroy', [
                'cast_member' => $this->castMember->id
            ])
        );

        $response->assertStatus(204);

        $this->assertNull(CastMember::find($this->castMember->id));

        $this->assertNotNull(CastMember::withTrashed()->find($this->castMember->id));
    }

    protected function routeStore()
    {
        return route('cast_members.store');
    }


    protected function routeUpdate()
    {
        return route('cast_members.update', ['cast_member' => $this->castMember->id]);
    }

    protected function model()
    {
        return CastMember::class;
    }
}
