<?php

namespace App\Http\Controllers;

use App\Models\Component;
use App\Models\Layer;
use App\Repositories\LayerResporitory;
use Illuminate\Http\Request;

class LayerController extends ApiController
{

    private $layer;

    public function __construct(LayerResporitory $layer)
    {
        $this->layer = $layer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Component $component)
    {
        //todo need convert to layer tree or frontend convert
        $layers = $component->layers()->get();

        return $this->respond($layers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Component $component)
    {
        $layer = $component->layers()->create($request->all());

        return $this->respondCreated($layer);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Layer $layer
     * @return \Illuminate\Http\Response
     */
    public function show(Component $component , Layer $layer)
    {
        return $this->respond($layer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Layer $layer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Component $component, Layer $layer)
    {
        $layer->update($request->all());

        $layer = $this->layer->find($layer->id);

        return $this->respond($layer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Layer $layer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Component $component, Layer $layer)
    {
        //todo also delete children layer
        $layer->delete();

        return $this->respondSuccess();
    }
}
