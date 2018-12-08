<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Token;
use App\Repositories\TokenResporitory;
use Illuminate\Http\Request;

class TokenController extends ApiController
{

    private $token;

    public function __construct(TokenResporitory $token)
    {
        $this->token = $token;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(File $file)
    {
        $tokens = $file->tokens()->get();

        return $this->respond($tokens);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, File $file)
    {
        $token = $file->tokens()->create($request->all());

        return $this->respondCreated($token);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Token $token
     * @return \Illuminate\Http\Response
     */
    public function show(File $file , Token $token)
    {
        return $this->respond($token);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Token $token
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, File $file, Token $token)
    {
        $token->update($request->all());

        $token = $this->token->find($token->id);

        return $this->respond($token);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Token $token
     * @return \Illuminate\Http\Response
     */
    public function destroy(File $file, Token $token)
    {
        $token->delete();

        return $this->respondSuccess();
    }
}
