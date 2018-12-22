<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\RestaurantTable;
use App\Meal;
use App\Http\Resources\RestaurantTable as TableResource;
use Carbon\Carbon;
class RestaurantTableController extends Controller
{
    public function all()
    {
        // Get meals
        $restaurantTables = RestaurantTable::whereNull('deleted_at')->orderBy('table_number', 'asc')->paginate(5);

        // Return collection of orders as a resource
        return TableResource::collection($restaurantTables);
    }

    public function create($newTableNumber){
        $table = RestaurantTable::find($newTableNumber);
        if($table != null){
            Abort(403, 'Table already exists');
        }
        $newTable = new RestaurantTable;
        $newTable->table_number = $newTableNumber;
        if($newTable->save()){
            return new TableResource($newTable);
        }
    }

    //Todo
    public function update(Request $request, $tableId){
        /*$table = RestaurantTable::findOrFail($tableId);
        $table->table_number = $request->newTableNumber;
        $meals = Meal::where('table_number', $tableId)->get();
        foreach($meals as $meal){
            $meal->table_number = $request->newTableNumber;
            $meal->save();
        }
        if($table->save()){
            return new RestaurantTableResource($table);
        }*/
    }

    public function delete($tableId){
            $table = RestaurantTable::findOrFail($tableId);
            $table->delete();
    }
}
