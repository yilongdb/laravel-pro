<?php

namespace Tests\Unit;

use App\Models\Token;
use App\Models\File;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TokenTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->withHeaders($this->headers);
        $this->file = $this->loggedInUser->files()->save(factory(File::class)->make());
    }

    /** @test */
    public function it_returns_all_tokens()
    {
        $tokens = $this->file->tokens()->saveMany(factory(Token::class)->times(2)->make());

        $response = $this->json('get', "/api/files/{$this->file->id}/tokens");

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    [
                        'name' => $tokens[0]->name,
                        'id' => $tokens[0]->id,
                        'value' => $tokens[0]->value,
                        'type' => $tokens[0]->type
                    ],
                    [
                        'name' => $tokens[1]->name,
                        'id' => $tokens[1]->id,
                        'value' => $tokens[1]->value,
                        'type' => $tokens[1]->type
                    ]
                ]
            );
    }

    /** @test */
    public function it_returns_the_token_ok_creating_a_token_by_the_user()
    {
        $data = [
            'name' => 'token name',
            'value' => 'token value',
            'type' => 'border',
        ];

        $response = $this->json('post', "/api/files/{$this->file->id}/tokens", $data);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson(
                [
                    'name' => $data['name'],
                    'value' => $data['value'],
                    'type' => $data['type'],
                ]
            );
    }

    /** @test */
    public function it_returns_the_created_token_by_the_token_id()
    {
        $data = $this->file->tokens()->save(factory(Token::class)->make());

        $response = $this->json('get', "/api/files/{$this->file->id}/tokens/{$data->id}");

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'name' => $data->name,
                    'id' => $data->id,
                    'value' => $data->value,
                    'type' => $data->type,
                ]
            );
    }


    /** @test */
    public function it_returns_the_updated_token_after_update_token()
    {
        $data = $this->file->tokens()->save(factory(Token::class)->make());
        $newName = 'update token name';
        $newValue = 'update token value';
        $newBorder = 'border';
        $response = $this->json('put', "/api/files/{$this->file->id}/tokens/{$data->id}", [
            'name' => $newName,
            'value' => $newValue,
            'type' => $newBorder,
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'name' => $newName,
                    'value' => $newValue,
                    'type' => $newBorder,
                    'id' => $data->id
                ]
            );
    }

    /** @test */
    public function it_returns_ok_after_delete_token()
    {
        $data = $this->file->tokens()->save(factory(Token::class)->make());

        $response = $this->json('delete', "/api/files/{$this->file->id}/tokens/{$data->id}");

        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseMissing('tokens', ['id' => $data->id]);

    }
}
