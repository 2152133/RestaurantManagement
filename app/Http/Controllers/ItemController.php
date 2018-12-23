<?php

namespace App\Http\Controllers;

use App\Http\Resources\Item as ItemResource;
use App\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        // $items = Item::orderBy('type', 'asc')->paginate(5);
        $items = Item::orderBy('created_at', 'desc')->paginate(5);

        // Return collection of items as a resource
        return ItemResource::collection($items);
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'name' => 'required|string|max:50|unique:items,name',
            'type'=> 'required|in:dish,drink',
            'description' => 'required|string|max:200',
            'photo_url' => 'nullable',
            'price' => 'required|between:0,99.99'
        ]);
        
        $item = new Item();
        if (strpos($request->input('photo_url'), 'data:image/') !== false) {
            $exploded = explode(',', $request->photo_url);
            $decoded = base64_decode($exploded[1]);
            if (str_contains($exploded[0], 'jpeg') || str_contains($exploded[0], 'jpg')) {
                $extention = 'jpg';
            } else {
                $extention = 'png';
            }

            $fileName = str_random().'.'.$extention;


            $path = storage_path('app/public/items/').$fileName;
            file_put_contents($path, $decoded);
            
            $item->fill($request->except('photo_url') + [
                'photo_url' => $fileName,
            ]);
        } else {
            $item->fill($request->all());
        }
        $item->save();
        return response()->json(new ItemResource($item), 201);
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:items,name,'.$id,
            'type'=> 'required|in:dish,drink',
            'description' => 'required|string|max:200',
            'photo_url' => 'nullable',
            'price' => 'required|between:0,99.99'
        ]);
        $item = Item::findOrFail($id);
        if (strpos($request->input('photo_url'), 'data:image/') !== false) {

            $exploded = explode(',', $request->photo_url);
            $decoded = base64_decode($exploded[1]);
            if (str_contains($exploded[0], 'jpeg') || str_contains($exploded[0], 'jpg')) {
                $extention = 'jpg';
            } else {
                $extention = 'png';
            }

            $fileName = str_random().'.'.$extention;


            $path = storage_path('app/public/items/').$fileName;
            file_put_contents($path, $decoded);

            //dd(Storage::disk('local')->putFileAs('items/'.$fileName));

            // mostra os items com patch
            //dd($request->getContent());dd(/'.$fileName);
            $item->update($request->except('photo_url') + [
                'photo_url' => $fileName,
            ]);
        } else {
            $item->update($request->all());
        }
        $item->save();
        return new ItemResource($item);
    }


    public function all()
    {
        $allItems = DB::table('items')
            ->get();
        return $allItems;
    }

    public function show($id)
    {
        $item = Item::findOrFail($id);
        return new ItemResource($item);
    }

    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete()
        return new ItemResource($item);
    }
}
