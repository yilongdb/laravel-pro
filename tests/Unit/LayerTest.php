<?php

namespace Tests\Unit;

use App\Models\Component;
use App\Models\File;
use App\Models\Layer;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LayerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->withHeaders($this->headers);
        $this->component = $this->loggedInUser
            ->files()->save(factory(File::class)->make())
            ->components()->save(factory(Component::class)->make());
    }

    /** @test */
    public function it_returns_all_layers()
    {
        $layers = $this->component->layers()->saveMany(factory(Layer::class)->times(2)->make());

        $response = $this->json('get', "/api/components/{$this->component->id}/layers");

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    ['name' => $layers[0]->name, 'id' => $layers[0]->id, 'type' => $layers[0]->type],
                    ['name' => $layers[1]->name, 'id' => $layers[1]->id, 'type' => $layers[1]->type]
                ]
            );
    }

    /** @test */
    public function it_returns_the_layer_ok_creating_a_layer_by_the_user()
    {
        $data = [
            'name' => 'layer name',
            'type' => 'box',
        ];

        $response = $this->json('post', "/api/components/{$this->component->id}/layers", $data);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson(
                [
                    'name' => $data['name'], 'type' => $data['type']
                ]
            );
    }

    /** @test */
    public function it_returns_the_created_layer_by_the_layer_id()
    {
        $data = $this->component->layers()->save(factory(Layer::class)->make());

        $response = $this->json('get', "/api/components/{$this->component->id}/layers/{$data->id}");

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'name' => $data->name,
                    'type' => $data->type,
                    'id' => $data->id
                ]
            );
    }

    /** @test */
    public function it_returns_the_created_layer_and_include_children_layers_by_the_layer_id()
    {
        Layer::truncate();
        $layers1 = $this->component->layers()->save(factory(Layer::class)->make());

        $layers2 = $this->component->layers()->times(2)->saveMany(function ($layer) {
            factory(Layer::class)->make(
                [
                    'component_id' => $this->component->id,
                    'parent_id' => $layers1[0]->id
                ]
            );
        })
            ->each(function ($layer) {
                factory(\App\Models\Layer::class)->create([
                    'component_id' => $this->component->id,
                    'parent_id' => $layer->id
                ]);
            });

        $layers = Layer::all();

        $response = $this->json('get', "/api/components/{$this->component->id}/layers/{$layer->id}");

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    [
                        'id' => $layer->id,
                        'name' => $layer->name,
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
    public function it_returns_the_updated_layer_after_update_layer()
    {
        $data = $this->component->layers()->save(factory(Layer::class)->make());
        $newName = 'update layer name';
        $newType = 'box';
        $response = $this->json('put', "/api/components/{$this->component->id}/layers/{$data->id}", [
            'name' => $newName,
            'type' => $newType,
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'name' => $newName,
                    'type' => $newType,
                    'id' => $data->id
                ]
            );
    }

    /** @test */
    public function it_returns_ok_after_delete_layer()
    {
        $data = $this->component->layers()->save(factory(Layer::class)->make());

        $response = $this->json('delete', "/api/components/{$this->component->id}/layers/{$data->id}");

        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseMissing('layers', ['id' => $data->id]);

    }
}
