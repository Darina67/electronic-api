<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Str;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{


    public function index(Request $request)
    {
        $sortBy = $request->query('sortBy');
        $title = $request->query('title');

        $items = Item::query();

        if ($sortBy) {
            $direction = Str::startsWith($sortBy, '-') ? 'desc' : 'asc';
            $column = ltrim($sortBy, '-');

            // Проверка наличия столбца в модели Item
            if (in_array($column, ['title', 'price'])) {
                $items->orderBy($column, $direction);
            }
        }

        // Фильтрация по названию, если параметр title указан
        if ($title) {
            $items->where(function ($query) use ($title) {
                $query->where('title', 'like', '%' . $title . '%')
                    ->orWhere('title', $title);
            });
        }

        $items = $items->get();

        if ($items->count() > 0) {
            $data = [
                'status' => 200,
                'items' => $items,
            ];

            return response()->json($data, 200);
        } else {
            $data = [
                'status' => 404,
                'message' => 'Товары не найдены',
            ];

            return response()->json($data, 404);
        }
    }

    public function store(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string',
                'description'=>'required|string',
                'price' =>'required|integer',
                'imageUrl' =>'required|string',
                'count'=>'required|integer',
            ]);
            if($validator->fails()){
                $data = [
                    'status'=> 422,
                    'errors' => $validator->messages()
                ];
                return response()->json($data,422);
            }
            else{
                $item = Item::create([
                    'title' => $request->title,
                    'description' => $request->description,
                    'price' => $request->price,
                    'imageUrl' => $request->imageUrl,
                    'count' => $request->count,
                ]);
                if($item){
                    $data = [
                        'status' => 200,
                        'message' => 'Товар был добавлен'
                    ];
                    return response()->json($data, 200);
                }else{
                    $data = [
                        'status' => 500,
                        'message' => 'Произошла ошибка при добавлении товара'
                    ];
                    return response()->json($data, 500);
                }
            }
        }
        public function show($id)
        {
            $item = Item::find($id);
            if($item){
                $data = [
                    'status' => 200,
                    'item' => $item
                ];
                return response()->json($data, 200);
            }else{
                $data = [
                    'status' => 404,
                    'message' => 'Товар не найден!'
                ];
                return response()->json($data, 404);
            }
        }
        public function edit($id)
        {
            $item = Item::find($id);
            if($item){
                $data = [
                    'status' => 200,
                    'item' => $item
                ];
                return response()->json($data, 200);
            }else{
                $data = [
                    'status' => 404,
                    'message' => 'Товар не найден!'
                ];
                return response()->json($data, 404);
            }
        }

        public function update(Request $request, int $id){
            $validator = Validator::make($request->all(), [
                'title' => 'required|string',
                'description'=>'required|string',
                'price' =>'required|integer',
                'imageUrl' =>'required|string',
                'count'=>'required|integer',
            ]);
            if($validator->fails()){
                $data = [
                    'status'=> 422,
                    'errors' => $validator->messages()
                ];
                return response()->json($data,422);
            }
            else{
                $item = Item::find($id);
                if($item){
                    $item -> update([
                        'title' => $request->title,
                        'description' => $request->description,
                        'price' => $request->price,
                        'imageUrl' => $request->imageUrl,
                        'count' => $request->count,
                    ]);
                    $data = [
                        'status' => 200,
                        'message' => 'Товар был обновлен'
                    ];
                    return response()->json($data, 200);
                }else{
                    $data = [
                        'status' => 404,
                        'message' => 'Товар не найден'
                    ];
                    return response()->json($data, 404);
                }
            }
        }
        public function destroy($id){
            $item = Item::find($id);
            if($item){
                $item->delete();
                $data = [
                    'status' => 200,
                    'message' => 'Товар удален'
                ];
                return response()->json($data, 200);
            }
            else{
                $data = [
                    'status' => 404,
                    'message' => 'Товар не найден'
                ];
                return response()->json($data, 404);
            }
        }
}
