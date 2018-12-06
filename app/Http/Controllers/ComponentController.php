<?php

namespace App\Http\Controllers;

use App\Models\Component;
use App\Models\File;
use App\Repositories\ComponentResporitory;
use Illuminate\Http\Request;

class ComponentController extends ApiController
{

    private $component;

    public function __construct(ComponentResporitory $component)
    {
        $this->component = $component;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(File $file)
    {
        $components = $file->components()->get();

        return $this->respond($components);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, File $file)
    {
        $component = $file->components()->create($request->all());

        return $this->respondCreated($component);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Component $component
     * @return \Illuminate\Http\Response
     */
    public function show(Component $component)
    {
        //todo need convert to layer tree or frontend convert
        $component = $component->with('layer')->get();

        return $this->respond($component);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Component $component
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, File $file, Component $component)
    {
        $component->update($request->all());

        return $this->respondSuccess();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Component $component
     * @return \Illuminate\Http\Response
     */
    public function destroy(File $file, Component $component)
    {
        $component->delete();

        return $this->respondSuccess();
    }
}
