<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DeliveryController extends Controller
{
    public function index()
    {
        return Inertia::render('Delivery/Index');
    }

    public function store(Request $request)
    {
        // Валидация данных
        $validatedData = $request->validate([
            'serviceType' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
        ]);

        // Сохранение данных в базе
        Delivery::create([
            'service_type' => $validatedData['serviceType'],
            'name' => $validatedData['name'],
            'address' => $validatedData['address'],
            'phone' => $validatedData['phone'],
            'email' => $validatedData['email'],
        ]);

        // Возвращение успешного ответа
        return response()->json(['message' => 'Delivery information saved successfully'], 200);
    }
}
