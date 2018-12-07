<?php

namespace App\Http\Controllers;

use App\Exceptions\GoogleLoginFailException;
use App\Http\Requests\EmailConfirmPost;
use App\Http\Requests\EmailLoginPost;
use App\Http\Requests\GoogleLoginPost;
use App\Notifications\SendConfirmEmail;
use App\Repositories\UserRepository as User;
use Illuminate\Http\Request;
use Google_Client;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends ApiController
{

    private $user;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->middleware(['jwt.verify'], ['except' => ['loginByGoogle', 'loginByEmail', 'emailConfirm']]);
        $this->user = $user;
    }

    public function loginByGoogle(GoogleLoginPost $request)
    {
        $CLIENT_ID = config('google_client_id');
        $id_token = $request->input('id_token');
        $client = new Google_Client(['client_id' => $CLIENT_ID]);
        $payload = $client->verifyIdToken($id_token);

        if ($payload && isset($payload->email) && isset($payload->name)) {
            $email = $payload->email;
            $name = $payload->name;

            $user = $this->user->findBy('email', $email);
            if (!$user) {
                $user = $this->user->create(['name' => $name, 'email' => $email]);
            }
            $token = JWTAuth::fromUser($user);

            $this->respondWithToken($token);
        } else {
            throw new GoogleLoginFailException('google verify fail', Response::HTTP_UNAUTHORIZED);
        }
    }

    public function loginByEmail(EmailLoginPost $request)
    {
        $email = $request->input('email');
        list($name, $sufix) = explode('@', $email);

        $user = $this->user->findBy('email', $email);
        if (!$user) {
            $user = $this->user->create(['name' => $name, 'email' => $email]);
        }

        $user->confirmation_code = str_random(30);
        $user->save();
        //send email
        $user->notify(new SendConfirmEmail($user));
        return response()->json(['message' => 'email is sending'], Response::HTTP_OK);
    }

    public function emailConfirm(EmailConfirmPost $request)
    {
        $id = $request->input('id');
        $confirmation_code = $request->input('confirmation_code');
        $user = $this->user->confirm($id, $confirmation_code);
        $user->confirmation_code = null;
        $user->confirmed = true;
        $user->save();

        $token = JWTAuth::fromUser($user);

        return $this->respondWithToken($token);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ], Response::HTTP_OK);
    }
}
