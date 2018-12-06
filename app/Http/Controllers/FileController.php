<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileRequest;
use App\Models\File;
use App\Repositories\FileResporitory;
use Illuminate\Http\Request;

class FileController extends ApiController
{
    private $file;

    public function __construct(FileResporitory $file)
    {
        $this->file = $file;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $files = $user->files()->get();

        return $this->respond($files);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(FileRequest $request)
    {
        $user = auth()->user();
        $file = $user->files()->create($request->all());

        return $this->respondCreated($file);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\File $file
     * @return \Illuminate\Http\Response
     */
    public function show(File $file)
    {
        $file = $file->with(
            ['user' => function ($q) {
                $q->select('user_id');
            }
            ]
        )
            ->with('component.layers')
            ->with('token')->get();

        return $this->respond($file);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\File $file
     * @return \Illuminate\Http\Response
     */
    public function update(FileRequest $request, File $file)
    {
        $file->update($request->all());
        return $this->respond($file);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\File $file
     * @return \Illuminate\Http\Response
     */
    public function destroy(File $file)
    {
        $file->delete();

        return $this->respondSuccess();
    }
}
