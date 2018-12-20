<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\Http\Resources\Item as ItemResource;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get items
        $items = Item::orderBy('type', 'asc')->paginate(5);

        // Return collection of items as a resource
        return ItemResource::collection($items);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:items,name',
            'type'=> 'required|in:dish,drink',
            'description' => 'required|string|max:200',
            'photo_url' => 'nullable',
            'price' => 'required|between:0,99.99'
        ]);

        // if ($request->isMethod('patch')) {
        //     $item = Item::findOrFail($request->id);    
        //     // mostra os items com patch
        //     //dd($request->getContent());
        //     //dd($request->all());
        //     $item->update($request->all());
        //     return new ItemResource($item);
        // } 
        dd($request->all());
        $item = new Item();
        $item->fill($request->all());
        $item->save();
        return response()->json(new ItemResource($item), 201);
    }

    public function update(Request $request, $id)
    {
        //dd($request);
        $request->validate([
            'name' => 'required|string|max:50|unique:items,name,'.$id,
            'type'=> 'required|in:dish,drink',
            'description' => 'required|string|max:200',
            'photo_url' => 'nullable',
            'price' => 'required|between:0,99.99'
        ]);
        //dd($request);
        $item = Item::findOrFail($id);    
        // mostra os items com patch
        //dd($request->getContent());
        $item->update($request->all());
        return new ItemResource($item);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get item
        $item = Item::findOrFail($id);

        // Return item as a resource
        return new ItemResource($item);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get item
        $item = Item::findOrFail($id);

        // Method = delete -> return deleted item 
        if ($item->delete()) {
            return new ItemResource($item);
        }
    }
}
