<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Controllers\Api\VideoController;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Video;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Request;
use Mockery;

use Tests\TestCase;

use Tests\Exceptions\TestException;

use Tests\Traits\TestValidations;
use Tests\Traits\TestSaves;

class VideoControllerTest extends TestCase
{
    use DatabaseMigrations, TestValidations, TestSaves;

    private $video;

    private $sendData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->video = factory(Video::class)->create([
            'opened' => false
        ]);

        $this->sendData = [
            'title' => 'title',
            'description' => 'description',
            'year_launched' => 2010,
            'rating' => Video::RATING_LIST[0],
            'duration' => 90
        ];
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->get(route('videos.index'));

        $response->assertStatus(200)->assertJson([$this->video->toArray()]);
    }

    public function testShow()
    {
        $response = $this->get(route('videos.show', ['video' => $this->video->id]));

        $response->assertStatus(200)->assertJson($this->video->toArray());
    }

    public function testInvalidationRequired()
    {
        $data = [
            'title' => '',
            'description' => '',
            'year_launched' => '',
            'rating' => '',
            'duration' => '',
            'categories_id' => '',
            'genres_id' => ''
        ];

        $this->assertInvalidationInStoreAction($data, 'required');
        $this->assertInvalidationInUpdateAction($data, 'required');
    }

    public function testInvalidationMax()
    {
        $data = [
            'title' => str_repeat('a', 300)
        ];

        $this->assertInvalidationInStoreAction($data, 'max.string', ['max' => 255]);
        $this->assertInvalidationInUpdateAction($data, 'max.string', ['max' => 255]);
    }

    public function testInvalidationInteger()
    {
        $data = [
            'duration' => 'a'
        ];

        $this->assertInvalidationInStoreAction($data, 'integer');
        $this->assertInvalidationInUpdateAction($data, 'integer');
    }

    public function testInvalidationYearLaunchedField()
    {
        $data = [
            'year_launched' => 'invalid year'
        ];

        $this->assertInvalidationInStoreAction($data, 'date_format', ['format' => 'Y']);
        $this->assertInvalidationInUpdateAction($data, 'date_format', ['format' => 'Y']);
    }

    public function testInvalidationCategoriesIdField()
    {
        $data = [
            'categories_id' => 'a'
        ];

        $invalidIdsArray = [
            'categories_id' => [200]
        ];

        $this->assertTableRelationship($data, $invalidIdsArray);



        $category = factory(Category::class)->create();
        $category->delete();

        $data = [
            'categories_id' => [$category->id]
        ];

        $this->assertInvalidationInStoreAction($data, 'exists');
        $this->assertInvalidationInUpdateAction($data, 'exists');
    }

    public function testInvalidationGenresIdField()
    {
        $data = [
            'genres_id' => 'a'
        ];

        $invalidIdsArray = [
            'genres_id' => [200]
        ];

        $this->assertTableRelationship($data, $invalidIdsArray);

        $genre = factory(Genre::class)->create();
        $genre->delete();

        $data = [
            'genres_id' => [$genre->id]
        ];

        $this->assertInvalidationInStoreAction($data, 'exists');
        $this->assertInvalidationInUpdateAction($data, 'exists');
    }

    private function assertTableRelationship($invalidArrayData, $invalidIdsArray)
    {
        $this->assertInvalidationInStoreAction($invalidArrayData, 'array');
        $this->assertInvalidationInUpdateAction($invalidArrayData, 'array');

        $this->assertInvalidationInStoreAction($invalidIdsArray, 'exists');
        $this->assertInvalidationInUpdateAction($invalidIdsArray, 'exists');
    }


    public function testInvalidationOpenedField()
    {
        $data = [
            'opened' => 'not a boolean'
        ];

        $this->assertInvalidationInStoreAction($data, 'boolean');
        $this->assertInvalidationInUpdateAction($data, 'boolean');
    }

    public function testInvalidationRatingField()
    {
        $data = [
            'rating' => 0
        ];

        $this->assertInvalidationInStoreAction($data, 'in');
        $this->assertInvalidationInUpdateAction($data, 'in');
    }

    public function testSave()
    {
        $category = factory(Category::class)->create();
        $genre = factory(Genre::class)->create();
        $genre->categories()->sync($category->id);

        $insertionData = $this->sendData + [
            'categories_id' => [$category->id],
            'genres_id' => [$genre->id]
        ];

        $data = [
            [
                'send_data' => $insertionData,
                'test_data' => $this->sendData + ['opened' => false]
            ],
            [
                'send_data' => $insertionData + ['opened' => true],
                'test_data' => $this->sendData + ['opened' => true]
            ],
            [
                'send_data' => $insertionData + ['rating' => Video::RATING_LIST[1]],
                'test_data' => $this->sendData + ['rating' => Video::RATING_LIST[1]]
            ],
        ];

        foreach ($data as $key => $value) {
            $response = $this->assertStore(
                $value['send_data'],
                $value['test_data'] + ['deleted_at' => null]
            );
            $response->assertJsonStructure([
                'created_at', 'updated_at'
            ]);

            $this->assertHasCategory(
                $response->json('id'),
                $value['send_data']['categories_id'][0]
            );

            $this->assertHasGenre(
                $response->json('id'),
                $value['send_data']['genres_id'][0]
            );

            $response = $this->assertUpdate(
                $value['send_data'],
                $value['test_data'] + ['deleted_at' => null]
            );
            $response->assertJsonStructure([
                'created_at', 'updated_at'
            ]);
        }
    }

    protected function assertHasCategory($videoId, $categoryId)
    {
        $this->assertDatabaseHas('category_video', [
            'video_id' => $videoId,
            'category_id' => $categoryId
        ]);
    }

    protected function assertHasGenre($videoId, $genreId)
    {
        $this->assertDatabaseHas('genre_video', [
            'video_id' => $videoId,
            'genre_id' => $genreId
        ]);
    }

    public function testSyncCategories()
    {
        $categoriesId = factory(Category::class, 3)->create()->pluck('id')->toArray();
        $genre = factory(Genre::class)->create();
        $genre->categories()->sync($categoriesId);
        $genreId = $genre->id;

        $response = $this->json(
            'POST',
            $this->routeStore(),
            $this->sendData + [
                'genres_id' => [$genreId],
                'categories_id' => [$categoriesId[0]]
            ]
        );

        $this->assertDatabaseHas('category_video', [
            'category_id' => $categoriesId[0],
            'video_id' => $response->json('id')
        ]);

        $response = $this->json(
            'PUT',
            route('videos.update', ['video' => $response->json('id')]),
            $this->sendData + [
                'genres_id' => [$genreId],
                'categories_id' => [$categoriesId[1], $categoriesId[2]],
            ]
        );

        $this->assertDatabaseMissing('category_video', [
            'category_id' => $categoriesId[0],
            'video_id' => $response->json('id')
        ]);

        $this->assertDatabaseHas('category_video', [
            'category_id' => $categoriesId[1],
            'video_id' => $response->json('id')
        ]);

        $this->assertDatabaseHas('category_video', [
            'category_id' => $categoriesId[2],
            'video_id' => $response->json('id')
        ]);
    }


    public function testSyncGenres()
    {
        $genres = factory(Genre::class, 3)->create();
        $genresId = $genres->pluck('id')->toArray();
        $categoryId = factory(Category::class)->create()->id;

        $genres->each(function ($genre) use ($categoryId) {
            $genre->categories()->sync($categoryId);
        });

        $response = $this->json(
            'POST',
            $this->routeStore(),
            $this->sendData + [
                'categories_id' => [$categoryId],
                'genres_id' => [$genresId[0]],
            ]
        );

        $this->assertDatabaseHas('genre_video', [
            'genre_id' => $genresId[0],
            'video_id' => $response->json('id')
        ]);

        $response = $this->json(
            'PUT',
            route('videos.update', ['video' => $response->json('id')]),
            $this->sendData + [
                'genres_id' => [$genresId[1], $genresId[2]],
                'categories_id' => [$categoryId]
            ]
        );

        $this->assertDatabaseMissing('genre_video', [
            'genre_id' => $genresId[0],
            'video_id' => $response->json('id')
        ]);

        $this->assertDatabaseHas('genre_video', [
            'genre_id' => $genresId[1],
            'video_id' => $response->json('id')
        ]);

        $this->assertDatabaseHas('genre_video', [
            'genre_id' => $genresId[2],
            'video_id' => $response->json('id')
        ]);
    }


    public function testRollbackStore()
    {
        $controller = Mockery::mock(VideoController::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $controller->shouldReceive('validate')
            ->withAnyArgs()
            ->andReturn($this->sendData);

        $controller->shouldReceive('rulesStore')
            ->withAnyArgs()
            ->andReturn([]);

        $controller
            ->shouldReceive('handleRelations')
            ->once()
            ->andThrow(new TestException());


        $request = Mockery::mock(Request::class);

        $request->shouldReceive('get')
            ->withAnyArgs()
            ->andReturnNull();

        $hasError = false;

        try {
            $controller->store($request);
        } catch (TestException $exception) {
            $this->assertCount(1, Video::all());
            $hasError = true;
        }

        $this->assertTrue($hasError);
    }

    public function testRollbackUpdate()
    {
        $controller = Mockery::mock(VideoController::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $controller->shouldReceive('findOrFail')
            ->withAnyArgs()
            ->andReturn($this->video);

        $controller->shouldReceive('validate')
            ->withAnyArgs()
            ->andReturn($this->sendData);

        $controller->shouldReceive('rulesUpdate')
            ->withAnyArgs()
            ->andReturn([]);

        $controller
            ->shouldReceive('handleRelations')
            ->once()
            ->andThrow(new TestException());


        $request = Mockery::mock(Request::class);


        $request->shouldReceive('get')
            ->withAnyArgs()
            ->andReturnNull();

        $hasError = false;

        try {
            $controller->update($request, 1);
        } catch (TestException $exception) {
            $this->assertCount(1, Video::all());
            $hasError = true;
        }

        $this->assertTrue($hasError);
    }

    public function testDestroy()
    {
        $response = $this->json(
            'DELETE',
            route('videos.destroy', [
                'video' => $this->video->id
            ])
        );

        $response->assertStatus(204);

        $this->assertNull(Video::find($this->video->id));

        $this->assertNotNull(Video::withTrashed()->find($this->video->id));
    }

    protected function routeStore()
    {
        return route('videos.store');
    }


    protected function routeUpdate()
    {
        return route('videos.update', ['video' => $this->video->id]);
    }

    protected function model()
    {
        return Video::class;
    }
}
