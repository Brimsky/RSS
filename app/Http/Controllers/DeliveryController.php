<?php

namespace App\Http\Controllers;

use Inertia\Inertia; // Make sure you have this import if you're using Inertia

class DeliveryController extends Controller
{
    public function index()
    {
        // Return view or Inertia response
        return Inertia::render('Delivery/Index'); // Adjust if using Inertia
        // or if using traditional blade views
        // return view('delivery.index');
    }
}
