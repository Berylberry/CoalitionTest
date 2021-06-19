<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Psy\Util\Json;

class ProductController extends Controller
{
    /***
     * Index method
     *
     * @return View
     **/
    public function index(){
        return view('products');
    }

    /***
     * Store products and send back to view
     *
     * @param Request $request
     *
     * @return Json
     **/
    public function storeProducts(Request $request){
        $path = base_path('resources/json/result.json');
//        get old data
        $oldData = json_decode(file_get_contents($path));
//        get new data from request object
        $data = $request->only(['p_name','quantity','price']);
        $data['date'] = Carbon::now()->toDateTimeString();
        $data['total'] = $request->quantity * $request->price;
//          add to existing data
        array_push($oldData, $data);
//        sort by date before rewriting
        $result = collect($oldData);
//        sort by date
        $sorted = $result->sortByDesc('date');
//        $sorted = $result->sortByDesc(function ($prods, $key) {
//            return Carbon::parse($prods->date)->getTimestamp();
//        });
//        $sorted = $result->sortByDesc([
//            fn ($a, $b) => Carbon::createFromFormat('MMMM Do YYYY, h:mm a',$a['date'])->compare(Carbon::createFromFormat('MMMM Do YYYY, h:mm a',$b['date']))
//        ]);
        $finalResult = $sorted->values()->all();
//        rewrite the file
        file_put_contents($path, json_encode($finalResult, JSON_PRETTY_PRINT));
        //format for ajax response
        $products = file_get_contents($path);
        $total = $result->sum('total');
//        dd($products);
        return response()->json(['result' => $products, 'total' => $total],200);
    }
}
