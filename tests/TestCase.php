<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
//use Faker\Generator as Faker;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;
//    use DatabaseTransactions;
    protected $loggedInUser;

    protected $user;

    protected $headers;

    protected $token;

    public function setUp()
    {
        parent::setUp();

//        $this->faker = $faker;
        $users = factory(User::class)->times(2)->create();

        $this->loggedInUser = $users[0];
        $this->loggedInUser->confirmed = true;
        $this->loggedInUser->save();
        $this->token = JWTAuth::fromUser($this->loggedInUser);

        $this->user = $users[1];
        $this->user->confirmation_code = str_random(30);
        $this->user->save();

        $this->headers = [
            'Authorization' => "Bearer {$this->token}"
        ];
    }
}
