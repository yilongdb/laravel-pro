<?php

namespace Tests\Unit;

use App\Models\Component;
use App\Models\File;
use App\Models\Layer;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ComponentTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->withHeaders($this->headers);
        $this->file = $this->loggedInUser->files()->save(factory(File::class)->make());
    }

    /** @test */
    public function it_returns_all_components()
    {


        $components = $this->file->components()->saveMany(factory(Component::class)->times(2)->make());

        $response = $this->json('get', "/api/files/{$this->file->id}/components");

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    ['name' => $components[0]->name, 'id' => $components[0]->id],
                    ['name' => $components[1]->name, 'id' => $components[1]->id]
                ]
            );
    }

    /** @test */
    public function it_returns_the_component_ok_creating_a_component_by_the_user()
    {
        $data = [
            'name' => 'component name'
        ];

        $response = $this->json('post', "/api/files/{$this->file->id}/components", $data);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson(
                [
                    'name' => $data['name']
                ]
            );
    }

    /** @test */
    public function it_returns_the_created_component_by_the_component_id()
    {
        $data = $this->file->components()->save(factory(Component::class)->make());

        $response = $this->json('get', "/api/files/{$this->file->id}/components/{$data->id}");

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
    public function it_returns_the_created_component_and_include_layer_by_the_component_id()
    {
        $component = $this->file->components()->save(factory(Component::class)->make());

        Layer::truncate();

        $component->layers()
            ->save(
                factory(\App\Models\Layer::class)->make()
            )
            ->each(function ($layer) use ($component) {

                $subLayer = factory(\App\Models\Layer::class)->create([
                    'component_id' => $component->id,
                    'parent_id' => $layer->id
                ]);
            });

        $layers = Layer::all();

        $response = $this->json('get', "/api/files/{$this->file->id}/components/{$component->id}");

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    [
                        'id' => $component->id,
                        'name' => $component->name,
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
                    ]
                ]
            );
    }

    /** @test */
    public function it_returns_the_updated_component_after_update_component()
    {
        $data = $this->file->components()->save(factory(Component::class)->make());
        $newName = 'update component name';
        $response = $this->json('put', "/api/files/{$this->file->id}/components/{$data->id}", [
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
    public function it_returns_ok_after_delete_component()
    {
        $data = $this->file->components()->save(factory(Component::class)->make());

        $response = $this->json('delete', "/api/files/{$this->file->id}/components/{$data->id}");

        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseMissing('components', ['id' => $data->id]);

    }
}
