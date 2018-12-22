<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\RestaurantTable;
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

    public function delete($tableId){
            $table = RestaurantTable::findOrFail($tableId);
            $table->delete();
    }
}
