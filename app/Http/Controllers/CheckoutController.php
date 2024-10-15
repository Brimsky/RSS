<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class CheckoutController extends Controller
{
    // Veiksmīga darījuma lapas izvade
    public function success()
    {
        session()->forget('cart');
        return Inertia::render('Success');
    }

    // Neveiksmīgas transakcijas lapas izvade
    public function cancel()
    {
        return Inertia::render('Cancel');
    }
}
