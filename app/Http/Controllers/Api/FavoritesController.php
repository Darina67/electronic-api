<?php

namespace App\Http\Controllers\Api;

use App\Models\Favorite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


use Illuminate\Support\Facades\DB;

class FavoritesController extends Controller
{
        public function index()
        {
            $favorites = Favorite::all();

            if ($favorites->count() > 0) {
                $data = [
                    'status' => 200,
                    'favorites' => $favorites,
                ];

                return response()->json($data, 200);
            } else {
                $data = [
                    'status' => 404,
                    'message' => 'Избранные товары не найдены',
                ];

                return response()->json($data, 404);
            }
        }
        public function store(Request $request)
        {
            $itemId = $request->input('itemId');

            // Создание новой записи в модели Favorite
            $favorite = Favorite::create([
                'itemId' => $itemId,
            ]);

            if($favorite){
                $data = [
                    'status' => 200,
                    'message' => 'Товар был добавлен в закладки'
                ];
                return response()->json($data, 200);
            } else {
                $data = [
                    'status' => 500,
                    'message' => 'Произошла ошибка при добавлении товара в закладки'
                ];
                return response()->json($data, 500);
            }
        }
        public function destroy($id){
            $favorite = Favorite::find($id);
            if($favorite){
                $favorite->delete();
                $data = [
                    'status' => 200,
                    'message' => 'Товар удален из избранных'
                ];
                return response()->json($data, 200);
            }
            else{
                $data = [
                    'status' => 404,
                    'message' => 'Товар не найден в избранных'
                ];
                return response()->json($data, 404);
            }
        }
        public function relations_items(Request $request)
        {
          // Получаем данные из таблицы Favorites с учетом параметра _relations
          $favorites = DB::table('favorites')->get();

          // Формируем массив для ответа
          $response = [];

          // Обрабатываем каждую запись
          foreach ($favorites as $favorite) {
              // Если параметр _relations равен items, заменяем itemId на данные из таблицы Items
              if ($request->has('_relations') && $request->_relations == 'items') {
                  // Получаем данные из таблицы Items, связанные с текущей записью Favorites
                  $itemData = DB::table('items')->where('id', $favorite->itemId)->first();

                  // Если данные найдены, заменяем itemId на объект item
                  if ($itemData) {
                      $favorite->item = $itemData;
                      unset($favorite->itemId);
                  }
              }

              $response[] = $favorite;
          }

          // Возвращаем сформированный ответ
          return response()->json(['status' => 200, 'favorites' => $response]);
      }

}
