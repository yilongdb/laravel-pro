<?php

namespace Tests\Unit;

use App\Models\File;
use App\Models\Layer;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;


class FileTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();

        $this->withHeaders($this->headers);
    }

    /** @test */
    public function it_returns_all_files()
    {
        $files = $this->loggedInUser->files()->saveMany(factory(File::class)->times(2)->make());

        $data = [];
        $response = $this->json('get', '/api/files', $data);

//        var_dump($this->faker);
        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    ['name' => $files[0]->name, 'id' => $files[0]->id],
                    ['name' => $files[1]->name, 'id' => $files[1]->id]
                ]
            );
    }

    /** @test */
    public function it_returns_the_file_ok_creating_a_file_by_the_user()
    {
        $data = [
            'name' => 'file name'
        ];

        $response = $this->json('post', '/api/files', $data);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson(
                [
                    'name' => $data['name']
                ]
            );
    }

    /** @test */
    public function it_returns_the_created_file_by_the_file_id()
    {
//        File::truncate();
//        $this->loggedInUser->files()->delete();
        $data = $this->loggedInUser->files()->save(factory(File::class)->make());

        $response = $this->json('get', "/api/files/{$data->id}", []);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    [
                        'name' => $data->name,
                        'id' => $data->id
                    ]
                ]
            );
    }

    /** @test */
    public function it_returns_the_created_file_and_include_component_token_layer_by_the_file_id()
    {
//        File::truncate();
//        $this->loggedInUser->files()->delete();
        $file = $this->loggedInUser->files()->save(factory(File::class)->make());

        $tokens = $file->tokens()->saveMany(
            factory(\App\Models\Token::class)
                ->times(2)
                ->make()
        );

        $components = $file->components()->saveMany(
            factory(\App\Models\Component::class)
                ->times(2)
                ->make()
        );

        $layers = [];
//        Layer::truncate();
        $components->each(function ($component) use (&$layers){
            $component->layers()->save(
                factory(\App\Models\Layer::class)->make()
            )
                ->each(function ($layer) use ($component , &$layers) {

                    $subLayer = factory(\App\Models\Layer::class)->create([
                        'component_id' => $component->id,
                        'parent_id' => $layer->id
                    ]);
                    $layers[] = $layer;
                    $layers[] = $subLayer;
//
                });
        });

        $layers = Layer::all();
        $response = $this->json('get', "/api/files/{$file->id}", []);


//        var_dump($response->getContent());
//        print_r($response->getContent());
        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    [
                        'name' => $file->name,
                        'id' => $file->id,
                        'user' => [
                            'id' => $this->loggedInUser->id
                        ],
                        'tokens' => [
                            [
                                'id' => $tokens[0]->id,
                                'name' => $tokens[0]->name,
                            ],
                            [
                                'id' => $tokens[1]->id,
                                'name' => $tokens[1]->name,
                            ]
                        ],
                        'components' => [
                            [
                                'id' => $components[0]->id,
                                'name' => $components[0]->name,
                                'layers' => [
                                    [
                                        'id' => $layers[0]->id,
                                        'name' => $layers[0]->name,
                                    ],
                                    [
                                        'id' => $layers[1]->id,
                                        'name' => $layers[1]->name,
                                    ],
                                ]
                            ],
                            [
                                'id' => $components[1]->id,
                                'name' => $components[1]->name,
                                'layers' => [
                                    [
                                        'id' => $layers[2]->id,
                                        'name' => $layers[2]->name,
                                    ],
                                    [
                                        'id' => $layers[3]->id,
                                        'name' => $layers[3]->name,
                                    ],
                                ]
                            ]
                        ],
                    ]
                ]
            );
    }

    /** @test */
    public function it_returns_the_updated_file_after_update_file()
    {
        $data = $this->loggedInUser->files()->save(factory(File::class)->make());
        $newName = 'update file name';
        $response = $this->json('put', "/api/files/{$data->id}", [
            'name' => $newName
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'name' => $newName,
                    'id' => $data->id
                ]
            );
    }

    /** @test */
    public function it_returns_ok_after_delete_file()
    {
        $data = $this->loggedInUser->files()->save(factory(File::class)->make());

        $response = $this->json('delete', "/api/files/{$data->id}");

        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseMissing('files', ['id' => $data->id]);

    }


}
