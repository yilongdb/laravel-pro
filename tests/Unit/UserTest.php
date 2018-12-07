<?php

namespace Tests\Unit;

use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;


class UserTest extends TestCase
{


    /** @test */
    public function it_returns_ok_when_logged_in_by_email()
    {
        $data = [
            'email' => '2974105336@qq.com'
        ];
        $response = $this->json('POST' , '/api/auth/email-login' , $data);

        $response->assertStatus(Response::HTTP_OK)->assertJson(
            [
                'message' => 'email is sending'
            ]
        );
    }

    /** @test */
    public function it_returns_ok_when_logged_in_by_no_email()
    {
        $data = [
            'not_email_field' => '2974105336@qq.com'
        ];
        $response = $this->json('POST' , '/api/auth/email-login' , $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_returns_error_when_logged_in_by_not_valid_email()
    {
        $data = [
            'email' => '2974105336qq.com'
        ];
        $response = $this->json('POST' , '/api/auth/email-login' , $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_returns_token_when_email_confirm_by_user_id_and_confirmation_code()
    {
        $data = [
            'id' => $this->user->id,
            'confirmation_code' => $this->user->confirmation_code
        ];
        $response = $this->json('POST' , '/api/auth/email-confirm' , $data);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in'
        ]);;
    }

    /** @test */
    public function it_returns_ok_logout_by_correct_jwt_token(){
        $data = [
            'id' => $this->user->id,
            'confirmation_code' => $this->user->confirmation_code
        ];
        $response = $this->withHeaders($this->headers)->json('POST' , '/api/auth/logout' , $data);

        $response->assertStatus(Response::HTTP_OK);
    }


    /** @test */
    public function it_returns_new_jwt_token_by_refresh_token(){
        $data = [];
        $response = $this->withHeaders($this->headers)->json('get' , '/api/auth/refresh' , $data);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_in'
            ]);;
    }
}
