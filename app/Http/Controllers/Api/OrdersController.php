<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Str;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class OrdersController extends Controller
{
    public function index()
    {
        $orders = Order::all();

        if ($orders->count() > 0) {
            $data = [
                'status' => 200,
                'orders' => $orders,
            ];

            return response()->json($data, 200);
        } else {
            $data = [
                'status' => 404,
                'message' => 'Оформленные заказы не найдены',
            ];

            return response()->json($data, 404);
        }
    }
    public function store(Request $request)
    {
        // Создание новой записи в модели Order
        $order = Order::create([
            'itemstId' => $request->itemstId,
            'userId' => $request->userId,
            'adress' => $request->adress,
            'statusId' => $request->statusId,
            'totalPrice' => $request->totalPrice,
        ]);

        if ($order) {
            $data = [
                'status' => 200,
                'message' => 'Заказ был добавлен',
                'id' => $order->id, // Получение идентификатора созданного заказа
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                'status' => 500,
                'message' => 'Произошла ошибка при создании заказа'
            ];
            return response()->json($data, 500);
        }
    }

    }
