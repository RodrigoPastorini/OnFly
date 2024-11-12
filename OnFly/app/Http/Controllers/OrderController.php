<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use Carbon\Carbon;
use Mockery\Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            $validated = $request->validated();

            $order = Order::create([
                'user_id' => $user->id_user,
                'destination' => $validated['destination'],
                'departure' => Carbon::parse($validated['departure']),
                'return' => $validated['return'] ? Carbon::parse($validated['return']) : null,
            ]);

            return response()->json(['success' => 'Order created successfully', 'order' => $order], 201);

        }  catch (JWTException $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function cancel(string $orderId)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            $order = Order::find($orderId);

            if (!$order) {
                return response()->json(['error' => 'Order not found'], 404);
            }

            if ($order->user_id != $user->id_user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            if ($order->status != Order::STATUS_PENDING) {
                return response()->json(['error' => 'Order status is not pending'], 401);
            }

            $order->status = 'cancelled';
            $order->save();

            return response()->json(['success' => 'Order cancelled'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function approve(string $orderId)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            $order = Order::find($orderId);

            if (!$order) {
                return response()->json(['error' => 'Order not found'], 404);
            }

            if ($order->user_id != $user->id_user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            if ($order->status != Order::STATUS_PENDING) {
                return response()->json(['error' => 'Order status is not pending'], 401);
            }

            $order->status = 'approved';
            $order->save();

            return response()->json(['success' => 'Order approved'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function detail(string $orderId)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            $order = Order::with('user')
                ->find($orderId);

            if (!$order) {
                return response()->json(['error' => 'Order not found'], 404);
            }

            if ($order->user_id != $user->id_user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            return response()->json(['order' => [
                'user_name' => $order->user->name,
                'destination' => $order->destination,
                'departure' => $order->departure,
                'return' => $order->return ? $order->return : null,
                'status' => $order->status,
            ]], 200);

        } catch (Exception $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function list(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            $status = $request->get('status');

            if ($status) {
                $orders = Order::with('user')
                    ->where('status', $status)
                    ->get()
                ;
            } else {
                $orders = Order::with('user')->get();
            }

            $responseOrders = $orders->map(function ($order) {
                return [
                    'user_name' => $order->user->name,
                    'destination' => $order->destination,
                    'departure' => $order->departure,
                    'return' => $order->return ?? null,
                    'status' => $order->status,
                ];
            });

            return response()->json(['orders' => $responseOrders], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
}
